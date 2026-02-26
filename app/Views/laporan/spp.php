<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div 
    x-data="tunggakanApp()" 
    class="bg-white rounded-xl shadow p-4 overflow-x-auto">

    <h2 class="text-lg font-bold mb-4">
        Laporan Pembayaran & Tunggakan SPP
    </h2>
    
<div class="flex flex-wrap gap-2 mb-4">
    <!-- Download -->
    <a href="<?= site_url('laporan/spp/download') ?>"
       class="px-3 py-1.5 bg-green-600 text-white rounded text-sm hover:bg-green-700">
        ⬇ Download
    </a>

    <!-- Laporan Alumni -->
    <a href="<?= site_url('laporan/spp-alumni') ?>"
       class="px-3 py-1.5 bg-gray-600 text-white rounded text-sm hover:bg-gray-700">
        📄 Laporan SPP Alumni
    </a>
</div>

<table class="min-w-full border border-gray-200 text-sm">
    <thead class="bg-gray-100 text-center">
        <tr>
            <th rowspan="2" class="border px-2 py-1">Nama</th>
            <th rowspan="2" class="border px-2 py-1">Kelas</th>

            <?php foreach ($bulan as $b): ?>
                <th colspan="2" class="border px-2 py-1"><?= $b ?></th>
            <?php endforeach; ?>

            <th rowspan="2" class="border px-2 py-1">Total Bayar</th>
            <th rowspan="2" class="border px-2 py-1">Total Tunggakan</th>
            <th rowspan="2" class="border px-2 py-1">Sisa Tunggakan</th>
        </tr>
        <tr>
            <?php foreach ($bulan as $b): ?>
                <th class="border px-2 py-1">Bayar</th>
                <th class="border px-2 py-1">Tunggakan</th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($santri as $s): ?>
        <tr class="hover:bg-gray-50">
            <td class="border px-2 py-1"><?= esc($s['nama']) ?></td>
            <td class="border px-2 py-1 text-center"><?= esc($s['kelas']) ?></td>

            <?php foreach ($s['bulan'] as $key => $b): ?>
                <td class="border px-2 py-1 text-right">
                    <?= number_format($b['bayar'],0,',','.') ?>
                </td>

                <td class="border px-2 py-1 text-center">
                    <?php if ($b['ada']): ?>
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs">
                            <?= number_format($b['tunggakan'],0,',','.') ?>
                        </span>
                    <?php else: ?>
                        <a href="<?= site_url('tunggakan/create?nisn='.$s['nisn'].'&bulan='.$key) ?>"
                           class="text-blue-600 hover:underline text-xs">
                            ➕ Tambah
                        </a>
                    <?php endif; ?>
                </td>
            <?php endforeach; ?>

            <!-- TOTAL -->
            <td class="border px-2 py-1 text-right font-semibold">
                <?= number_format($s['total_bayar'],0,',','.') ?>
            </td>

            <td class="border px-2 py-1 text-right font-semibold text-red-600">
                <?= number_format($s['total_tunggakan'],0,',','.') ?>
            </td>

            <td class="border px-2 py-1 text-right font-bold
                <?= $s['sisa_tunggakan'] > 0 ? 'text-red-700' : 'text-green-700' ?>">
                <?= number_format($s['sisa_tunggakan'],0,',','.') ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<?= $this->endSection(); ?>