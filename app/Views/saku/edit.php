<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Edit Data Saku</h5>

          <form action="/saku/update/<?= $saku['id'] ?>" method="post">
            <div class="mb-3">
              <label class="form-label">NISN</label>
              <input type="text" class="form-control" name="nisn" value="<?= esc($saku['nisn']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" name="nama" value="<?= esc($saku['nama']) ?>" required>
            </div>
<!-- Jenjang -->
<div class="mb-3">
  <label class="form-label">Jenjang</label>
  <input type="text" class="form-control" name="jenjang" value="<?= esc($saku['jenjang']) ?>" required>
</div>

<!-- Saldo 1 - 5 -->
<strong>Saldo Masuk</strong><hr>
<?php for ($i = 1; $i <= 5; $i++): ?>
  <div class="mb-3">
    <label class="form-label">Saldo <?= $i ?></label>
    <input type="number" class="form-control" name="saldo_<?= $i ?>" value="<?= esc($saku['saldo_' . $i]) ?>" required>
  </div>
<?php endfor; ?>

<!-- Debit 1 - 5 -->
<?php for ($i = 1; $i <= 15; $i++): ?>
<strong>Saldo Keluar</strong><hr>
  <div class="mb-3">
    <label class="form-label">Debit <?= $i ?></label>
    <input type="number" class="form-control" name="debit_<?= $i ?>" value="<?= esc($saku['debit_' . $i]) ?>" required>
    <label class="form-label">Keterangan <?= $i ?></label>
    <input type="text" class="form-control" name="ket_<?= $i ?>" value="<?= esc($saku['ket_' . $i]) ?>">
  </div>
<?php endfor; ?>

            <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Update</button>
            <a href="/saku" class="btn btn-secondary">Kembali</a>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
