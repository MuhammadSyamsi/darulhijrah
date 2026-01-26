<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_general');
}
?>

<?= $this->section('konten') ?>

<div class="w-full px-2 sm:px-4">
  <div class="flex justify-center">
    <div class="w-full">

      <div class="bg-white shadow rounded-xl p-5">

        <h5 class="text-xl font-semibold mb-4 flex items-center gap-2">
          <span class="material-symbols-outlined text-blue-600">check_circle</span>
          Data Seragam Santri
        </h5>

        <!-- info kecil -->
        <p class="text-gray-500 text-sm mb-3 flex items-center gap-1">
          <span class="material-symbols-outlined text-base">info</span>
          Angka pada label menunjukkan jumlah <strong>seragam yang belum diberikan</strong>
        </p>

        <!-- FILTER KELAS -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 mb-4">
          <?php foreach ($statistikKelas as $k): ?>
            <a href="<?= base_url('seragam?jenjang=' . $k['jenjang'] . '&kelas=' . $k['kelas']) ?>"
              class="px-3 py-2 text-center text-sm rounded-lg border 
                <?= $filter_kelas == $k['kelas'] ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' ?>">
              
              <?= $k['jenjang'] ?> <?= $k['kelas'] ?>

              <span class="ml-1 inline-block bg-red-600 text-white px-2 py-0.5 rounded-full text-xs">
                <?= $k['total_belum'] ?>
              </span>
            </a>
          <?php endforeach; ?>
        </div>

        <!-- Download -->
        <div class="flex justify-end mb-4">
          <a href="<?= base_url('seragam/downloadcsv') ?>"
             class="px-3 py-2 bg-green-600 text-white rounded-lg text-sm flex items-center gap-1 hover:bg-green-700">
            <span class="material-symbols-outlined text-base">download</span>
            Download Semua Data (CSV)
          </a>
        </div>

        <!-- Kondisi Filter -->
        <?php if (!$filter_jenjang || !$filter_kelas): ?>
          <div class="bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-lg text-center">
            <span class="material-symbols-outlined align-middle">filter_alt</span>
            Silakan pilih jenjang dan kelas terlebih dahulu.
          </div>

        <?php elseif (empty($santri)): ?>
          <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded-lg text-center">
            <span class="material-symbols-outlined align-middle">warning</span>
            Tidak ada santri ditemukan untuk filter yang dipilih.
          </div>

        <?php else: ?>

          <!-- TABLE -->
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm">
              <thead class="bg-gray-100 text-gray-700 text-center">
                <tr>
                  <th class="px-3 py-2 border">Nama</th>
                  <th class="px-3 py-2 border">Baju Putih</th>
                  <th class="px-3 py-2 border">Celana Abu</th>
                  <th class="px-3 py-2 border">Baju Batik</th>
                  <th class="px-3 py-2 border">Celana Putih</th>
                  <th class="px-3 py-2 border">Baju Coklat</th>
                  <th class="px-3 py-2 border">Celana Coklat</th>
                  <th class="px-3 py-2 border">Baju Pandu</th>
                  <th class="px-3 py-2 border">Celana Pandu</th>
                  <th class="px-3 py-2 border">Beladiri</th>
                  <?php if (in_groups('superadmin')) : ?>
                    <th class="px-3 py-2 border">Aksi</th>
                  <?php endif; ?>
                </tr>
              </thead>

              <tbody>
                <?php foreach ($santri as $s): ?>
                  <tr class="border-b">
                    <td class="px-3 py-2 border"><?= esc($s['nama']) ?></td>

                    <?php
                      $seragamMap = [];
                      foreach ($s['seragam'] as $item) {
                        $seragamMap[$item['jenis_seragam'].'-'.$item['kategori']] = $item;
                      }

                      $itemList = [
                        'baju-putih','celana-abu',
                        'baju-batik','celana-putih',
                        'baju-coklat','celana-coklat',
                        'baju-pandu','celana-pandu',
                        'baju-beladiri'
                      ];

                      foreach ($itemList as $key):
                        $data = $seragamMap[$key] ?? null;
                    ?>
                      <td class="text-center px-3 py-2 border">
                        <?php if ($data && $data['status'] == 'sudah_diberikan'): ?>
                          <span class="flex flex-col items-center text-green-600">
                            <span class="material-symbols-outlined">check_box</span>
                            <span class="text-xs text-gray-500"><?= $data['ukuran'] ?></span>
                          </span>
                        <?php else: ?>
                          <span class="material-symbols-outlined text-gray-400">check_box_outline_blank</span>
                        <?php endif; ?>
                      </td>
                    <?php endforeach; ?>

                    <?php if (in_groups('superadmin')) : ?>
                      <td class="text-center px-3 py-2 border">
                        <button 
                          type="button"
                          data-modal-target="modal-<?= $s['nisn'] ?>"
                          class="px-2 py-1 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 text-xs flex items-center gap-1">
                          <span class="material-symbols-outlined text-base">edit</span>
                        </button>
                      </td>
                    <?php endif; ?>
                  </tr>

                  <!-- MODAL -->
                  <div id="modal-<?= $s['nisn'] ?>"
                       class="hidden fixed inset-0 bg-black/50 flex justify-center items-center p-4 z-50">

                    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl">
                      <form method="post" action="<?= base_url('seragam/update') ?>">

                        <div class="flex justify-between items-center p-4 border-b">
                          <h5 class="font-semibold text-lg">
                            Edit Seragam - <?= esc($s['nama']) ?>
                          </h5>
                          <button type="button" class="material-symbols-outlined" onclick="document.getElementById('modal-<?= $s['nisn'] ?>').classList.add('hidden')">
                            close
                          </button>
                        </div>

                        <div class="p-4">
                          <input type="hidden" name="nisn" value="<?= $s['nisn'] ?>">

                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                              $kategoriList = ['putih','abu','batik','coklat','pandu','beladiri'];
                              $jenisList = ['baju','celana'];
                              $ukuranList = ['S','M','L','XL','XXL','XXXL'];

                              foreach ($kategoriList as $kategori):
                                foreach ($jenisList as $jenis):

                                  if (
                                    ($jenis=='baju' && in_array($kategori,['putih','batik','coklat','pandu','beladiri'])) ||
                                    ($jenis=='celana' && in_array($kategori,['abu','putih','coklat','pandu']))
                                  ):

                                    $dataSeragam = null;
                                    foreach ($s['seragam'] as $seragam) {
                                      if ($seragam['kategori']==$kategori && $seragam['jenis_seragam']==$jenis) {
                                        $dataSeragam = $seragam;
                                      }
                                    }
                            ?>
                              <div>
                                <label class="font-medium text-sm mb-1 block">
                                  <?= ucfirst($jenis).' '.ucfirst($kategori) ?>
                                </label>

                                <div class="flex gap-2">
                                  <select name="seragam[<?= $jenis ?>][<?= $kategori ?>][ukuran]"
                                    class="w-1/2 border rounded-lg p-2">
                                    <option value="">- Pilih Ukuran -</option>
                                    <?php foreach ($ukuranList as $uk): ?>
                                      <option value="<?= $uk ?>" <?= ($dataSeragam && $dataSeragam['ukuran']==$uk)?'selected':'' ?>>
                                        <?= $uk ?>
                                      </option>
                                    <?php endforeach; ?>
                                  </select>

                                  <select name="seragam[<?= $jenis ?>][<?= $kategori ?>][status]"
                                    class="w-1/2 border rounded-lg p-2">
                                    <option value="belum" <?= (!$dataSeragam || $dataSeragam['status']=='belum')?'selected':'' ?>>Belum</option>
                                    <option value="sudah_diberikan" <?= ($dataSeragam && $dataSeragam['status']=='sudah_diberikan')?'selected':'' ?>>Sudah</option>
                                  </select>
                                </div>
                              </div>
                            <?php endif; endforeach; endforeach; ?>
                          </div>
                        </div>

                        <div class="p-4 border-t flex justify-end">
                          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan Perubahan
                          </button>
                        </div>

                      </form>
                    </div>
                  </div>

                <?php endforeach; ?>
              </tbody>

            </table>
          </div>

        <?php endif; ?>

      </div>

    </div>
  </div>
</div>

<script>
document.querySelectorAll("[data-modal-target]").forEach(btn => {
    btn.addEventListener("click", function () {
        const id = this.getAttribute("data-modal-target");
        document.getElementById(id).classList.remove("hidden");
    });
});
</script>

<?= $this->endSection() ?>