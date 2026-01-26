<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>
<?php $today = date('Y-m-d'); ?>

<div class="w-full px-4">
  <div class="flex">
    <div class="w-full">

      <!-- ================= CARD ================= -->
      <div class="bg-white rounded-xl shadow-sm">
        <div class="p-4 sm:p-6">

          <!-- Header -->
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
              Formulir Santri Baru
            </h3>

            <button
              class="inline-flex items-center gap-1 bg-sky-500 hover:bg-sky-600 text-white text-sm px-3 py-2 rounded-lg"
              onclick="document.getElementById('psbModal').classList.remove('hidden')">
              <i class="bi bi-table"></i>
              View Data
            </button>
          </div>

          <!-- ================= FORM ================= -->
          <form action="<?= base_url('formulir_psb') ?>" method="post">
            <?= csrf_field(); ?>

            <div class="overflow-x-auto">
              <table class="w-full border border-gray-200 text-sm text-center">
                <thead class="bg-gray-100 text-gray-700">
                  <tr>
                    <th class="border px-2 py-2">Nama</th>
                    <th class="border px-2 py-2">Jenjang</th>
                    <th class="border px-2 py-2">Tanggal Daftar</th>
                    <th class="border px-2 py-2">Bayar Formulir</th>
                    <th class="border px-2 py-2">Rekening</th>
                    <th class="border px-2 py-2">Aksi</th>
                  </tr>
                </thead>

                <tbody id="form-container">
                  <tr class="repeatable-row">

                    <input type="hidden" name="santri[0][id]" value="<?= $id ? max($id)+1 : 1; ?>">
                    <input type="hidden" name="santri[0][nisn]" value="0">
                    <input type="hidden" name="santri[0][program]" value="MANDIRI">
                    <input type="hidden" name="santri[0][status]" value="formulir">

                    <td class="border p-2">
                      <input type="text"
                             name="santri[0][nama]"
                             required
                             placeholder="Nama"
                             class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </td>

                    <td class="border p-2">
                      <select name="santri[0][jenjang]"
                              class="w-full rounded-lg border-gray-300">
                        <option value="MTs|7">MTs</option>
                        <option value="MA|10">MA</option>
                      </select>
                    </td>

                    <td class="border p-2">
                      <input type="date"
                             name="santri[0][tanggal]"
                             value="<?= $today ?>"
                             class="w-full rounded-lg border-gray-300">
                    </td>

                    <td class="border p-2">
                      <input type="number"
                             name="santri[0][formulir]"
                             value="0"
                             class="w-full rounded-lg border-gray-300">
                    </td>

                    <td class="border p-2">
                      <select name="santri[0][rekening]"
                              class="w-full rounded-lg border-gray-300">
                        <option value="Muamalat Salam">Muamalat Salam</option>
                        <option value="Jatim Syariah">Jatim Syariah</option>
                        <option value="BSI">BSI</option>
                        <option value="Tunai">Tunai</option>
                        <option value="lain-lain">Lain-lain</option>
                      </select>
                    </td>

                    <td class="border p-2">
                      <button type="button"
                              class="remove-row text-red-600 hover:text-red-800">
                        <i class="bi bi-dash-circle text-lg"></i>
                      </button>
                    </td>

                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-2 mt-4">
              <button type="button"
                      id="add-row"
                      class="inline-flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white text-sm px-3 py-2 rounded-lg">
                <i class="bi bi-plus-circle"></i>
                Tambah Baris
              </button>

              <button type="submit"
                      class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg">
                Simpan Semua
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ================= MODAL VIEW DATA ================= -->
<div id="psbModal"
     class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">

  <div class="bg-white w-full max-w-6xl rounded-xl shadow-lg max-h-[90vh] overflow-hidden">

    <!-- Header -->
    <div class="flex justify-between items-center border-b px-4 py-3">
      <h5 class="font-semibold text-gray-800">
        Data Pendaftaran Santri
      </h5>
      <button onclick="document.getElementById('psbModal').classList.add('hidden')"
              class="text-gray-500 hover:text-gray-700">
        ✕
      </button>
    </div>

    <!-- Body -->
    <div class="p-4 overflow-y-auto">

      <!-- Filter -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
        <select id="filter-status"
                class="rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
          <option value="">-- Semua Status --</option>
          <option value="formulir">Formulir</option>
          <option value="diterima">Diterima</option>
          <option value="lulus">Lulus</option>
        </select>

        <button id="btn-filter"
                class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">
          Filter
        </button>
      </div>

      <div id="table-container" class="text-sm text-gray-500">
        Silakan pilih filter untuk melihat data...
      </div>

    </div>
  </div>
</div>


<!-- JS -->
<script>
let rowIndex = 1;

// Tambah baris baru
document.getElementById('add-row').addEventListener('click', function() {
  let container = document.getElementById('form-container');
  let clone = container.querySelector('.repeatable-row').cloneNode(true);

  clone.querySelectorAll('input, select').forEach(el => {
    let name = el.getAttribute('name');
    if (name) {
      el.setAttribute('name', name.replace(/\[\d+\]/, '[' + rowIndex + ']'));
      if (el.type !== 'hidden') {
        el.value = (el.tagName === 'SELECT') ? el.options[0].value : '';
      }
    }
  });

  container.appendChild(clone);
  rowIndex++;
});

// Hapus baris
document.addEventListener('click', function(e) {
  if (e.target.closest('.remove-row')) {
    let row = e.target.closest('.repeatable-row');
    if (document.querySelectorAll('.repeatable-row').length > 1) {
      row.remove();
    } else {
      alert('Minimal satu baris harus ada!');
    }
  }
});

// Filter data via AJAX
document.getElementById('btn-filter').addEventListener('click', function() {
  let status = document.getElementById('filter-status').value;

  fetch("<?= base_url('psb/filter') ?>", {
    method: "POST",
    headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest" },
    body: JSON.stringify({status: status})
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('table-container').innerHTML = html;
  });
});
</script>

<?= $this->endSection(); ?>
