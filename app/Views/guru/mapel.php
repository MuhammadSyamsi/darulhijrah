<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_sekolah');
}
?>
<?= $this->section('konten') ?>

<div class="max-w-6xl mx-auto px-4 py-6">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- MAPEL CARD -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md overflow-hidden">
      <div class="p-6">
        <div class="flex items-center justify-between">
          <h5 class="text-lg font-semibold flex items-center gap-2 text-slate-800 dark:text-slate-100">
            <span class="material-symbols-outlined text-xl">menu_book</span>
            Mapel
          </h5>
        </div><?php if (!empty($matpel)): ?>
      <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-700">
        <?php foreach ($matpel as $guru): ?>
          <li class="py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300">
                <span class="material-symbols-outlined">school</span>
              </div>
              <div>
                <div class="text-sm font-medium text-slate-800 dark:text-slate-100"><?= esc($guru['nama_mapel']) ?></div>
                <div class="text-xs text-slate-500 dark:text-slate-400">ID: <?= esc($guru['id']) ?></div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 border border-amber-400 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm" title="Edit">
                <span class="material-symbols-outlined">edit</span>
                Edit
              </a>

              <form action="<?= site_url('guru/delete/' . $guru['id']) ?>" method="post" onsubmit="return confirm('Hapus data ini?')" class="inline">
                <?= csrf_field() ?>
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-sm" title="Hapus">
                  <span class="material-symbols-outlined">delete</span>
                  Hapus
                </button>
              </form>
            </div>
          </li>
        <?php endforeach ?>
      </ul>
    <?php else: ?>
      <div class="mt-6 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada mata pelajaran.</div>
    <?php endif ?>

    <div class="relative mt-6">
      <div class="h-px bg-slate-100 dark:bg-slate-700"></div>
      <div class="absolute left-1/2 -translate-x-1/2 -top-3 bg-white dark:bg-slate-800 px-3 text-sm text-slate-500">Tambah Mapel</div>
    </div>

    <form action="<?= base_url('jadwal/mapel/simpan') ?>" method="post" class="mt-4">
      <div class="flex gap-2">
        <input type="text" name="nama_mapel" class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100" placeholder="Nama Mapel" required>
        <button class="inline-flex items-center gap-2 rounded-lg px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium shadow-sm" type="submit">
          <span class="material-symbols-outlined">add</span>
          Tambah
        </button>
      </div>
    </form>

  </div>
</div>

<!-- OPTIONAL: Placeholder / Actions / Button to open modal Tambah Guru -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md p-6 flex flex-col justify-between">
  <div>
    <h6 class="text-md font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2">
      <span class="material-symbols-outlined">person_add</span>
      Guru
    </h6>
    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kelola data guru — tambah, edit atau hapus guru.</p>

    <div class="mt-6 grid grid-cols-1 gap-3">
      <button id="openModalBtn" class="w-full inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-medium shadow-sm">
        <span class="material-symbols-outlined">person_add_alt_1</span>
        Tambah Guru
      </button>

      <a href="#" class="w-full inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 border border-slate-200 dark:border-slate-700 bg-transparent text-slate-700 dark:text-slate-200 text-sm">
        <span class="material-symbols-outlined">format_list_bulleted</span>
        Lihat Semua Guru
      </a>
    </div>
  </div>

  <div class="mt-4 text-xs text-slate-500 dark:text-slate-400">Tip: gunakan tombol di atas untuk menambah data guru lewat modal.</div>
</div>

  </div>
</div><!-- Modal (vanilla JS toggle) --><div id="modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
  <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg max-w-2xl w-full overflow-hidden">
    <div class="flex items-center justify-between p-4 border-b border-slate-100 dark:border-slate-700">
      <h6 class="text-sm font-semibold text-slate-800 dark:text-slate-100 flex items-center gap-2"><span class="material-symbols-outlined">person_add</span>Tambah Guru</h6>
      <button id="closeModalBtn" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="p-4">
      <!-- include form partial -->
      <?= $this->include('guru/_form') ?>
    </div>
  </div>
</div>
<script>
  // Simple modal open/close
  const openBtn = document.getElementById('openModalBtn');
  const closeBtn = document.getElementById('closeModalBtn');
  const modal = document.getElementById('modal');

  if (openBtn) openBtn.addEventListener('click', () => { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; });
  if (closeBtn) closeBtn.addEventListener('click', () => { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; });
  // Close on backdrop click
  modal && modal.addEventListener('click', (e) => { if (e.target === modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = ''; } });
</script>
<?= $this->endSection() ?>