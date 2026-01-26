<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="px-4 py-4">

    <!-- Judul -->
    <h4 class="text-xl font-semibold mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-blue-600">list_alt</span>
        Penerimaan Santri Baru
    </h4>

    <div class="space-y-4">

        <?php foreach ($psb as $p): 
            $persen = ($p['kewajiban'] > 0)
                ? min(100, round(($p['pembayaran'] / $p['kewajiban']) * 100))
                : 0;

            $warna = match (strtolower($p['status'])) {
                'diterima' => 'bg-green-500',
                'baru' => 'bg-yellow-500',
                'mengundurkan diri' => 'bg-red-500',
                default => 'bg-gray-400'
            };
        ?>

        <div class="bg-white shadow rounded-xl p-4"
             x-data="{ percent: 0 }"
             x-init="setTimeout(() => percent = <?= $persen ?>, 100)">

            <!-- Header -->
            <div class="flex justify-between items-center mb-2">
                <div class="font-semibold capitalize">
                    <?= esc($p['status']); ?>
                </div>
                <span class="text-sm text-gray-600"
                      x-text="percent + '%'"></span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                <div class="h-3 rounded-full transition-all duration-700 ease-out <?= $warna ?>"
                     :style="`width: ${percent}%`">
                </div>
            </div>

            <!-- Detail -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm text-gray-700 mt-3">
                <div><strong>Jumlah:</strong> <?= $p['jumlah']; ?></div>
                <div><strong>Kewajiban:</strong> <?= format_rupiah($p['kewajiban']); ?></div>
                <div><strong>Bayar:</strong> <?= format_rupiah($p['pembayaran']); ?></div>
                <div><strong>Tunggakan:</strong> <?= format_rupiah($p['totaltunggakan']); ?></div>
            </div>

        </div>

        <?php endforeach; ?>
    </div>

    <hr class="my-6">

<?php
// =====================
// HITUNGAN PHP
// =====================
$pemasukan    = $jumlah[0]['sum'] ?? 0;
$targetBulanan = 200000000; // 🔧 ganti sesuai kebutuhan

$persenMasuk = ($targetBulanan > 0)
    ? min(100, round(($pemasukan / $targetBulanan) * 100))
    : 0;

$duMandiri  = array_sum(array_column($detailtung, 'tungdu'));
$duBeasiswa = array_sum(array_column($detailbea, 'tungdu'));
$tungTL     = array_sum(array_column($detailtung, 'tungtl')) + array_sum(array_column($detailbea, 'tungtl'));
$tungSPP    = array_sum(array_column($detailtung, 'tungspp'));

$totalTunggakan = $duMandiri + $duBeasiswa + $tungTL + $tungSPP;

function persenTung($nilai, $total) {
    return ($total > 0) ? round(($nilai / $total) * 100) : 0;
}
?>

<hr class="my-6">

<!-- ================= PEMASUKAN ================= -->
<h4 class="text-xl font-semibold mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-green-600">payments</span>
    Pemasukan Bulan Ini
</h4>

<div class="bg-white shadow rounded-xl p-4"
     x-data="{ percent: 0 }"
     x-init="setTimeout(() => percent = <?= $persenMasuk ?>, 100)">

    <div class="flex justify-between mb-2">
        <span class="font-semibold">Realisasi</span>
        <span class="text-sm text-gray-600" x-text="percent + '%'"></span>
    </div>

    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
        <div class="h-3 bg-green-500 rounded-full transition-all duration-700"
             :style="`width: ${percent}%`"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm text-gray-700 mt-3">
        <div><strong>Pemasukan:</strong> <?= format_rupiah($pemasukan); ?></div>
        <div><strong>Target:</strong> <?= format_rupiah($targetBulanan); ?></div>
        <div><strong>Sisa:</strong> <?= format_rupiah(max(0, $targetBulanan - $pemasukan)); ?></div>
    </div>
</div>

<!-- ================= RINGKASAN TUNGGAKAN ================= -->
<h4 class="text-xl font-semibold mt-6 mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-red-600">warning</span>
    Ringkasan Tunggakan
</h4>

<div class="space-y-4">

    <?php
    $tunggakanList = [
        ['label' => 'DU Mandiri',   'nilai' => $duMandiri,  'warna' => 'bg-blue-500'],
        ['label' => 'DU Beasiswa',  'nilai' => $duBeasiswa, 'warna' => 'bg-indigo-500'],
        ['label' => 'Tahun Lalu',   'nilai' => $tungTL,     'warna' => 'bg-orange-500'],
        ['label' => 'SPP',          'nilai' => $tungSPP,    'warna' => 'bg-red-500'],
    ];
    ?>

    <?php foreach ($tunggakanList as $t): 
        $persen = persenTung($t['nilai'], $totalTunggakan);
    ?>

    <div class="bg-white shadow rounded-xl p-4"
         x-data="{ percent: 0 }"
         x-init="setTimeout(() => percent = <?= $persen ?>, 100)">

        <div class="flex justify-between mb-2">
            <span class="font-semibold"><?= $t['label']; ?></span>
            <span class="text-sm text-gray-600" x-text="percent + '%'"></span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full transition-all duration-700 <?= $t['warna']; ?>"
                 :style="`width: ${percent}%`"></div>
        </div>

        <div class="text-sm text-gray-700 mt-2">
            <strong>Nominal:</strong> <?= format_rupiah($t['nilai']); ?>
        </div>

    </div>

    <?php endforeach; ?>

    <!-- TOTAL -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <strong>Total Tunggakan:</strong>
        <?= format_rupiah($totalTunggakan); ?>
    </div>

</div>

</div>

<?= $this->endSection(); ?>
