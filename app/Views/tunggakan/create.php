<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="max-w-lg mx-auto bg-white p-4 rounded-xl shadow">
    <h2 class="font-bold text-lg mb-4">Tambah Tunggakan</h2>

    <form method="post" action="<?= site_url('tunggakan/store') ?>">
        <?= csrf_field() ?>

        <input type="hidden" name="nisn" value="<?= esc($nisn) ?>">
        <input type="hidden" name="tanggal" value="<?= esc($tanggal) ?>">

        <div class="mb-3">
            <label class="text-sm">Nominal</label>
            <input type="number" name="nominal" required
                class="w-full border rounded px-2 py-1">
        </div>

        <div class="mb-3">
            <label class="text-sm">Keterangan</label>
            <textarea name="keterangan"
                class="w-full border rounded px-2 py-1"></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= site_url('laporan/spp') ?>"
               class="px-3 py-1 border rounded">
               Batal
            </a>
            <button
                class="px-3 py-1 bg-blue-600 text-white rounded">
                Simpan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>