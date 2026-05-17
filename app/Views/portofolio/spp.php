<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="bg-white rounded-xl shadow p-4">

    <!-- HEADER -->
    <div class="mb-4">
        <p><b>Nama</b> : <?= esc($santri['nama']) ?></p>
        <p><b>Jenjang / Kelas</b> :
            <?= esc($santri['jenjang']) ?> / <?= esc($santri['kelas']) ?>
        </p>
    </div>

    <a href="<?= site_url('portofolio/spp/'.$santri['nisn'].'/download') ?>"
       class="inline-block mb-3 px-3 py-1.5 bg-green-600 text-white rounded text-sm">
       ⬇ Download CSV
    </a>

    <!-- TABEL -->
    <div class="overflow-x-auto">
    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">No</th>
                <th class="border px-2 py-1">Tanggal</th>
                <th class="border px-2 py-1">Saldo Masuk</th>
                <th class="border px-2 py-1">Keterangan</th>
                <th class="border px-2 py-1">Daftar Ulang</th>
                <th class="border px-2 py-1">Tunggakan SPP</th>
                <th class="border px-2 py-1">SPP</th>
                <th class="border px-2 py-1">Inden SPP</th>
                <th class="border px-2 py-1">Uang Saku</th>
                <th class="border px-2 py-1">Formulir</th>
                <th class="border px-2 py-1">Infaq</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($rows as $r): ?>
            <tr>
                <td class="border px-2 py-1 text-center"><?= $no++ ?></td>
                <td class="border px-2 py-1"><?= $r['tanggal'] ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['saldomasuk'],0,',','.') ?></td>
                <td class="border px-2 py-1"><?= esc($r['keterangan']) ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['daftarulang'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['tunggakan_spp'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['spp'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['inden_spp'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['uangsaku'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['formulir'],0,',','.') ?></td>
                <td class="border px-2 py-1 text-right"><?= number_format($r['infaq'],0,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    
    <div class="mt-6">
    <h3 class="font-semibold mb-2">Daftar Tunggakan SPP</h3>

    <table class="w-full text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">Tanggal Tunggakan</th>
                <th class="border px-2 py-1">Nominal Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tunggakanRows)): ?>
                <?php foreach ($tunggakanRows as $t): ?>
                <tr>
                    <td class="border px-2 py-1">
                        <?= $t['tanggal'] ?>
                    </td>
                    <td class="border px-2 py-1 text-right">
                        <?= number_format($t['nominal'],0,',','.') ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" class="border px-2 py-1 text-center text-gray-500">
                        Tidak ada data tunggakan
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <!-- TOTAL -->
    <div class="mt-4 text-sm space-y-1">
        <p><b>Total Saldo Masuk</b> : <?= number_format($total['saldomasuk'],0,',','.') ?></p>
        <p><b>Total Daftar Ulang</b> : <?= number_format($total['daftarulang'],0,',','.') ?></p>
        <p><b>Total Tunggakan SPP</b> : <?= number_format($total['tunggakan_spp'],0,',','.') ?></p>
        <p><b>Total SPP</b> : <?= number_format($total['spp'],0,',','.') ?></p>
        <p><b>Total Inden SPP</b> : <?= number_format($total['inden_spp'],0,',','.') ?></p>
        <p><b>Total Uang Saku</b> : <?= number_format($total['uangsaku'],0,',','.') ?></p>
        <p><b>Total Formulir</b> : <?= number_format($total['formulir'],0,',','.') ?></p>
        <p><b>Total Infaq</b> : <?= number_format($total['infaq'],0,',','.') ?></p>

        <p class="font-bold text-red-700">
            Sisa Tunggakan :
            <?= number_format($sisaTunggakan,0,',','.') ?>
        </p>
    </div>

</div>

<?= $this->endSection(); ?>