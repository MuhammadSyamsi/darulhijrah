<?php if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_sekolah');
}
?>

<?= $this->section('konten') ?>
<div class="w-full px-4 py-6">
  <div class="max-w-full mx-auto">
      <div class="bg-white shadow rounded-xl p-6 mb-6">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Data Guru -->
    <div class="bg-white shadow rounded-xl p-4">
      <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
        <i class="bi bi-journal-bookmark-fill"></i> Data Guru
      </h2>

      <?php if (!empty($guruList)): ?>
        <div class="space-y-3" id="accordionGuru">
          <?php foreach ($guruList as $i => $guru): ?>
            <div class="border rounded-lg overflow-hidden">
              <button class="w-full text-left px-4 py-3 bg-gray-100 hover:bg-gray-200 font-medium flex justify-between items-center" data-accordion-toggle="guru-<?= $i ?>">
                <span>Ustadz <?= esc($guru['nama']) ?></span>
                <span class="bi bi-chevron-down text-sm"></span>
              </button>

              <div id="guru-<?= $i ?>" class="hidden px-4 py-3 text-sm space-y-1">
                <p><strong>NIP:</strong> <?= esc($guru['nip']) ?></p>
                <p><strong>Jabatan:</strong> <?= esc($guru['jabatan']) ?></p>
                <p><strong>Wali Kelas:</strong> <?= esc($guru['kelas']) ?></p>
                <p><strong>Pendidikan:</strong> <?= esc($guru['pendidikan_akhir']) ?></p>

                <div class="flex gap-2 mt-2">
                  <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="px-3 py-1 bg-yellow-400 text-white rounded text-xs">Edit</a>

                  <form action="<?= site_url('guru/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs">Hapus</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-400">Belum ada data guru.</p>
      <?php endif ?>

      <div class="relative text-center my-4">
        <hr>
        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-gray-400 text-xs">Tambah Guru</span>
      </div>

      <form action="<?= base_url('guru/save') ?>" method="post" class="flex gap-2">
        <input type="text" name="nama" class="flex-1 border rounded-lg px-3 py-2 text-sm" placeholder="Nama Guru" required>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Tambah</button>
      </form>
    </div>


    <!-- Distribusi Jam Guru -->
    <div class="bg-white shadow rounded-xl p-4">
      <h2 class="text-lg font-semibold mb-3 flex items-center gap-2">
        <i class="bi bi-person-lines-fill"></i> Distribusi Jam Guru / Minggu
      </h2>

      <?php
      $grouped = [];
      foreach ($guruMapel as $item) {
          $grouped[$item['nama_guru']][] = $item;
      }
      ?>

      <?php if (!empty($grouped)): ?>
        <div class="space-y-3" id="accordionMapel">
          <?php foreach ($grouped as $mapel => $daftarGuru): ?>
            <div class="border rounded-lg overflow-hidden">
              <button class="w-full text-left px-4 py-3 bg-gray-100 hover:bg-gray-200 font-medium flex justify-between items-center" data-accordion-toggle="mapel-<?= md5($mapel) ?>">
                <span>Ustadz <?= esc($mapel) ?></span>
                <span class="bi bi-chevron-down text-sm"></span>
              </button>

              <div id="mapel-<?= md5($mapel) ?>" class="hidden px-4 py-3 text-sm">
                <?php foreach ($daftarGuru as $guru): ?>
                  <div class="pb-3 mb-3 border-b">
                    <p><strong>Nama Mapel:</strong> <?= esc($guru['nama_mapel']) ?></p>
                    <p><strong>Jumlah Jam:</strong> <?= esc($guru['jumlah_jam']) ?></p>

                    <div class="flex gap-2 mt-2">
                      <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="px-3 py-1 bg-yellow-400 text-white rounded text-xs">Edit</a>
                      <form action="<?= site_url('guru/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs">Hapus</button>
                      </form>
                    </div>
                  </div>
                <?php endforeach ?>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-400">Belum ada data jam mengajar guru.</p>
      <?php endif ?>


      <div class="relative text-center my-4">
        <hr>
        <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-gray-400 text-xs">Tambah Jam Mengajar</span>
      </div>

      <form action="<?= base_url('jadwal/distribusi/simpan') ?>" method="post" class="grid grid-cols-3 gap-3 text-sm">

        <div>
          <label class="block font-semibold text-xs mb-1">Guru</label>
          <select name="guru_id" class="w-full border rounded-lg px-3 py-2" required>
            <option value="">Pilih Guru</option>
            <?php foreach ($guruList as $g): ?>
              <option value="<?= $g['id'] ?>"><?= $g['nama'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block font-semibold text-xs mb-1">Mapel</label>
          <select name="mapel_id" class="w-full border rounded-lg px-3 py-2" required>
            <option value="">Pilih Mapel</option>
            <?php foreach ($matpel as $m): ?>
              <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block font-semibold text-xs mb-1">Jam</label>
          <input type="number" name="jumlah_jam" min="1" class="w-full border rounded-lg px-3 py-2" required>
        </div>

        <div class="col-span-3">
          <button class="w-full py-2 bg-green-600 text-white rounded-lg">Simpan</button>
        </div>
      </form>

    </div>

  </div>
</div>

  </div>
</div>
<script>
  document.querySelectorAll('[data-accordion-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = document.getElementById(btn.dataset.accordionToggle)
      target.classList.toggle('hidden')
    })
  })
</script>
<?= $this->endSection() ?>