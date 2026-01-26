<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Landing Page - Data Saku Santri</title>

  <!-- ✅ Style Sistaff -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body style="background-color: #f8f9fa;">

<!-- Container -->
<div class="container py-4">
  <h5 class="mb-3 text-center">Data Saku Santri</h5>

  <!-- 🔍 Filter -->
  <div class="mb-3">
    <input type="text" id="searchNama" class="form-control mb-2" placeholder="Cari nama santri...">
    <select id="filterJenjang" class="form-select">
      <option value="">-- Semua Jenjang --</option>
      <option value="MTs">MTs</option>
      <option value="MA">MA</option>
    </select>
  </div>

  <!-- 📱 Kartu per Santri -->
  <div id="daftarSantri" class="d-flex flex-column gap-3">
    <?php foreach ($saku as $row): ?>
      <div class="card shadow-sm p-3 santriCard">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="mb-1 nama"><?= esc($row['nama']) ?></h6>
            <small class="text-muted jenjang"><?= esc($row['jenjang']) ?></small>
          </div>
          <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id'] ?>">
            <i class="bi bi-eye me-2"></i>Lihat Mutasi
          </button>
        </div>
        <div class="mt-2">
            <?php $sisa = $row['total_in'] - $row['total_out']; ?>
  <div><strong>Sisa Saldo:</strong> Rp<?= number_format($sisa) ?></div>
          <div><strong>Total Debit:</strong> Rp<?= number_format($row['total_out']) ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- 📦 Modal Detail -->
  <?php foreach ($saku as $row): ?>
    <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id'] ?>" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title" id="modalLabel<?= $row['id'] ?>">Detail Saku - <?= esc($row['nama']) ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            <dl class="row">
              <dt class="col-sm-5">Nama</dt>
              <dd class="col-sm-7"><?= esc($row['nama']) ?></dd>

              <dt class="col-sm-5">Jenjang</dt>
              <dd class="col-sm-7"><?= esc($row['jenjang']) ?></dd>

<?php $sisa = $row['total_in'] - $row['total_out']; ?>

<!-- 🌟 Sisa Saldo Paling Atas -->
<div class="text-center mb-4">
  <div class="fw-bold text-white bg-primary rounded-pill py-2 px-4 d-inline-block shadow">
    💰 Sisa Saldo: Rp<?= number_format($sisa) ?>
  </div>
</div>

<div class="row">
  <!-- 🟢 Bagian Saldo -->
  <div class="col-md-6">
    <h6 class="mb-3 text-success"><i class="bi bi-arrow-down-circle me-1"></i>Saldo Masuk</h6>
    <dl class="row">
      <?php for ($i = 1; $i <= 5; $i++): ?>
        <?php if ($row['saldo_'.$i] > 0): ?>
          <dt class="col-sm-5">Saldo Masuk <?= $i ?></dt>
          <dd class="col-sm-7">Rp<?= number_format($row['saldo_'.$i]) ?></dd>
        <?php endif; ?>
      <?php endfor; ?>
      <dt class="col-sm-5">Total Masuk</dt>
      <dd class="col-sm-7 fw-bold text-success">Rp<?= number_format($row['total_in']) ?></dd>
    </dl>
  </div>

  <!-- 🔴 Bagian Debit -->
  <div class="col-md-6">
    <h6 class="mb-3 text-danger"><i class="bi bi-arrow-up-circle me-1"></i>Pengeluaran & Keterangan</h6>
    <dl class="row">
      <?php for ($i = 1; $i <= 15; $i++): ?>
        <?php if ($row['debit_'.$i] > 0): ?>
          <dt class="col-sm-5">Keluar <?= $i ?></dt>
          <dd class="col-sm-7">Rp<?= number_format($row['debit_'.$i]) ?></dd>
          <dt class="col-sm-5">Keterangan <?= $i ?></dt>
          <dd class="col-sm-7"><?= esc($row['ket_'.$i]) ?></dd>
        <?php endif; ?>
      <?php endfor; ?>
      <dt class="col-sm-5">Total Debit</dt>
      <dd class="col-sm-7 fw-bold text-danger">Rp<?= number_format($row['total_out']) ?></dd>
    </dl>
  </div>
</div>

            </dl>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

  <!-- ✅ Script Sistaff -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.js"></script>

  <!-- 🔍 Filter Script -->
<script>
  document.getElementById('searchNama').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.santriCard').forEach(card => {
      const nama = card.querySelector('.nama').textContent.toLowerCase();
      card.style.display = nama.includes(keyword) ? '' : 'none';
    });
  });

  document.getElementById('filterJenjang').addEventListener('change', function () {
    const filter = this.value;
    document.querySelectorAll('.santriCard').forEach(card => {
      const jenjang = card.querySelector('.jenjang').textContent;
      card.style.display = (filter === '' || jenjang === filter) ? '' : 'none';
    });
  });
</script>
</body>
</html>
