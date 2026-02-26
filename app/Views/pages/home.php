<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div x-data="psbCompare()" class="bg-white shadow rounded-xl p-4 space-y-4">
  <h3 class="text-lg font-semibold flex items-center gap-2">
    <span class="material-symbols-outlined text-sky-500">list_alt</span>
     Pemasukan PSB
  </h3>

<!-- FILTER -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- Filter 1 -->
    <div class="border rounded-lg p-3">
        <div class="font-medium mb-2">Filter 1</div>
        <div class="flex gap-2">
            <input type="month" x-model="f1_start" class="border rounded px-2 py-1 w-full">
            <input type="month" x-model="f1_end" class="border rounded px-2 py-1 w-full">
        </div>
    </div>

    <!-- Filter 2 -->
    <div class="border rounded-lg p-3">
        <div class="font-medium mb-2">Filter 2</div>
        <div class="flex gap-2">
            <input type="month" x-model="f2_start" class="border rounded px-2 py-1 w-full">
            <input type="month" x-model="f2_end" class="border rounded px-2 py-1 w-full">
        </div>
    </div>

</div>

<button @click="loadData()" class="bg-blue-500 text-white px-4 py-2 rounded">
    Hasil
</button>

<!-- HASIL -->

<template x-if="loaded">
    <div class="space-y-6">
    <!-- ================= TOTAL DAFTAR ULANG ================= -->
    <div>
        <div class="flex justify-between text-sm font-semibold mb-1">
            <span>Total Daftar Ulang</span>
        </div>

        <!-- Persentase -->
        <div class="flex justify-between text-xs mb-1">
            <span x-text="percentTotalF1 + '%'"></span>
            <span x-text="percentTotalF2 + '%'"></span>
        </div>

        <!-- Bar 2 warna -->
        <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden flex">
            <div class="bg-green-500 transition-all duration-700"
                 :style="`width:${percentTotalF1}%`"></div>
            <div class="bg-blue-500 transition-all duration-700"
                 :style="`width:${percentTotalF2}%`"></div>
        </div>

        <div class="text-xs mt-1 text-gray-600 flex justify-between">
            <span><span x-text="formatRupiah(data.filter1.total)"></span></span>
            <span><span x-text="formatRupiah(data.filter2.total)"></span></span>
        </div>
    </div>


    <!-- ================= JUMLAH TRANSAKSI ================= -->
    <div>
        <div class="flex justify-between text-sm font-semibold mb-1">
            <span>Jumlah Transaksi</span>
        </div>

        <!-- Persentase -->
        <div class="flex justify-between text-xs mb-1">
            <span x-text="percentCountF1 + '%'"></span>
            <span x-text="percentCountF2 + '%'"></span>
        </div>

        <!-- Bar 2 warna -->
        <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden flex">
            <div class="bg-yellow-500 transition-all duration-700"
                 :style="`width:${percentCountF1}%`"></div>
            <div class="bg-red-500 transition-all duration-700"
                 :style="`width:${percentCountF2}%`"></div>
        </div>

        <div class="text-xs mt-1 text-gray-600 flex justify-between">
            <span><span x-text="data.filter1.jumlah"></span></span>
            <span><span x-text="data.filter2.jumlah"></span></span>
        </div>
    </div>

</div>

</template>


</div>

<div x-data="sppCompare()" class="bg-white shadow rounded-xl p-4 space-y-4 mt-3">
<h3 class="font-semibold text-lg">Perbandingan SPP</h3>

<!-- FILTER -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="border rounded p-3">
        <div class="text-sm font-semibold mb-1">Filter 1</div>
        <div class="flex gap-2">
            <input type="month" x-model="f1_start" class="border rounded px-2 py-1 w-full">
            <input type="month" x-model="f1_end" class="border rounded px-2 py-1 w-full">
        </div>
    </div>

    <div class="border rounded p-3">
        <div class="text-sm font-semibold mb-1">Filter 2</div>
        <div class="flex gap-2">
            <input type="month" x-model="f2_start" class="border rounded px-2 py-1 w-full">
            <input type="month" x-model="f2_end" class="border rounded px-2 py-1 w-full">
        </div>
    </div>
</div>

<button @click="loadData()" class="bg-blue-500 text-white px-4 py-2 rounded">
    Hasil
</button>

<!-- HASIL -->
<template x-if="loaded">
    <div class="space-y-5">

        <!-- Function bar -->
<template x-for="item in bars" :key="item.label">
    <div>
        <div class="flex justify-between text-sm font-semibold">
            <span x-text="item.label"></span>
        </div>

    <div class="flex justify-between text-xs mb-1">
        <span x-text="item.p1 + '%'"></span>
        <span x-text="item.p2 + '%'"></span>
    </div>

    <!-- BAR WARNA-WARNI -->
    <div class="w-full bg-gray-200 h-7 rounded-full flex overflow-hidden shadow-inner">
        <div :class="item.c1 + ' transition-all duration-700'"
             :style="`width:${item.p1}%`"></div>
        <div :class="item.c2 + ' transition-all duration-700'"
             :style="`width:${item.p2}%`"></div>
    </div>

    <div class="text-xs text-gray-600 flex justify-between mt-1">
        <span x-text="format(item.v1)"></span>
        <span x-text="format(item.v2)"></span>
    </div>
</div>

</template>

    </div>
</template>

</div>

    <div class="mt-3 space-y-4">

        <?php foreach ($psb as $p): 
            $persen = ($p['kewajiban'] > 0)
                ? min(100, round(($p['pembayaran'] / $p['kewajiban']) * 100))
                : 0;

            $warna = match (strtolower($p['status'])) {
                'diterima' => 'bg-green-500',
                default => 'bg-gray-400'
            };
        ?>

        <div class="bg-white shadow rounded-xl p-4"
             x-data="{ percent: 0 }"
             x-init="setTimeout(() => percent = <?= $persen ?>, 100)">

            <!-- Header -->
            <div class="flex justify-between items-center mb-2">
                <div class="font-semibold capitalize">
                  <h3 class="text-lg font-semibold flex items-center gap-2">
                      <span class="material-symbols-outlined text-sky-500">list_alt</span>
                      Pemasukan PSB TA 2025/2026
                  </h3>
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
                <div><strong>Jumlah:</strong> <?= $p['jumlah']; ?> Santri</div>
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
$duMandiri  = array_sum(array_column($detailtung, 'tungdu'));
$duBeasiswa = array_sum(array_column($detailbea, 'tungdu'));
$tungTL     = array_sum(array_column($detailtung, 'tungtl')) + array_sum(array_column($detailbea, 'tungtl'));
$tungSPP    = array_sum(array_column($detailtung, 'tungspp'));

$totalTunggakan = $duMandiri + $duBeasiswa + $tungTL + $tungSPP;

function persenTung($nilai, $total) {
    return ($total > 0) ? round(($nilai / $total) * 100) : 0;
}
?>

<!-- ================= RINGKASAN TUNGGAKAN ================= -->
<h4 class="text-xl font-semibold mt-6 mb-3 flex items-center gap-2">
    <span class="material-symbols-outlined text-red-600">warning</span>
    Tunggakan
</h4>

<div class="space-y-4">

    <?php
    $tunggakanList = [
        ['label' => 'DU Mandiri',   'nilai' => $duMandiri,  'warna' => '#3B82F6'],
        ['label' => 'DU Beasiswa',  'nilai' => $duBeasiswa, 'warna' => '#6366F1'],
        ['label' => 'SPP',          'nilai' => $tungSPP,    'warna' => '#EF4444'],
    ];
    
    $total = array_sum(array_column($tunggakanList, 'nilai'));
    ?>

    <div class="bg-white shadow rounded-xl p-4"
         x-data="pieTunggakan(<?= htmlspecialchars(json_encode($tunggakanList)) ?>, <?= $total ?>)">
    
        <h3 class="font-semibold mb-4">Komposisi Tunggakan</h3>
    
        <!-- Pie -->
        <div class="flex justify-center mb-4">
            <div class="w-40 h-40 rounded-full"
                 :style="pieStyle">
            </div>
        </div>
    
        <!-- Legend -->
        <div class="space-y-2 text-sm">
            <?php foreach ($tunggakanList as $t): 
                $persen = $total ? round($t['nilai'] / $total * 100, 1) : 0;
            ?>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full" style="background: <?= $t['warna'] ?>"></span>
                    <span><?= $t['label']; ?></span>
                </div>
                <div class="text-right">
                    <div class="font-semibold"><?= format_rupiah($t['nilai']); ?></div>
                    <div class="text-xs text-gray-500"><?= $persen ?>%</div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    
    </div>

    <!-- TOTAL -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <strong>Total Tunggakan:</strong>
        <?= format_rupiah($totalTunggakan); ?>
    </div>
    
    <div>
      <div class="bg-white rounded-2xl shadow p-4">
        <h4 class="text-sm font-semibold mb-2">Informasi</h4>
        <p class="text-sm text-slate-600">Data didapat dari akumulasi hingga saat ini (realtime). Gunakan filter untuk membatasi jumlah informasi yang akan ditampilkan</p>
      </div>
    </div>
  </div>

</div>

<script>
function pieTunggakan(data, total) {
    return {
        pieStyle: '',
        init() {
            let current = 0;
            let parts = [];

            data.forEach(item => {
                let percent = total ? (item.nilai / total) * 100 : 0;
                let next = current + percent;

                parts.push(`${item.warna} ${current}% ${next}%`);
                current = next;
            });

            this.pieStyle = `background: conic-gradient(${parts.join(',')});`;
        }
    }
}

function psbCompare() {
    return {
        f1_start: '',
        f1_end: '',
        f2_start: '',
        f2_end: '',
        data: {},
        percentTotal: 0,
        percentCount: 0,
        loaded: false,
        percentTotalF1: 0,
        percentTotalF2: 0,
        percentCountF1: 0,
        percentCountF2: 0,

        loadData() {
            fetch(`<?= base_url('/psb-compare') ?>?f1_start=${this.f1_start}&f1_end=${this.f1_end}&f2_start=${this.f2_start}&f2_end=${this.f2_end}`)
            .then(res => res.json())
            .then(res => {
                this.data = res;

                let total1 = res.filter1.total;
                let total2 = res.filter2.total;

                let count1 = res.filter1.jumlah;
                let count2 = res.filter2.jumlah;

                this.percentTotalF1 = (total1 + total2) > 0
    ? Math.round((total1 / (total1 + total2)) * 100)
    : 0;

this.percentTotalF2 = 100 - this.percentTotalF1;

this.percentCountF1 = (count1 + count2) > 0
    ? Math.round((count1 / (count1 + count2)) * 100)
    : 0;

this.percentCountF2 = 100 - this.percentCountF1;

                this.loaded = true;
            });
        },

        formatRupiah(val) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(val || 0);
        }
    }
}

function sppCompare(){
    return {
        f1_start:'',
        f1_end:'',
        f2_start:'',
        f2_end:'',
        loaded:false,
        bars:[],

        loadData(){
            fetch(`<?= base_url('/spp-compare') ?>?f1_start=${this.f1_start}&f1_end=${this.f1_end}&f2_start=${this.f2_start}&f2_end=${this.f2_end}`)
            .then(r=>r.json())
            .then(res=>{

                this.bars = [
                    this.makeBar('Tunggakan SPP', res.f1.tunggakan, res.f2.tunggakan, 'bg-red-500', 'bg-pink-400'),
                    this.makeBar('Pembayaran SPP', res.f1.spp, res.f2.spp, 'bg-green-500', 'bg-emerald-300'),
                    this.makeBar('Inden SPP', res.f1.inden, res.f2.inden, 'bg-yellow-400', 'bg-orange-400'),
                    this.makeBar('Total Nilai', res.f1.total, res.f2.total, 'bg-blue-500', 'bg-purple-400'),
                    this.makeBar('Jumlah Transaksi', res.f1.jumlah, res.f2.jumlah, 'bg-gray-700', 'bg-gray-400')
                ];

                this.loaded = true;
            });
        },

        makeBar(label, v1, v2, c1, c2){
            let total = v1 + v2;
            let p1 = total > 0 ? Math.round((v1/total)*100) : 0;
            let p2 = 100 - p1;

            return { label, v1, v2, p1, p2, c1, c2 };
        },

        format(val){
            if (Number.isInteger(val) && val < 1000) return val;
            return new Intl.NumberFormat('id-ID',{
                style:'currency',
                currency:'IDR',
                minimumFractionDigits:0
            }).format(val || 0);
        }
    }
}

</script>

<?= $this->endSection(); ?>
