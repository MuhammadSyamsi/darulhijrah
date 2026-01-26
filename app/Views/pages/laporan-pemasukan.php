<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container mx-auto px-4 py-6">

  <!-- Judul -->
  <h5 class="text-lg font-semibold mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">assessment</span>
    Laporan Pemasukan Bulanan
  </h5>

  <!-- Filter Bulan & Tahun -->
  <form method="get" class="flex flex-wrap items-center gap-3 mb-6">
    <div>
      <label class="sr-only" for="bulan">Bulan</label>
      <select id="bulan" name="bulan" class="form-select block w-44 rounded-full border px-3 py-2 shadow-sm">
        <?php for ($i = 1; $i <= 12; $i++): ?>
          <option value="<?= $i; ?>" <?= $i == $bulan ? 'selected' : ''; ?>>
            <?= date('F', mktime(0, 0, 0, $i, 10)); ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div>
      <label class="sr-only" for="tahun">Tahun</label>
      <select id="tahun" name="tahun" class="form-select block w-28 rounded-full border px-3 py-2 shadow-sm">
        <?php for ($y = date('Y') - 5; $y <= date('Y') + 1; $y++): ?>
          <option value="<?= $y; ?>" <?= $y == $tahun ? 'selected' : ''; ?>>
            <?= $y; ?>
          </option>
        <?php endfor; ?>
      </select>
    </div>

    <div>
      <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-full shadow-sm hover:bg-blue-700">
        <span class="material-symbols-outlined">filter_list</span>
        Tampilkan
      </button>
    </div>
  </form>

  <!-- Accordion container -->
  <div class="space-y-4">

    <!-- Card 1: Rekap Pemasukan Bulanan -->
    <div x-data="{ open: true }" class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined">account_balance</span>
          <div class="text-sm font-semibold">
            Pemasukan per Rekening & Program
            <div class="text-xs font-normal opacity-90"><?= date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)); ?></div>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <a href="<?= base_url('laporan/downloadBulanan?bulan='.$bulan.'&tahun='.$tahun); ?>" 
             class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm hover:opacity-90">
            <span class="material-symbols-outlined">download</span>
            Download
          </a>
          <span class="material-symbols-outlined" x-text="open ? 'expand_less' : 'expand_more'"></span>
        </div>
      </button>

      <div x-show="open" x-collapse class="p-4">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr class="text-left">
                <th class="px-3 py-2">Rekening</th>
                <th class="px-3 py-2">Program</th>
                <th class="px-3 py-2 text-right">Daftar Ulang</th>
                <th class="px-3 py-2 text-right">Tunggakan</th>
                <th class="px-3 py-2 text-right">SPP</th>
                <th class="px-3 py-2 text-right">Uang Saku</th>
                <th class="px-3 py-2 text-right">Infaq</th>
                <th class="px-3 py-2 text-right">Formulir</th>
                <th class="px-3 py-2 text-right">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($detailtrans)): ?>
                <tr>
                  <td colspan="9" class="px-3 py-6 text-center text-gray-500">
                    <span class="material-symbols-outlined align-middle">sentiment_neutral</span>
                    <span class="align-middle">Belum ada pemasukan pada bulan ini.</span>
                  </td>
                </tr>
              <?php else: ?>
                <?php
                $grand = ["daftarulang"=>0,"tunggakan"=>0,"spp"=>0,"saku"=>0,"infaq"=>0,"formulir"=>0,"total"=>0];
                foreach ($detailtrans as $row):
                  $total = $row['daftarulang'] + $row['tunggakan'] + $row['spp'] + $row['saku'] + $row['infaq'] + $row['formulir'];
                  foreach ($grand as $k => $v) {
                    if ($k !== "total") $grand[$k] += $row[$k];
                  }
                  $grand["total"] += $total;
                ?>
                  <tr class="border-b">
                    <td class="px-3 py-2"><?= esc($row['rekening']); ?></td>
                    <td class="px-3 py-2"><?= esc($row['program']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['daftarulang']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['tunggakan']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['spp']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['saku']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['infaq']); ?></td>
                    <td class="px-3 py-2 text-right"><?= number_format($row['formulir']); ?></td>
                    <td class="px-3 py-2 text-right font-semibold"><?= number_format($total); ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-100 font-semibold">
                  <td class="px-3 py-2 text-center" colspan="2">TOTAL</td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['daftarulang']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['tunggakan']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['spp']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['saku']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['infaq']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['formulir']); ?></td>
                  <td class="px-3 py-2 text-right"><?= number_format($grand['total']); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card 2: Rekap Harian -->
    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined">calendar_month</span>
          <div class="text-sm font-semibold">
            Rekap Harian
            <div class="text-xs font-normal opacity-90"><?= date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)); ?></div>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <form method="get" class="flex items-center gap-2" onsubmit="">
            <input type="hidden" name="bulan" value="<?= esc($bulan); ?>">
            <input type="hidden" name="tahun" value="<?= esc($tahun); ?>">
            <select name="rekening" class="form-select rounded-full text-sm px-3 py-1" onchange="this.form.submit()">
              <option value="">Semua Rekening</option>
              <?php foreach ($listRekening as $rek): ?>
                <option value="<?= esc($rek); ?>" <?= ($rek == ($rekening ?? '')) ? 'selected' : ''; ?>>
                  <?= esc($rek); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>

          <a href="<?= base_url('laporan/downloadHarian?bulan='.$bulan.'&tahun='.$tahun.($rekening ? '&rekening='.$rekening : '')); ?>" 
             class="inline-flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full">
            <span class="material-symbols-outlined">download</span>
            Download
          </a>

          <span class="material-symbols-outlined" x-text="open ? 'expand_less' : 'expand_more'"></span>
        </div>
      </button>

      <div x-show="open" x-collapse class="p-4">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2">Tanggal</th>
                <th class="px-3 py-2">Rekening</th>
                <th class="px-3 py-2 text-right">Total Masuk</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($rekapharian ?? [])): ?>
                <tr>
                  <td colspan="3" class="px-3 py-6 text-center text-gray-500">
                    <span class="material-symbols-outlined align-middle">mood_bad</span>
                    <span class="align-middle">Belum ada data harian pada bulan ini.</span>
                  </td>
                </tr>
              <?php else: ?>
                <?php 
                $totalHarian = 0;
                foreach ($rekapharian as $row): 
                  $totalHarian += $row['total'];
                ?>
                  <tr class="border-b">
                    <td class="px-3 py-2"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                    <td class="px-3 py-2"><?= esc($row['rekening']); ?></td>
                    <td class="px-3 py-2 text-right font-medium"><?= number_format($row['total']); ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-100 font-semibold">
                  <td colspan="2" class="px-3 py-2 text-center">TOTAL</td>
                  <td class="px-3 py-2 text-right"><?= number_format($totalHarian); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card 3: Detail Transaksi -->
    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-sm overflow-hidden">
      <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 bg-gradient-to-r from-sky-600 to-sky-500 text-white">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined">description</span>
          <div class="text-sm font-semibold">
            Detail Transaksi
            <div class="text-xs font-normal opacity-90"><?= date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)); ?></div>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <form method="get" class="flex items-center gap-2">
            <input type="hidden" name="bulan" value="<?= esc($bulan); ?>">
            <input type="hidden" name="tahun" value="<?= esc($tahun); ?>">
            <select name="rekening" class="form-select rounded-full text-sm px-3 py-1" onchange="this.form.submit()">
              <option value="">Semua Rekening</option>
              <?php foreach ($listRekening as $rek): ?>
                <option value="<?= esc($rek); ?>" <?= ($rek == ($rekening ?? '')) ? 'selected' : ''; ?>>
                  <?= esc($rek); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </form>

          <a href="<?= base_url('laporan/downloadDetail?bulan='.$bulan.'&tahun='.$tahun.($rekening ? '&rekening='.$rekening : '')); ?>" 
             class="inline-flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full">
            <span class="material-symbols-outlined">download</span>
            Download
          </a>

          <span class="material-symbols-outlined" x-text="open ? 'expand_less' : 'expand_more'"></span>
        </div>
      </button>

      <div x-show="open" x-collapse class="p-4">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2">Tanggal</th>
                <th class="px-3 py-2">Rekening</th>
                <th class="px-3 py-2 text-right">Total Masuk</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($detaildata ?? [])): ?>
                <tr>
                  <td colspan="3" class="px-3 py-6 text-center text-gray-500">
                    <span class="material-symbols-outlined align-middle">sentiment_dissatisfied</span>
                    <span class="align-middle">Tidak ada detail transaksi.</span>
                  </td>
                </tr>
              <?php else: ?>
                <?php 
                $totalDetail = 0;
                foreach ($detaildata as $row): 
                  $totalDetail += $row['total'];
                ?>
                  <tr class="border-b">
                    <td class="px-3 py-2"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                    <td class="px-3 py-2"><?= esc($row['rekening']); ?></td>
                    <td class="px-3 py-2 text-right font-medium"><?= number_format($row['total']); ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-100 font-semibold">
                  <td colspan="2" class="px-3 py-2 text-center">TOTAL</td>
                  <td class="px-3 py-2 text-right"><?= number_format($totalDetail); ?></td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div> <!-- end accordion container -->

</div>

<?= $this->endSection(); ?>