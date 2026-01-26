<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_sekolah');
}
?>

<?= $this->section('konten') ?>

<div class="max-w-6xl mx-auto px-4 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left / main card -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-500">home</span>
            Kelas
          </h3>
          <button
            type="button"
            id="openModalBtn"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-sky-600 text-white hover:bg-sky-700"
          >
            <span class="material-symbols-outlined">person_add</span>
            Tambah Guru
          </button>
        </div>

        <?php if (!empty($kelas)): ?>
          <div class="space-y-2">
            <?php foreach ($kelas as $guru): ?>
              <div class="flex items-center justify-between bg-slate-50 rounded-lg p-3 border border-slate-100">
                <div class="text-sm font-medium">
                  <?= esc($guru['nama_kelas']) ?> <span class="text-slate-400">-</span> <?= esc($guru['tingkat']) ?>
                </div>
                <div class="flex items-center gap-2">
                  <a href="<?= site_url('guru/edit/' . $guru['id']) ?>" title="Edit"
                     class="inline-flex items-center gap-2 px-2 py-1 rounded-md bg-amber-100 text-amber-800 hover:bg-amber-200">
                    <span class="material-symbols-outlined">edit</span>
                    <span class="text-xs">Edit</span>
                  </a>

                  <form action="<?= site_url('kelas/delete/' . $guru['id']) ?>" method="post"
                        onsubmit="return confirm('Hapus data ini?')" class="inline">
                    <?= csrf_field() ?>
                    <button type="submit" title="Hapus"
                            class="inline-flex items-center gap-2 px-2 py-1 rounded-md bg-red-100 text-red-700 hover:bg-red-200">
                      <span class="material-symbols-outlined">delete</span>
                      <span class="text-xs">Hapus</span>
                    </button>
                  </form>
                </div>
              </div>
            <?php endforeach ?>
          </div>
        <?php else: ?>
          <div class="text-center text-slate-400 py-6">Belum ada Kelas.</div>
        <?php endif ?>

        <div class="relative my-6">
          <hr class="border-slate-200" />
          <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-3 text-sm text-slate-500">
            Tambah Kelas
          </div>
        </div>

        <form action="<?= base_url('jadwal/kelas/simpan') ?>" method="post" class="space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="text" name="nama_kelas" class="block w-full rounded-md border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Kelas (7, 8, 9, ...)" required>

            <input type="text" name="tingkat" class="block w-full rounded-md border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-300" placeholder="Rombel (A, B, C, ...)" required>

            <div class="flex">
              <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-sky-600 text-white hover:bg-sky-700">
                <span class="material-symbols-outlined">save</span>
                Simpan
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>

    <!-- Right / info card or empty placeholder -->
    <div>
      <div class="bg-white rounded-2xl shadow p-4">
        <h4 class="text-sm font-semibold mb-2">Informasi</h4>
        <p class="text-sm text-slate-600">Gunakan form di kiri untuk menambah kelas. Untuk mengelola guru, gunakan tombol "Tambah Guru".</p>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Guru (Tailwind) -->
<div id="modalGuru" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/40" id="modalBackdrop"></div>

  <div class="relative w-full max-w-lg mx-4">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 bg-sky-600 text-white">
        <h6 class="text-sm font-medium flex items-center gap-2">
          <span class="material-symbols-outlined">person_add</span>
          Tambah Guru
        </h6>
        <button id="closeModalBtn" class="text-white/90 hover:text-white">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <div class="p-4">
        <?= $this->include('guru/_form') ?>
      </div>
    </div>
  </div>
</div>

<script>
  // Simple modal toggle
  const openBtn = document.getElementById('openModalBtn');
  const closeBtn = document.getElementById('closeModalBtn');
  const modal = document.getElementById('modalGuru');
  const backdrop = document.getElementById('modalBackdrop');

  if (openBtn) openBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
  });

  function closeModal() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
  }

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (backdrop) backdrop.addEventListener('click', closeModal);

  // Close on ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
  });
</script>

<?= $this->endSection() ?>

</html>