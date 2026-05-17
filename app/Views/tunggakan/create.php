<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-4">

    <h2 class="font-bold text-lg mb-4">SPP Santri</h2>

    <!-- INFO SANTRI -->
    <div class="mb-4 text-sm bg-gray-50 p-3 rounded">
        <p><b>Nama</b> : <?= esc($nama) ?></p>
        <p><b>SPP</b> : <?= esc($spp) ?></p>
    </div>

    <!-- LIST TUNGGAKAN -->
    <?php if (!empty($listTunggakan)): ?>
        <div class="mb-5">
            <h3 class="font-semibold text-sm mb-2">Daftar Tunggakan</h3>

            <table class="w-full text-sm border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Bulan</th>
                        <th class="border px-2 py-1">Nominal</th>
                        <th class="border px-2 py-1">Keterangan</th>
                        <th class="border px-2 py-1 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listTunggakan as $t): ?>
                    <tr>
                        <td class="border px-2 py-1">
                            <?= date('F Y', strtotime($t['tanggal'])) ?>
                        </td>
                        <td class="border px-2 py-1 text-right">
                            <?= number_format($t['nominal'],0,',','.') ?>
                        </td>
                        <td class="border px-2 py-1">
                            <?= esc($t['keterangan']) ?>
                        </td>
                        <td class="border px-2 py-1 text-center">
                            <a href="<?= site_url('tunggakan/edit/'.$t['id']) ?>"
                               class="text-yellow-600 text-xs hover:underline">
                               ✏ Edit
                            </a>
                            <a href="<?= site_url('tunggakan/delete/'.$t['id']) ?>"
                               onclick="return confirm('Hapus tunggakan ini?')"
                               class="text-red-600 text-xs hover:underline ml-2">
                               🗑 Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- FORM MULTIPLE CREATE -->
    <form method="post" action="<?= site_url('tunggakan/store') ?>" x-data="{ rows: [1] }">
        <?= csrf_field() ?>

        <input type="hidden" name="nisn" value="<?= esc($nisn) ?>">

        <h3 class="font-semibold text-sm mb-2">Tambah Tunggakan</h3>

        <template x-for="(r, i) in rows" :key="i">
            <div class="grid grid-cols-4 gap-2 mb-2">

                <!-- BULAN -->
                <input
                    type="month"
                    name="bulan[]"
                    class="border rounded px-2 py-1"
                    required>

                <!-- NOMINAL -->
                <input
                    type="number"
                    name="nominal[]"
                    placeholder="Nominal"
                    class="border rounded px-2 py-1"
                    step="any"
                    required>

                <!-- KETERANGAN -->
                <input
                    type="text"
                    name="keterangan[]"
                    placeholder="Keterangan"
                    class="border rounded px-2 py-1 col-span-2">
            </div>
        </template>

        <button
            type="button"
            @click="rows.push(1)"
            class="text-sm text-blue-600 mb-3">
            ➕ Tambah Baris
        </button>

        <div class="flex justify-end gap-2">
            <a href="<?= site_url('laporan/spp') ?>"
               class="px-3 py-1 border rounded">
               Kembali
            </a>
            <button
                class="px-3 py-1 bg-blue-600 text-white rounded">
                Simpan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection(); ?>