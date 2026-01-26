<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Tambah Data Saku</h5>

          <form action="/saku/store" method="post">
            <div class="mb-3">
              <label for="nisn" class="form-label">NISN</label>
              <input type="text" class="form-control" name="nisn" required>
            </div>
            <div class="mb-3">
              <label for="nama" class="form-label">Nama</label>
              <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="mb-3">
              <label for="jenjang" class="form-label">Jenjang</label>
              <input type="text" class="form-control" name="jenjang" required>
            </div>
            <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
            <a href="/saku" class="btn btn-secondary">Kembali</a>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
