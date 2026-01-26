<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Detail Saku Santri</h5>

          <table class="table table-bordered">
            <tr><th>NISN</th><td><?= esc($saku['nisn']) ?></td></tr>
            <tr><th>Nama</th><td><?= esc($saku['nama']) ?></td></tr>
            <tr><th>Jenjang</th><td><?= esc($saku['jenjang']) ?></td></tr>
            <tr><th>Saldo 1</th><td><?= number_format($saku['saldo_1']) ?></td></tr>
            <tr><th>Debit 1</th><td><?= number_format($saku['debit_1']) ?></td></tr>
            <tr><th>Keterangan 1</th><td><?= esc($saku['ket_1']) ?></td></tr>
          </table>

          <a href="/saku" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
