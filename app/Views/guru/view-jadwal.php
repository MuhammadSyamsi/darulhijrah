<?= $this->extend('template_general') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="row mx-2 py-4">
<!-- Navigasi halaman -->
<div class="col-lg-12">
  <div class="card shadow-sm mb-4">
    <div class="card-body">

      <!-- Baris atas: Tombol cetak dan input pencarian -->
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">

        <!-- Tombol Cetak -->
        <div>
          <button class="btn btn-outline-primary" onclick="cetakJadwal()">
            <i class="bi bi-printer me-1"></i> Cetak Jadwal
          </button>
        </div>

        <!-- Input Cari -->
        <div class="flex-grow-1">
          <input type="text" id="inputCariJadwal" class="form-control" placeholder="Cari nama mapel / guru..." onfocus>
        </div>
      </div>

      <!-- Baris filter hari -->
      <div class="d-flex flex-wrap gap-2">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="filterHari" id="hariSemua" value="" checked>
          <label class="form-check-label" for="hariSemua">Semua</label>
        </div>
        <?php foreach ($jadwalGrouped as $hari => $data): ?>
          <?php $idHari = strtolower($hari); ?>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="filterHari" id="hari<?= $idHari ?>" value="<?= $idHari ?>">
            <label class="form-check-label" for="hari<?= $idHari ?>"><?= esc($hari) ?></label>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>



<!-- Jadwal Tabel -->
<div class="col-md-12 mb-4">
    <div class="card shadow-sm">
        <div class="card-body">
            
<div class="accordion" id="jadwalAccordion">
  <?php foreach ($jadwalGrouped as $hari => $jamData): ?>
    <?php $idHari = strtolower($hari); ?>
    <div class="accordion-item jam-wrapper hari-wrapper" id="hari-<?= $idHari ?>" data-hari="<?= $idHari ?>">
      <h2 class="accordion-header" id="heading-<?= $idHari ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $idHari ?>">
          <i class="bi bi-calendar3 me-2"></i> Hari <?= esc($hari) ?>
        </button>
      </h2>
      <div id="collapse-<?= $idHari ?>" class="accordion-collapse collapse" data-bs-parent="#jadwalAccordion">
        <div class="accordion-body">

          <!-- Accordion Jam Ke dalam Hari -->
          <div class="accordion" id="accordionJam-<?= $idHari ?>">
            <?php foreach ($jamData as $jam => $kelasRow): ?>
              <?php $idJam = "jam{$jam}_{$idHari}"; ?>
              <div class="accordion-item jam-wrapper" data-hari="<?= $idHari ?>">
                <h2 class="accordion-header" id="heading-<?= $idJam ?>">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= $idJam ?>">
                    🕒 Jam ke-<?= $jam ?>
                  </button>
                </h2>
                <div id="collapse-<?= $idJam ?>" class="accordion-collapse collapse" data-bs-parent="#accordionJam-<?= $idHari ?>">
                  <div class="accordion-body p-2">
                    <ul class="list-group">
                      <?php foreach ($kelasList as $kelas): ?>
<li class="list-group-item small jadwal-item">
  <strong><?= esc($kelas) ?>:</strong>
  <?php if (isset($kelasRow[$kelas])): ?>
    <span class="isi-jadwal">
      <?= esc($kelasRow[$kelas]['nama_mapel']) ?> - 
      <small class="text-muted"><?= esc($kelasRow[$kelas]['nama_guru']) ?></small>
    </span>
  <?php else: ?>
    <span class="text-muted isi-jadwal">Kosong</span>
  <?php endif; ?>
</li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <!-- End Accordion Jam -->

        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
            
            </div>
            </div>
            </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function cetakJadwal() {
  window.open('<?= base_url("jadwal/cetak") ?>', '_blank');
}
</script>
<script>
  $(document).ready(function () {

    $('#inputCariJadwal').focus();
  
    function applyFilter() {
      const keyword = $('#inputCariJadwal').val().toLowerCase();
      const selectedHari = $('input[name="filterHari"]:checked').val(); // "" = semua

      // Reset semua item
      $('.jadwal-item').show();
      $('.jam-wrapper').removeClass('d-none');
      $('.hari-wrapper').removeClass('d-none');

      if (keyword.length > 0 || selectedHari) {
        // Buka semua accordion
        $('.accordion-collapse').addClass('show');
        $('.accordion-button').removeClass('collapsed');

        // Sembunyikan hari yang tidak sesuai jika filterHari dipilih
        $('.hari-wrapper').each(function () {
          const id = $(this).attr('id').replace('hari-', ''); // contoh: 'hari-senin'
          if (selectedHari && id !== selectedHari) {
            $(this).addClass('d-none');
          }
        });

        // Filter berdasarkan keyword
        $('.jadwal-item').each(function () {
          const isi = $(this).find('.isi-jadwal').text().toLowerCase();
          const cocok = isi.includes(keyword);
          $(this).toggle(cocok);
        });

        // Sembunyikan jam kosong
        $('.jam-wrapper').each(function () {
          const visible = $(this).find('.jadwal-item:visible').length > 0;
          $(this).toggleClass('d-none', !visible);
        });

        // Sembunyikan hari kosong
        $('.hari-wrapper').each(function () {
          const visibleJam = $(this).find('.jam-wrapper:visible').length;
          $(this).toggleClass('d-none', visibleJam === 0);
        });
      } else {
        // Reset semua
        $('.accordion-collapse').removeClass('show');
        $('.accordion-button').addClass('collapsed');
        $('.jadwal-item').show();
        $('.jam-wrapper').removeClass('d-none');
        $('.hari-wrapper').removeClass('d-none');
      }
    }

    $('#inputCariJadwal').on('keyup', applyFilter);
    $('input[name="filterHari"]').on('change', applyFilter);
  });
</script>

<?= $this->endSection() ?>