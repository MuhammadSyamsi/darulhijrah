<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="w-full px-4">
  <div class="flex justify-center">
    <div class="w-full">

      <!-- CARD UTAMA -->
      <div class="bg-white rounded-xl shadow-sm mb-6">
        <div class="p-4 sm:p-6">

          <!-- ================= STATISTIK SANTRI ================= -->
          <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="p-4">
              <h5 class="font-semibold text-gray-800 mb-4">
                Statistik Santri
              </h5>

              <div class="grid grid-cols-3 text-center divide-x">
                <div class="px-2">
                  <div class="text-sm text-gray-500">
                    Total Calon Santri
                  </div>
                  <div class="text-2xl font-bold text-yellow-500">
                    <?= $total ?>
                  </div>
                </div>

                <div class="px-2">
                  <div class="text-sm text-gray-500">
                    Santri MTs
                  </div>
                  <div class="text-2xl font-bold text-gray-600">
                    <?= $mts ?>
                  </div>
                </div>

                <div class="px-2">
                  <div class="text-sm text-gray-500">
                    Santri MA
                  </div>
                  <div class="text-2xl font-bold text-red-500">
                    <?= $ma ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ================= HEADER DATA SANTRI ================= -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
            <h5 class="font-semibold text-gray-800">
              Data Santri
            </h5>

            <a href="<?= base_url('pendaftaran-formulir') ?>"
               class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-2 rounded-lg">
              <i class="bi bi-plus-circle"></i>
              Tambah Pendaftaran
            </a>
          </div>

          <!-- ================= FILTER ================= -->
          <form id="formFilter" class="grid grid-cols-1 md:grid-cols-12 gap-3">

            <!-- Jenjang -->
            <div class="md:col-span-2">
              <label class="text-sm text-gray-600">
                Jenjang
              </label>
              <select name="jenjang" id="filterJenjang"
                      class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Jenjang</option>
                <?php foreach ($filterJenjang as $fj): ?>
                  <option value="<?= $fj['jenjang']; ?>">
                    <?= $fj['jenjang']; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Kelas -->
            <div class="md:col-span-2">
              <label class="text-sm text-gray-600">
                Kelas
              </label>
              <select name="kelas" id="filterKelas" disabled
                      class="w-full mt-1 rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
                <option value="">Pilih Kelas</option>
              </select>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
              <label class="text-sm text-gray-600">
                Status
              </label>
              <select name="status" id="filterStatus"
                      class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Status</option>
                <?php foreach ($statusList as $s): ?>
                  <option value="<?= $s['status'] ?>">
                    <?= ucfirst($s['status']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Search -->
            <div class="md:col-span-6">
              <label class="text-sm text-gray-600">
                Pencarian Nama
              </label>
              <input type="text"
                     name="keyword"
                     id="keyword"
                     autocomplete="off"
                     placeholder="Cari Nama..."
                     class="w-full mt-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
            </div>

          </form>

          <!-- ================= DATA AJAX ================= -->
          <div class="mt-4">
            <div id="cardListSantri">
              <!-- Data AJAX muncul di sini -->
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- Script Filtering -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
  const kelasByJenjang = <?= json_encode($kelasByJenjang) ?>;
  const form = $('#formFilter');

  $('#filterJenjang').on('change', function () {
    const jenjang = $(this).val();
    let html = '<option value="">Pilih Kelas</option>';

    if (jenjang && kelasByJenjang[jenjang]) {
      kelasByJenjang[jenjang].forEach(k => {
        html += `<option value="${k}">${k}</option>`;
      });
      $('#filterKelas').html(html).prop('disabled', false);
    } else {
      $('#filterKelas').html(html).prop('disabled', true);
    }

    filterSantri();
  });

  $('#filterKelas, #filterStatus, #keyword').on('change keyup', function () {
    filterSantri();
  });

  function filterSantri() {
    const kelas = $('#filterKelas').val();
    const keyword = $('#keyword').val().trim();
    const jenjang = $('#filterJenjang').val();

    if (jenjang && kelas || keyword.length > 0) {
      $.ajax({
        type: 'GET',
        url: '<?= base_url('Santri/psb') ?>',
        data: form.serialize(),
        success: function (html) {
          $('#cardListSantri').html(html);
        }
      });
    } else {
      $('#cardListSantri').html('');
    }
  }

  filterSantri(); // initial load
});
</script>

<?= $this->endSection(); ?>
