<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_sekolah');
}
?><?= $this->section('konten') ?><!-- NOTE: This view rewritten using Tailwind CSS utility classes. Make sure Tailwind is loaded in your base template. --><div class="max-w-7xl mx-auto p-4">
  <div class="space-y-6"><!-- Accordion: Atur Jadwal Guru -->
<section class="bg-white shadow rounded-2xl p-4">
  <details class="group" open>
    <summary class="flex items-center justify-between cursor-pointer p-3 rounded-lg bg-slate-50 hover:bg-slate-100">
      <div class="flex items-center gap-3">
        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
        <span class="font-semibold">Atur Jadwal Guru</span>
      </div>
      <div class="text-sm text-slate-500 group-open:rotate-180 transition-transform">
        ▼
      </div>
    </summary>

    <div class="mt-4">
      <form id="formJadwal" action="<?= base_url('jadwal/simpanChecklist') ?>" method="post" class="space-y-4">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="guru_id" class="block text-sm font-medium text-slate-700 mb-1">Guru</label>
            <select name="guru_id" id="guru_id" class="block w-full rounded-md border-gray-200 shadow-sm focus:ring-1 focus:ring-sky-300" required>
              <option value="">Pilih Guru</option>
              <?php foreach ($guruList as $guru): ?>
                <option value="<?= $guru['id'] ?>"><?= esc($guru['nama']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label for="mapel_id" class="block text-sm font-medium text-slate-700 mb-1">Mata Pelajaran</label>
            <select name="mapel_id" id="mapel_id" class="block w-full rounded-md border-gray-200 shadow-sm focus:ring-1 focus:ring-sky-300" required>
              <option value="">Pilih Mapel</option>
              <?php foreach ($matpel as $mapel): ?>
                <option value="<?= $mapel['id'] ?>"><?= esc($mapel['nama_mapel']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="space-y-3">
          <!-- Kelas Accordions -->
          <?php foreach ($kelasChecklist as $index => $kelas): ?>
            <?php
              $accordionId = 'kelasAccordion' . $kelas['id'];
            ?>

            <details class="group bg-slate-50 rounded-md p-3" <?= $index === 0 ? 'open' : '' ?>>
              <summary class="flex items-center justify-between cursor-pointer font-medium">
                <div><?= esc($kelas['nama_kelas']) ?> — <span class="text-sm text-slate-500"><?= esc($kelas['tingkat']) ?></span></div>
                <div class="text-slate-400">▾</div>
              </summary>

              <div class="mt-3">
                <input type="hidden" name="kelas_id[]" value="<?= $kelas['id'] ?>">

                <div class="overflow-x-auto">
                  <table class="min-w-full table-auto border-collapse text-sm">
                    <thead>
                      <tr class="bg-white">
                        <th class="px-3 py-2 text-left font-medium">Hari / Jam ke-</th>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                          <th class="px-2 py-2 text-center font-medium">Ke-<?= $i ?></th>
                        <?php endfor; ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($hari as $h): ?>
                        <tr class="border-t">
                          <th class="px-3 py-2 text-left font-medium w-48"><?= esc($h['nama_hari']) ?></th>
                          <?php for ($i = 1; $i <= 8; $i++): ?>
                            <?php $isTerisi = isset($slotTerisi[$kelas['id']][$h['id']][$i]); ?>
                            <td class="px-2 py-2 text-center align-middle">
                              <label class="inline-flex items-center gap-2">
                                <input
                                  type="checkbox"
                                  class="h-4 w-4 rounded border-gray-300 focus:ring-1 focus:ring-sky-300"
                                  name="slots[<?= $kelas['id'] ?>][<?= $h['id'] ?>][]"
                                  value="<?= $i ?>"
                                  <?= $isTerisi ? 'checked disabled title="Sudah terisi di jadwal"' : '' ?> />
                              </label>
                            </td>
                          <?php endfor; ?>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </details>

          <?php endforeach; ?>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan Jadwal
          </button>
        </div>

      </form>
    </div>

  </details>
</section>

<!-- Jadwal Tabel -->
<section class="bg-white shadow rounded-2xl p-4">
  <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h5 class="flex items-center gap-2 font-semibold text-slate-800">
      <svg class="w-5 h-5"><use href="#"/></svg>
      Jadwal Pelajaran
    </h5>

    <div class="flex items-center gap-2">
      <form action="<?= base_url('jadwal/generate/proses') ?>" method="post" class="inline">
        <button class="px-3 py-2 rounded-md bg-amber-400 text-slate-800 hover:bg-amber-300">Generate Otomatis</button>
      </form>

      <form action="<?= base_url('jadwal/reset') ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus SEMUA jadwal? Tindakan ini tidak bisa dibatalkan.')" class="inline">
        <?= csrf_field() ?>
        <button type="submit" class="px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-500">Reset Semua Jadwal</button>
      </form>
    </div>
  </div>

  <div>
    <?php if (empty($jadwalGrouped)): ?>
      <div class="py-12 text-center text-slate-500">Belum ada jadwal tersedia.</div>
    <?php else: ?>
      <?php foreach ($jadwalGrouped as $hariNama => $jamData): ?>
        <div class="mb-6">
          <h6 class="font-semibold mb-3">Hari <?= esc($hariNama) ?></h6>

          <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm border-collapse">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-3 py-2 text-left">Jam Ke</th>
                  <?php foreach ($kelasList as $kelas): ?>
                    <th class="px-3 py-2 text-center"><?= esc($kelas) ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($jamData as $jam => $kelasRow): ?>
                  <tr class="border-t">
                    <td class="px-3 py-2 font-medium"><?= $jam ?></td>
                    <?php foreach ($kelasList as $kelas): ?>
                      <td class="px-3 py-2 align-top text-center">
                        <?php if (isset($kelasRow[$kelas])): ?>
                          <div class="inline-block text-left w-full max-w-xs mx-auto bg-white border rounded-md p-2 shadow-sm">
                            <div class="text-sm font-medium"><?= esc($kelasRow[$kelas]['nama_mapel']) ?></div>
                            <div class="text-xs text-slate-500"><?= esc($kelasRow[$kelas]['nama_guru']) ?></div>
                            <div class="mt-2 flex justify-end gap-2">
                              <button class="btn-edit inline-flex items-center justify-center px-2 py-1 border rounded text-xs" data-id="<?= $kelasRow[$kelas]['id'] ?>">Edit</button>
                              <button class="btn-hapus inline-flex items-center justify-center px-2 py-1 border rounded text-xs" data-id="<?= $kelasRow[$kelas]['id'] ?>">Hapus</button>
                            </div>
                          </div>
                        <?php else: ?>
                          <span class="text-slate-400">-</span>
                        <?php endif; ?>
                      </td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

  </div>
</div><!-- Edit template (hidden) --><template id="edit-template">
  <form class="edit-form flex flex-col gap-2">
    <select name="mapel_id" class="rounded-md border-gray-200 p-1 text-sm" required>
      <?php foreach ($matpel as $m): ?>
        <option value="<?= $m['id'] ?>"><?= esc($m['nama_mapel']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="guru_id" class="rounded-md border-gray-200 p-1 text-sm" required>
      <?php foreach ($guruList as $g): ?>
        <option value="<?= $g['id'] ?>"><?= esc($g['nama']) ?></option>
      <?php endforeach; ?>
    </select>
    <div class="flex justify-end gap-2">
      <button type="submit" class="px-3 py-1 rounded-md bg-emerald-600 text-white text-sm">Simpan</button>
      <button type="button" class="btn-cancel px-3 py-1 rounded-md bg-slate-200 text-sm">Batal</button>
    </div>
  </form>
</template><script>
// Minimal JS: delegate edit/hapus actions and inject edit form from template
document.addEventListener('click', function(e){
  if (e.target.closest('.btn-edit')){
    const id = e.target.closest('.btn-edit').dataset.id;
    const cell = e.target.closest('td');
    const tpl = document.getElementById('edit-template');
    const clone = tpl.content.cloneNode(true);
    // attach submit handler (example: send fetch to update endpoint)
    const form = clone.querySelector('form');
    form.addEventListener('submit', function(ev){
      ev.preventDefault();
      // here you can send fetch to server to save edit, then update UI
      alert('Simpan perubahan (implementasikan fetch ke server)');
    });
    cell.innerHTML = '';
    cell.appendChild(clone);
  }

  if (e.target.closest('.btn-hapus')){
    const id = e.target.closest('.btn-hapus').dataset.id;
    if (confirm('Hapus jadwal ini?')){
      // lakukan request delete (implementasikan)
      alert('Menghapus jadwal id: ' + id + ' (implementasikan request ke server)');
    }
  }

  if (e.target.closest('.btn-cancel')){
    // simple reload to restore UI — tweak if you want smarter revert
    location.reload();
  }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const template = document.getElementById('edit-template');

  function attachEditAndDeleteListeners() {
    document.querySelectorAll('.btn-edit').forEach(button => {
      button.addEventListener('click', () => {
        const cell = button.closest('.jadwal-cell');
        const id = cell.dataset.id;
        const view = cell.querySelector('.jadwal-view');

        // Simpan konten asli hanya jika belum ada
        if (!view.dataset.original) {
          view.dataset.original = view.innerHTML;
        }

        // Tampilkan form
        const form = template.content.cloneNode(true).querySelector('.edit-form');
        view.innerHTML = '';
        view.appendChild(form);

        // Handle cancel
        form.querySelector('.btn-cancel').addEventListener('click', () => {
          view.innerHTML = view.dataset.original;
          delete view.dataset.original;
          attachEditAndDeleteListeners(); // Re-attach tombol
        });

        // Handle submit
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const formData = new FormData(form);
          const res = await fetch(`<?= base_url('jadwal/update') ?>/${id}`, {
            method: 'POST',
            body: formData
          });

          if (res.ok) {
            const json = await res.json();
            view.innerHTML = `
              <div>${json.nama_mapel}</div>
              <small class="text-muted">${json.nama_guru}</small>
              <div class="mt-1">
                <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button>
              </div>
            `;
            delete view.dataset.original;
            attachEditAndDeleteListeners(); // Re-attach tombol
          } else {
            alert('Gagal update jadwal.');
          }
        });
      });
    });

    document.querySelectorAll('.btn-hapus').forEach(button => {
      button.addEventListener('click', async () => {
        if (!confirm('Yakin hapus jadwal ini?')) return;
        const cell = button.closest('.jadwal-cell');
        const id = cell.dataset.id;

        const res = await fetch(`<?= base_url('jadwal/hapus') ?>/${id}`, {
          method: 'DELETE'
        });

        if (res.ok) {
          cell.innerHTML = `
  <div class="jadwal-view">
    <span class="text-muted">-</span>
    <div class="mt-1">
      <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
    </div>
  </div>
`;
const newEditBtn = cell.querySelector('.btn-edit');
if (newEditBtn) {
  newEditBtn.addEventListener('click', () => {
    // Sama seperti fungsi klik edit awal
    const id = cell.dataset.id;
    const view = cell.querySelector('.jadwal-view');
    const originalContent = view.innerHTML;
    const form = template.content.cloneNode(true).querySelector('.edit-form');
    view.innerHTML = '';
    view.appendChild(form);

    form.querySelector('.btn-cancel').addEventListener('click', () => {
      view.innerHTML = originalContent;
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(form);
      const res = await fetch(`<?= base_url('jadwal/update') ?>/${id}`, {
        method: 'POST',
        body: formData
      });

      if (res.ok) {
        const json = await res.json();
        view.innerHTML = `
          <div>${json.nama_mapel}</div>
          <small class="text-muted">${json.nama_guru}</small>
          <div class="mt-1">
            <button class="btn btn-sm btn-outline-primary btn-edit"><i class="bi bi-pencil"></i></button>
            <button class="btn btn-sm btn-outline-danger btn-hapus"><i class="bi bi-trash"></i></button>
          </div>
        `;
        // Tambahkan event listener ulang
        setupButtonEvents(cell);
      } else {
        alert('Gagal update jadwal.');
      }
    });
  });
}
        } else {
          alert('Gagal hapus jadwal.');
        }
      });
    });
  }

  // Inisialisasi awal
  attachEditAndDeleteListeners();
});
</script>

<?= $this->endSection() ?>
