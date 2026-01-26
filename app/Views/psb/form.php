<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Lengkapi Formulir Santri Baru</h3>
            <form action="fullform" method="post">
              <?= csrf_field(); ?>
              <?php foreach ($datadiri as $data) : ?>
                <div class="row">
                  <div class="mb-1 col-lg-12">
                    <input type="hidden" class="form-control" id="id" name="id" value="<?= $data['id']; ?>" />
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama']; ?>" />
                    <label for="nisn" class="form-label">NISN</label>
                    <input type="text" class="form-control" id="nisn" name="nisn" value="<?= $data['nisn']; ?>" />
                    <?= $validation->listErrors(); ?>
                  </div>
                  <div class="mb-0 col-lg-3">
                    <label for="tanggal" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="ttl" name="ttl" value="<?= $data['tanggallahir'] ?>" />
                  </div>
                  <div class="mb-0 col-lg-9">
                    <label for="tanggal" class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" id="tl" name="tl" value="<?= $data['tempatlahir'] ?>" />
                  </div>
                  <div class="mb-0 col-lg-12">
                    <label for="asalsekolah" class="form-label">Asal Sekolah</label>
                    <input type="text" class="form-control" id="asalsekolah" name="asalsekolah" value="<?= $data['asalsekolah']; ?>">
                    <label for="tahunmasuk" class="form-label">Tahun Masuk</label>
                    <input type="text" class="form-control" id="tahunmasuk" name="tahunmasuk" value="<?= $data['tahunmasuk']; ?>">
                    <label for="ayah" class="form-label">Nama Ayah</label>
                    <input type="text" class="form-control" id="ayah" name="ayah" value="<?= $data['ayah']; ?>">
                    <label for="pekerjaanayah" class="form-label">Pekerjaan Ayah</label>
                    <input type="text" class="form-control" id="pekerjaanayah" name="pekerjaanayah" value="<?= $data['pekerjaanayah']; ?>">
                    <label for="alamatayah" class="form-label">Alamat Ayah</label>
                    <input type="text" class="form-control" id="alamatayah" name="alamatayah" value="<?= $data['alamatayah']; ?>">
                    <label for="kontak1" class="form-label">Nomor HP Ayah</label>
                    <input type="text" class="form-control" id="kontak1" name="kontak1" value="<?= $data['kontak1']; ?>">
                    <label for="ibu" class="form-label">Nama Ibu</label>
                    <input type="text" class="form-control" id="ibu" name="ibu" value="<?= $data['ibu']; ?>">
                    <label for="pekerjaanibu" class="form-label">Pekerjaan Ibu</label>
                    <input type="text" class="form-control" id="pekerjaanibu" name="pekerjaanibu" value="<?= $data['pekerjaanibu']; ?>">
                    <label for="alamatibu" class="form-label">Alamat Ibu</label>
                    <input type="text" class="form-control" id="alamatibu" name="alamatibu" value="<?= $data['alamatibu']; ?>">
                    <label for="kontak2" class="form-label">Nomor HP Ibu</label>
                    <input type="text" class="form-control" id="kontak2" name="kontak2" value="<?= $data['kontak2']; ?>">
                    <label for="berkas" class="form-label">Berkas</label>
                    <select class="form-select" id="berkas" name="berkas">
                      <option value="Belum Lengkap">Belum Lengkap</option>
                      <option value="Lengkap">Lengkap</option>
                    </select>
                  </div>
                  <div class="row">
                    <div class="mb-1 col-lg-3">
                      <button type="submit" class="form-control btn btn-dark mt-4">Kirim Formulir</button>
                    </div>
                  </div>
                <?php endforeach; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>