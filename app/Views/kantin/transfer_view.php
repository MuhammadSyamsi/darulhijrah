<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container mx-auto mt-6 px-4">

  <!-- Card Utama -->
  <div class="bg-white shadow-md rounded-xl overflow-hidden">
    
    <!-- Tabs -->
    <div class="border-b">
      <nav class="flex space-x-4" aria-label="Tabs">
        <button id="tab-laundry" class="tab-button text-gray-700 px-4 py-2 font-medium border-b-2 border-blue-500 focus:outline-none" data-target="#laundry">Laundry</button>
        <button id="tab-nonlaundry" class="tab-button text-gray-500 px-4 py-2 font-medium border-b-2 border-transparent focus:outline-none" data-target="#nonlaundry">Non-Laundry</button>
        <button id="tab-santri" class="tab-button text-gray-500 px-4 py-2 font-medium border-b-2 border-transparent focus:outline-none" data-target="#santri">Santri</button>
      </nav>
    </div>

    <!-- Tab Contents -->
    <div class="p-4 space-y-4">

      <!-- Tab Laundry -->
      <div id="laundry" class="tab-content">
        <form method="get" class="flex gap-2 mb-4">
          <select name="bulan" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($m=1;$m<=12;$m++): ?>
              <option value="<?= $m ?>" <?= (isset($_GET['bulan']) && $_GET['bulan']==$m)?'selected':''; ?>>
                <?= date("F", mktime(0,0,0,$m,10)); ?>
              </option>
            <?php endfor; ?>
          </select>
          <select name="tahun" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($y=date('Y');$y>=2020;$y--): ?>
              <option value="<?= $y ?>" <?= (isset($_GET['tahun']) && $_GET['tahun']==$y)?'selected':''; ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
          <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 flex items-center gap-1">
            <span class="material-symbols-outlined">filter_list</span> Filter
          </button>
        </form>

        <p><span class="inline-block bg-green-500 text-white px-2 py-1 rounded-full text-sm">Total Laundry: <?= count($transferLaundry); ?></span></p>

        <div class="space-y-2 mt-2">
          <?php if (!empty($transferLaundry)): ?>
            <?php foreach ($transferLaundry as $row): ?>
              <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
                <strong><?= esc($row['nama']); ?></strong><br>
                <small class="text-gray-500"><?= esc($row['keterangan']); ?></small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-400">Tidak ada data laundry.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Tab Non-Laundry -->
      <div id="nonlaundry" class="tab-content hidden">
        <form method="get" class="flex gap-2 mb-4">
          <select name="bulan3" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($m=1;$m<=12;$m++): ?>
              <option value="<?= $m ?>" <?= (isset($_GET['bulan3']) && $_GET['bulan3']==$m)?'selected':''; ?>>
                <?= date("F", mktime(0,0,0,$m,10)); ?>
              </option>
            <?php endfor; ?>
          </select>
          <select name="tahun3" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($y=date('Y');$y>=2020;$y--): ?>
              <option value="<?= $y ?>" <?= (isset($_GET['tahun3']) && $_GET['tahun3']==$y)?'selected':''; ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
          <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 flex items-center gap-1">
            <span class="material-symbols-outlined">filter_list</span> Filter
          </button>
        </form>

        <p><span class="inline-block bg-red-500 text-white px-2 py-1 rounded-full text-sm">Total Non-Laundry: <?= count($transferNonLaundry); ?></span></p>

        <div class="space-y-2 mt-2">
          <?php if (!empty($transferNonLaundry)): ?>
            <?php foreach ($transferNonLaundry as $row): ?>
              <div class="bg-gray-50 p-3 rounded-lg shadow-sm">
                <strong><?= esc($row['nama'] . '/' . $row['kelas']); ?></strong><br>
                <small class="text-gray-500"><?= esc($row['keterangan']); ?></small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-400">Tidak ada data Non-Laundry.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Tab Santri -->
      <div id="santri" class="tab-content hidden">
        <form method="get" class="flex gap-2 mb-4">
          <select name="bulan2" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($m=1;$m<=12;$m++): ?>
              <option value="<?= $m ?>" <?= (isset($_GET['bulan2']) && $_GET['bulan2']==$m)?'selected':''; ?>>
                <?= date("F", mktime(0,0,0,$m,10)); ?>
              </option>
            <?php endfor; ?>
          </select>
          <select name="tahun2" class="form-select rounded border px-2 py-1 text-sm">
            <?php for ($y=date('Y');$y>=2020;$y--): ?>
              <option value="<?= $y ?>" <?= (isset($_GET['tahun2']) && $_GET['tahun2']==$y)?'selected':''; ?>><?= $y ?></option>
            <?php endfor; ?>
          </select>
          <button type="submit" class="bg-indigo-500 text-white px-3 py-1 rounded text-sm hover:bg-indigo-600 flex items-center gap-1">
            <span class="material-symbols-outlined">filter_list</span> Filter
          </button>
        </form>

        <p><span class="inline-block bg-indigo-500 text-white px-2 py-1 rounded-full text-sm">Total Santri: <?= count($transferSantri); ?></span></p>

        <div class="space-y-2 mt-2">
          <?php if (!empty($transferSantri)): ?>
            <?php foreach ($transferSantri as $row): ?>
              <div class="flex justify-between items-center bg-gray-50 p-3 rounded-lg shadow-sm">
                <div>
                  <strong><?= esc($row['nama']); ?></strong><br>
                  <small class="text-gray-500"><?= esc($row['keterangan']); ?></small>
                </div>
                <span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-sm">Rp <?= number_format($row['saldomasuk'],0,',','.'); ?></span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-gray-400">Tidak ada data santri.</p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

</div>

<!-- Tab Script -->
<script>
  const tabs = document.querySelectorAll('.tab-button');
  const contents = document.querySelectorAll('.tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.replace('text-blue-500', 'text-gray-500'));
      tabs.forEach(t => t.classList.replace('border-blue-500', 'border-transparent'));
      tab.classList.replace('text-gray-500','text-gray-700');
      tab.classList.replace('border-transparent','border-blue-500');

      contents.forEach(c => c.classList.add('hidden'));
      document.querySelector(tab.dataset.target).classList.remove('hidden');
    });
  });
</script>

<?= $this->endSection(); ?>