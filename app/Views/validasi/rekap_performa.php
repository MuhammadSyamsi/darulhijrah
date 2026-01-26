<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">

      <div class="card shadow-sm mb-4">
        <div class="card-body">

          <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <h5 class="mb-2 mb-md-0">Rekap Performa Guru</h5>

            <!-- Filter Bulan -->
<form method="get" class="d-flex flex-wrap align-items-center gap-2">
  <?php
    $bulanList = [
      '01' => 'Januari',
      '02' => 'Februari',
      '03' => 'Maret',
      '04' => 'April',
      '05' => 'Mei',
      '06' => 'Juni',
      '07' => 'Juli',
      '08' => 'Agustus',
      '09' => 'September',
      '10' => 'Oktober',
      '11' => 'November',
      '12' => 'Desember'
    ];
    [$tahunAktif, $bulanAktif] = explode('-', $periode);
  ?>

  <select id="bulan" class="form-select form-select-sm" style="width:auto;">
    <?php foreach ($bulanList as $num => $nama): ?>
      <option value="<?= $num ?>" <?= ($bulanAktif == $num ? 'selected' : '') ?>>
        <?= $nama ?>
      </option>
    <?php endforeach ?>
  </select>

  <input type="number" id="tahun" class="form-control form-control-sm" 
         style="width:90px;" value="<?= $tahunAktif ?>" min="2020" max="<?= date('Y') ?>">

  <input type="hidden" name="periode" id="periode" value="<?= $periode ?>">

  <button type="submit" class="btn btn-sm btn-primary">
    <i class="bi bi-funnel"></i> Tampilkan
  </button>

  <a href="<?= base_url('validasi/rekap/download?' . $_SERVER['QUERY_STRING']) ?>"
     class="btn btn-sm btn-success">
    <i class="bi bi-download"></i> Download
  </a>
</form>
          </div>

          <!-- Tabel Rekap -->
          <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th>No</th>
                  <th>Nama Guru</th>
                  <th>Total Kehadiran</th>
                  <th>Tepat Waktu</th>
                  <th>Terlambat</th>
                  <th>Tidak Hadir</th>
                  <th>% Kehadiran</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($data)): ?>
                  <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                <?php else: ?>
                  <?php foreach ($data as $i => $r): ?>
                    <tr>
                      <td class="text-center"><?= $i + 1 ?></td>
                      <td><?= esc($r['nama_guru']) ?></td>
                      <td class="text-center"><?= $r['total'] ?></td>
                      <td class="text-center text-success fw-semibold"><?= $r['tepat'] ?></td>
                      <td class="text-center text-warning fw-semibold"><?= $r['terlambat'] ?></td>
                      <td class="text-center text-danger fw-semibold"><?= $r['absen'] ?></td>
                      <td class="text-center fw-semibold">
                        <?= number_format(($r['tepat'] / max($r['total'], 1)) * 100, 1) ?>%
                      </td>
                    </tr>
                  <?php endforeach ?>
                <?php endif ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<script>
  // Saat bulan atau tahun berubah, update input hidden "periode"
  const bulan = document.getElementById('bulan');
  const tahun = document.getElementById('tahun');
  const periode = document.getElementById('periode');

  function updatePeriode() {
    periode.value = `${tahun.value}-${bulan.value}`;
  }

  bulan.addEventListener('change', updatePeriode);
  tahun.addEventListener('change', updatePeriode);
</script>
<?= $this->endSection() ?>

