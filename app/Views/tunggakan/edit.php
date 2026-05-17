<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-4">

    <h2 class="font-bold text-lg mb-4">Edit Tunggakan</h2>

    <form method="post" action="<?= site_url('tunggakan/update/'.$data['id']) ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="text-sm">Nominal</label>
            <input
                type="number"
                name="nominal"
                value="<?= esc($data['nominal']) ?>"
                class="w-full border rounded px-2 py-1"
                required>
        </div>

        <div class="mb-3">
            <label class="text-sm">Keterangan</label>
            <textarea
                name="keterangan"
                class="w-full border rounded px-2 py-1"><?= esc($data['keterangan']) ?></textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="<?= site_url('laporan/spp') ?>"
               class="px-3 py-1 border rounded">
               Batal
            </a>
            <button
                class="px-3 py-1 bg-blue-600 text-white rounded">
                Update
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>