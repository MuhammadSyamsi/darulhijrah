<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<?php
use App\Models\DetailModel;
$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');
$today = date('Y-m-d');
$i = ($id && count($id)) ? $id[0] + 1 : 1;
?>

<div class="container mx-auto p-4">
  <div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-6 flex items-center">
      <span class="material-symbols-outlined mr-2">account_balance_wallet</span>
      Pembayaran Tunggakan Alumni
    </h2>

    <form action="<?= base_url('savealumni') ?>" method="post" x-data="alumniForm()" @submit.prevent="submitForm">
      <?= csrf_field(); ?>
      <input type="hidden" name="nisn" x-model="nisn">
      <input type="hidden" name="id" value="<?= $i ?>">
      <input type="hidden" name="nama" x-model="nama">
      <input type="hidden" name="kelas" x-model="kelas">
      <input type="hidden" name="program" x-model="program">

      <!-- Identitas -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium mb-1">Nama Santri</label>
          <select class="w-full border rounded px-3 py-2" x-model="nisn" @change="loadData">
            <option value="">- Pilih Nama -</option>
            <?php foreach ($cari as $c): ?>
              <option value="<?= $c['nisn'] ?>"><?= $c['nama'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Pembayaran</label>
          <input type="date" class="w-full border rounded px-3 py-2" name="tanggal" value="<?= $today ?>" required>
        </div>
      </div>

      <!-- Tunggakan -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium mb-1">Tunggakan SPP</label>
          <input type="number" class="w-full border rounded px-3 py-2 bg-gray-100" x-model="tunggakanspp" disabled>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Tunggakan TL</label>
          <input type="number" class="w-full border rounded px-3 py-2 bg-gray-100" x-model="tunggakantl" disabled>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Tunggakan DU</label>
          <input type="number" class="w-full border rounded px-3 py-2 bg-gray-100" x-model="tunggakandu" disabled>
        </div>
      </div>

      <!-- Pembayaran -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium mb-1">Nominal Pembayaran</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="saldomasuk" required>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Rekening</label>
          <select class="w-full border rounded px-3 py-2" name="rekening" required>
            <option value="" selected disabled>- Pilih Rekening -</option>
            <option value="Muamalat Salam">Muamalat Salam</option>
            <option value="Jatim Syariah">Jatim Syariah</option>
            <option value="BSI">BSI</option>
            <option value="Uang Saku">Uang Saku</option>
            <option value="Muamalat Yatim">Muamalat Yatim</option>
            <option value="Tunai">Tunai</option>
            <option value="lain-lain">Lain-lain</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Keterangan</label>
          <input type="text" class="w-full border rounded px-3 py-2" name="keterangan">
        </div>
      </div>

      <!-- Detail Pemasukan -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div>
          <label class="block text-sm font-medium mb-1">Bayar Daftar Ulang</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="tunggakandu" value="0">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Bayar Tunggakan TL</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="tunggakantl" value="0">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Bayar SPP</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="tunggakanspp" value="0">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Uang Saku</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="uangsaku" value="0">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Infaq</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="infaq" value="0">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Formulir</label>
          <input type="number" class="w-full border rounded px-3 py-2" name="formulir" value="0">
        </div>
      </div>

      <!-- Riwayat Transaksi -->
      <div class="mb-6">
        <label class="block text-sm font-semibold mb-2">Riwayat Transaksi Terakhir</label>
        <ul class="border rounded divide-y divide-gray-200" x-ref="riwayatList">
          <li class="px-4 py-2 text-gray-500">Belum ada transaksi.</li>
        </ul>
      </div>

      <button type="submit" class="w-full bg-blue-600 text-white rounded py-2 font-semibold flex justify-center items-center gap-2 hover:bg-blue-700">
        <span class="material-symbols-outlined">print</span> Buat Kwitansi
      </button>
    </form>
  </div>
</div>

<script>
function alumniForm() {
  return {
    nisn: '',
    nama: '',
    kelas: '',
    program: '',
    tunggakanspp: 0,
    tunggakantl: 0,
    tunggakandu: 0,
    riwayat: [],
    
    async loadData() {
      if(!this.nisn) return;

      // Ambil identitas
      const resIdentitas = await fetch(`<?= base_url('api/alumni/') ?>${this.nisn}`);
      const data = await resIdentitas.json();
      this.nama = data.nama;
      this.kelas = data.kelas;
      this.program = data.program;
      this.tunggakanspp = data.tunggakanspp ?? 0;
      this.tunggakantl = data.tunggakantl ?? 0;
      this.tunggakandu = data.tunggakandu ?? 0;

      // Ambil riwayat
      const resTrans = await fetch(`<?= base_url('api/kedua/') ?>${this.nisn}`);
      this.riwayat = await resTrans.json();
      this.renderRiwayat();
    },

    renderRiwayat() {
      const list = this.$refs.riwayatList;
      list.innerHTML = '';
      if(this.riwayat.length === 0) {
        list.innerHTML = '<li class="px-4 py-2 text-gray-500">Belum ada transaksi.</li>';
      } else {
        this.riwayat.forEach(item => {
          const li = document.createElement('li');
          li.className = 'px-4 py-2 flex justify-between items-center';
          li.innerHTML = `
            <div>
              <strong>${item.tanggal ?? '-'}</strong><br>
              <small>${item.keterangan ?? '-'}</small>
            </div>
            <div class="text-right">
              <span class="text-gray-500">${item.rekening}</span><br>
              <strong>${this.formatRupiah(item.nominal)}</strong>
            </div>
          `;
          list.appendChild(li);
        });
      }
    },

    formatRupiah(value) {
      if(!value) return 'Rp0';
      return 'Rp' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    },

    submitForm() {
      this.$el.submit();
    }
  }
}
</script>

<?= $this->endSection(); ?>