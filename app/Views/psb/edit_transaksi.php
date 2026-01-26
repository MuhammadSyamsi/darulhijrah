<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="w-full p-4">
    <div class="bg-white shadow-xl rounded-2xl p-6">

        <?php foreach ($edit as $c) : ?>
            <h2 class="text-2xl font-semibold mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">edit</span>
                Edit Transaksi <?= $c['nama']; ?>
            </h2>

<form x-data="formHandler" x-ref="form" @submit.prevent="submitForm" action="<?= base_url('edittungpsb'); ?>" method="post">
    <?= csrf_field(); ?>

    <!-- INFORMASI TRANSAKSI -->
    <div>
        <p class="text-lg font-semibold text-gray-800 mb-3">Informasi Transaksi</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Keterangan -->
            <div class="col-span-3">
                <label class="text-sm font-medium">Keterangan</label>
                <input type="text" name="keterangan"
                    value="<?= $c['keterangan']; ?>"
                    class="mt-1 input input-bordered w-full rounded-xl border-gray-300" />
            </div>

            <!-- Hidden Input -->
            <input type="hidden" name="nama" value="<?= $c['nama']; ?>">
            <input type="hidden" name="idtrans" value="<?= $c['idtrans']; ?>">
            <input type="hidden" name="nisn" value="<?= $c['nisn']; ?>">
            <input type="hidden" name="kelas" value="<?= $c['kelas']; ?>">

            <!-- Saldo Masuk -->
            <div>
                <label class="text-sm font-medium">Saldo Masuk</label>
                <input x-data="formatAngka" x-model="rawValue" x-on:input="format()"
                    type="text" name="saldomasuk"
                    value="<?= number_format($c['saldomasuk'], 0, ',', '.'); ?>"
                    class="w-full rounded-xl border-gray-300" />
            </div>

            <!-- Tanggal -->
            <div>
                <label class="text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal"
                    value="<?= $c['tanggal']; ?>"
                    class="mt-1 input input-bordered w-full rounded-xl border-gray-300" />
            </div>

            <!-- Rekening -->
            <div>
                <label class="text-sm font-medium">Rekening</label>
                <select name="rekening"
                    class="mt-1 w-full rounded-xl border-gray-300">
                    <option value="<?= $c['rekening']; ?>"><?= $c['rekening']; ?></option>
                    <option value="Muamalat Salam">Muamalat Salam</option>
                    <option value="Jatim Syariah">Jatim Syariah</option>
                    <option value="BSI">BSI</option>
                    <option value="Uang Saku">Uang Saku</option>
                    <option value="Muamalat Yatim">Muamalat Yatim</option>
                    <option value="Tunai">Tunai</option>
                    <option value="lain-lain">Lain-lain</option>
                </select>
            </div>

        </div>
    </div>
    <?php endforeach; ?>

    <!-- EDIT TUNGGAKAN -->
    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-3">Edit Tunggakan</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($santri as $s) : ?>
                <div>
                    <label class="text-sm font-medium">Daftar Ulang</label>
                    <input x-data="formatAngka" x-model="rawValue" x-on:input="format()"
                        type="text" name="santridu"
                        value="<?= number_format($s['tunggakandu'], 0, ',', '.'); ?>"
                        class="w-full rounded-xl border-gray-300" />

                    <input type="hidden" name="id" value="<?= $s['id']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <hr class="border-gray-300 my-4">

    <!-- DETAIL TRANSAKSI -->
    <div>
        <h3 class="text-lg font-semibold mb-3">Detail Transaksi</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($detail as $d) : ?>

                <?php
                $inputs = [
                    'du'         => ['Bayar Daftar Ulang', $d['daftarulang']],
                    'tunggakan'  => ['Bayar Tunggakan', $d['tunggakan']],
                    'spp'        => ['Bayar SPP', $d['spp']],
                    'uangsaku'   => ['Bayar Uang Saku', $d['uangsaku']],
                    'infaq'      => ['Infaq', $d['infaq']],
                    'formulir'   => ['Formulir', $d['formulir']],
                ];
                ?>

                <?php foreach ($inputs as $name => [$label, $value]) : ?>
                    <div>
                        <label class="text-sm font-medium"><?= $label; ?></label>
                        <input x-data="formatAngka" x-model="rawValue" x-on:input="format()"
                            type="text" name="<?= $name; ?>"
                            value="<?= number_format($value, 0, ',', '.'); ?>"
                            class="w-full rounded-xl border-gray-300" />
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>
        </div>
    </div>

    <!-- BUTTON -->
    <div class="pt-6 flex gap-3">
        <button type="submit"
            class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow flex items-center gap-2">
            <span class="material-symbols-outlined">save</span>
            Simpan
        </button>

        <a href="<?= base_url('riwayat-pembayaran'); ?>"
            class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl shadow flex items-center gap-2">
            <span class="material-symbols-outlined">arrow_back</span>
            Kembali
        </a>
    </div>

</form>

    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {

    Alpine.data('formatAngka', () => ({
        rawValue: '',
        init() {
            let angka = this.$el.value.replace(/\D/g, '');
            this.rawValue = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
        },
        format() {
            let angka = this.rawValue.replace(/\D/g, '');
            this.rawValue = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
        },
        get numeric() {
            return this.rawValue.replace(/\D/g, '') || 0;
        }
    }));

    Alpine.data('formHandler', () => ({
        submitForm() {
            document.querySelectorAll('[x-data="formatAngka"]').forEach(el => {
                el.value = Alpine.$data(el).numeric;
            });
            this.$el.submit();
        }
    }));

});
</script>
<?= $this->endSection(); ?>