<form action="<?= site_url('guru/save') ?>" method="post">
  <?= csrf_field() ?>
  <div class="mb-2">
    <label class="form-label">Nama</label>
    <input type="text" name="nama" class="form-control form-control-sm" required>
  </div>
  <div class="mb-2">
    <label class="form-label">NIP</label>
    <input type="text" name="nip" class="form-control form-control-sm">
  </div>
  <div class="mb-2">
    <label class="form-label">Mapel</label>
    <input type="text" name="mapel" class="form-control form-control-sm">
  </div>
  <div class="mb-2">
    <label class="form-label">Jabatan</label>
    <input type="text" name="jabatan" class="form-control form-control-sm">
  </div>
  <div class="mb-2">
    <label class="form-label">Walas</label>
    <input type="text" name="kelas" class="form-control form-control-sm">
  </div>
  <div class="mb-2">
    <label class="form-label">Pendidikan Akhir</label>
    <input type="text" name="pendidikan_akhir" class="form-control form-control-sm">
  </div>
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-success btn-sm px-3">Simpan</button>
  </div>
</form>
