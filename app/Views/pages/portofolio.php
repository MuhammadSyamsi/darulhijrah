<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="max-w-5xl mx-auto px-4 py-6">
<div 
x-data="autocompleteSantri()"
class="max-w-md mb-6"
>

<label class="block text-sm font-medium mb-1">
Cari Santri
</label>

<div class="relative">

<input
type="text"
x-model="query"
@input.debounce.400ms="search"
@focus="open=true"
placeholder="Ketik nama santri..."
class="w-full border rounded-lg px-3 py-2"
/>

<!-- dropdown -->
<div
x-show="open && results.length"
@click.outside="open=false"
class="absolute w-full bg-white border rounded-lg shadow mt-1 z-50 max-h-60 overflow-auto"
>

<template x-for="item in results" :key="item.nisn">

<div
@click="select(item)"
class="px-3 py-2 hover:bg-green-50 cursor-pointer"
>

<span x-text="item.nama"></span>

<span class="text-xs text-gray-400 ml-2" x-text="item.nisn"></span>

</div>

</template>

</div>

</div>

</div>
<?php if (!$santri): ?>

<div class="bg-yellow-100 text-yellow-700 p-4 rounded">
Silakan cari santri terlebih dahulu.
</div>

<?php return; ?>

<?php endif; ?>
<div class="bg-white shadow rounded-lg overflow-hidden">

<div id="portofolio-area" class="p-6">

<!-- HEADER -->
<div class="flex items-center justify-between mb-6 p-4 rounded-md"
     style="background:linear-gradient(135deg,#2e7d32,#1b5e20);border-bottom:4px solid #fbc02d">

    <div class="text-white">
        <h4 class="text-xl font-semibold">Portofolio Pembayaran Santri</h4>
        <p class="text-sm opacity-90">
            Darul Hijrah Salam<br>
            Jl. Ketanireng, Prigen, Pasuruan
        </p>
    </div>

    <img src="<?= base_url('assets/images/logo.png') ?>" class="h-14">
</div>


<!-- IDENTITAS -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

<div>
<div class="flex">
<div class="w-36 font-semibold">Nama Santri</div>
<div><?= $santri['nama']; ?></div>
</div>

<div class="flex mt-2">
<div class="w-36 font-semibold">NISN</div>
<div><?= $santri['nisn']; ?></div>
</div>
</div>

<div>
<div class="flex">
<div class="w-36 font-semibold">Tanggal Cetak</div>
<div><?= tanggal_indo(date('Y-m-d')); ?></div>
</div>
</div>

</div>


<!-- RINGKASAN KEWAJIBAN -->
<h5 class="font-semibold mt-6 mb-2">Posisi Kewajiban</h5>

<div class="overflow-x-auto">
<table class="min-w-full border text-sm">

<thead class="bg-gray-100">
<tr>
<th class="px-4 py-2 text-left">Jenis</th>
<th class="px-4 py-2 text-right">Kewajiban</th>
<th class="px-4 py-2 text-right">Sudah Bayar</th>
<th class="px-4 py-2 text-right">Sisa</th>
</tr>
</thead>

<tbody>

<tr class="border-t">
<td class="px-4 py-2">SPP</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['spp']); ?></td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['inden_spp']); ?></td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['tunggakan_spp']); ?></td>
</tr>

<tr class="border-t">
<td class="px-4 py-2">Daftar Ulang</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['daftarulang']); ?></td>
<td class="px-4 py-2 text-right">-</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['daftarulang']); ?></td>
</tr>

<tr class="border-t">
<td class="px-4 py-2">Uang Saku</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['uangsaku']); ?></td>
<td class="px-4 py-2 text-right">-</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['uangsaku']); ?></td>
</tr>

<tr class="border-t">
<td class="px-4 py-2">Infaq</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['infaq']); ?></td>
<td class="px-4 py-2 text-right">-</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['infaq']); ?></td>
</tr>

<tr class="border-t">
<td class="px-4 py-2">Formulir</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['formulir']); ?></td>
<td class="px-4 py-2 text-right">-</td>
<td class="px-4 py-2 text-right"><?= format_rupiah($detail['formulir']); ?></td>
</tr>

</tbody>
</table>
</div>


<!-- RIWAYAT PEMBAYARAN -->
<h5 class="font-semibold mt-8 mb-2">Riwayat Pembayaran</h5>

<div class="overflow-x-auto">
<table class="min-w-full border text-sm">

<thead class="bg-gray-100">
<tr>
<th class="px-4 py-2 text-left">Tanggal</th>
<th class="px-4 py-2 text-left">Nama</th>
<th class="px-4 py-2 text-right">Nominal</th>
<th class="px-4 py-2 text-right">Tunggakan</th>
</tr>
</thead>

<tbody>

<?php foreach($transfer as $t) : ?>

<tr class="border-t">

<td class="px-4 py-2">
<?= tanggal_indo($t['tanggal']); ?>
</td>

<td class="px-4 py-2">
<?= $t['nama']; ?>
</td>

<td class="px-4 py-2 text-right">
<?= format_rupiah($t['saldomasuk']); ?>
</td>

<td class="px-4 py-2 text-right">
<?= format_rupiah($t['nominal']); ?>
</td>

</tr>

<?php endforeach ?>

</tbody>
</table>
</div>


<!-- FOOTER -->
<div class="mt-8 text-right text-sm">
Mengetahui,<br><br><br>
<strong>Keuangan Darul Hijrah Salam</strong>
</div>


</div>
</div>


<!-- BUTTON DOWNLOAD -->
<div class="flex justify-end mt-6">

<button
id="btnDownload"
class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow"
>

Download PNG

</button>

</div>

</div>



<!-- SCRIPT DOWNLOAD PNG -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>

document.getElementById('btnDownload').addEventListener('click', function(){

const area = document.getElementById('portofolio-area');

html2canvas(area).then(canvas => {

const link = document.createElement('a');

link.download = "portofolio-<?= $santri['nisn'] ?>.png";

link.href = canvas.toDataURL("image/png");

link.click();

});

});

function autocompleteSantri(){

return{

query:'',
results:[],
open:false,

search(){

if(this.query.length < 2){
this.results=[]
return
}

fetch(`<?= site_url('api/portsantri') ?>?q=`+this.query)

.then(res=>res.json())

.then(data=>{
this.results=data
this.open=true
})

},

select(item){

window.location.href = `<?= site_url('portofolio') ?>/`+item.nisn

}

}

}

</script>

<?= $this->endSection(); ?>