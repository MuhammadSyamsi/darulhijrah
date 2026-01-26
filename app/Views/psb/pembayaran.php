<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<?php
use App\Models\DetailModel;
$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');
$today = date('Y-m-d');
$i = ($id == null) ? 1 : max($id) + 1;
?>

<div class="container mx-auto p-4">
  <div class="bg-white shadow rounded-xl p-6">

    <h3 class="text-2xl font-semibold mb-6">
      Pembayaran Daftar Ulang PSB
    </h3>

    <form
      x-data="psbBayar()"
      action="<?= base_url('bayar') ?>"
      method="post"
      @submit.prevent="submitForm"
      class="space-y-6"
    >
      <?= csrf_field(); ?>

      <!-- hidden -->
      <input type="hidden" name="nisn" x-model="santri.nisn">
      <input type="hidden" name="id" value="<?= $i ?>">
      <input type="hidden" name="nama" x-model="santri.nama">
      <input type="hidden" name="kelas" x-model="santri.kelas">
      <input type="hidden" name="saldomasuk" x-model="pembayaran.total">
      <input type="hidden" name="tdu" x-model="pembayaran.tdu">
      <input type="hidden" name="infaq" x-model="pembayaran.infaq">

      <!-- PILIH SANTRI -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
          <label class="form-label">Nama Santri</label>
          <select x-model="santri.nisn" @change="changeSantri"
                  class="form-input">
            <option value="">-- Pilih Santri --</option>
            <?php foreach ($cari as $c): ?>
              <option value="<?= $c['nisn'] ?>">
                <?= $c['nama'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="form-label">Daftar Ulang</label>
            <input class="form-input bg-gray-100"
                   :value="format(du)" disabled>
          </div>
          <div>
            <label class="form-label">Sisa DU</label>
            <input class="form-input bg-gray-100"
                   :value="format(tunggakan)" disabled>
          </div>
        </div>
      </div>

      <!-- PEMBAYARAN -->
      <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div>
          <label class="form-label">Nominal</label>
          <input class="form-input"
                 x-model="display.total"
                 @input="syncTotal">
        </div>

        <div>
          <label class="form-label">Tanggal</label>
          <input type="date"
                 name="tanggal"
                 value="<?= $today ?>"
                 class="form-input">
        </div>

        <div>
          <label class="form-label">Rekening</label>
          <select name="rekening" class="form-input">
            <option>Muamalat Salam</option>
            <option>Jatim Syariah</option>
            <option>BSI</option>
            <option>Uang Saku</option>
            <option>Muamalat Yatim</option>
            <option>Tunai</option>
            <option>Lain-lain</option>
          </select>
        </div>

        <div>
          <label class="form-label">Keterangan</label>
            <input
              type="text"
              name="keterangan"
              class="form-input"
              x-model="keterangan">
        </div>
      </div>

      <!-- AKSI -->
      <div class="grid grid-cols-2 gap-4">
        <button type="button"
                class="btn btn-green"
                @click="pelunasan()">
          Pelunasan DU
        </button>

        <button type="button"
                class="btn btn-yellow"
                @click="angsuran()">
          Angsuran DU
        </button>
      </div>

      <!-- RIWAYAT -->
      <div class="bg-gray-50 rounded-lg p-4">
        <h5 class="font-semibold mb-2">Riwayat Terakhir</h5>

        <template x-for="r in riwayat" :key="r.id">
          <div class="flex justify-between border-b py-1 text-sm">
            <div>
              <div class="text-gray-500" x-text="r.tanggal"></div>
              <div class="font-medium" x-text="r.keterangan"></div>
            </div>
            <div class="text-right">
              <span class="badge" x-text="r.rekening"></span>
              <div class="font-semibold" x-text="format(r.nominal)"></div>
            </div>
          </div>
        </template>
      </div>

      <!-- DETAIL -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div>
          <label class="form-label">Bayar DU</label>
          <input class="form-input"
                 x-model="display.tdu"
                 @input="syncTdu">
        </div>

        <div>
          <label class="form-label">Infaq</label>
          <input class="form-input"
                 x-model="display.infaq"
                 @input="syncInfaq">
        </div>
      </div>

      <button type="submit"
              :disabled="loading"
              class="btn btn-dark w-fit">
        Buat Kwitansi
      </button>
    </form>
  </div>
</div>

<script>
function psbBayar() {
  const parse = v => Number(v.replace(/\D/g,'')) || 0
  const format = v => v.toString().replace(/\B(?=(\d{3})+(?!\d))/g,'.')

  return {
    santri: { nisn:'', nama:'', kelas:'' },
    du: 0,
    tunggakan: 0,

    pembayaran: { total:0, tdu:0, infaq:0 },
    display: { total:'0', tdu:'0', infaq:'0' },

    keterangan: '',
    riwayat: [],
    loading: false,

    format,

    changeSantri() {
      if (!this.santri.nisn) return

      fetch(`/api/psb/${this.santri.nisn}`)
        .then(r => r.json())
        .then(d => {
          this.santri.nama = d.nama
          this.santri.kelas = d.kelas
          this.du = +d.daftarulang
          this.tunggakan = +d.tunggakandu
          this.resetBayar()
          this.loadRiwayat()
        })
    },

    resetBayar() {
      this.pembayaran = { total:0, tdu:0, infaq:0 }
      this.display = { total:'0', tdu:'0', infaq:'0' }
    },

    syncTotal() {
      this.pembayaran.total = parse(this.display.total)
      this.display.total = format(this.pembayaran.total)
    },
    syncTdu() {
      this.pembayaran.tdu = parse(this.display.tdu)
      this.display.tdu = format(this.pembayaran.tdu)
      this.syncAuto()
    },
    syncInfaq() {
      this.pembayaran.infaq = parse(this.display.infaq)
      this.display.infaq = format(this.pembayaran.infaq)
      this.syncAuto()
    },
    syncAuto() {
      this.pembayaran.total =
        this.pembayaran.tdu + this.pembayaran.infaq
      this.display.total = format(this.pembayaran.total)
    },

    pelunasan() {
      this.pembayaran.tdu = this.tunggakan
      this.display.tdu = format(this.tunggakan)
      this.keterangan = 'Pelunasan Daftar Ulang'
      this.syncAuto()
    },

    angsuran() {
      this.pembayaran.tdu = Math.floor(this.tunggakan / 2)
      this.display.tdu = format(this.pembayaran.tdu)
      this.keterangan = 'Angsuran Daftar Ulang'
      this.syncAuto()
    },

    loadRiwayat() {
      fetch(`/api/kedua/${this.santri.nisn}`)
        .then(r => r.json())
        .then(d => this.riwayat = d || [])
    },

    submitForm() {
      this.loading = true
      this.$el.submit()
    }
  }
}
</script>

<?= $this->endSection(); ?>