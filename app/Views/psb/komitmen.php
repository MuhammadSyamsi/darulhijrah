<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 d-flex align-items-stretch">
      <div class="card w-100">
        <div class="card-body p-4">
          <div class="mb-3">
            <h3 class="card-title fw-semibold">Komitmen Santri Baru <?= $datadiri['nama']; ?></h3>
            <p>Dengan ini menyatakan bahwa selaku orang tua telah membaca dan setuju dengan aturan dan pembiayaan pendidikan anak kami di Ma'had Tahfidz Darul Hijrah Salam Pandaan</p>
            <h5>Rincian Biaya Pendidikan</h5>
            <div class="table-responsive">
              <table class="table text-nowrap mb-0 align-middle">
                <tr>
                  <td>Infaq Gedung</td>
                  <td><input class="form-control" type="number" id="ig" name="ig" value="15000000" /></td>
                  <td>Syahriyah Perbulan</td>
                  <td><input class="form-control" type="number" id="spp2" name="spp2" value="1200000" /></td>
                  <td>Uang Kegiatan Tahuan</td>
                  <td><input class="form-control" type="number" id="uk" name="uk" value="1000000" /></td>
                </tr>
                <tr>
                  <td>Uang Buku</td>
                  <td><input class="form-control" type="number" id="ub" name="ub" value="1000000" /></td>
                  <td>Seragam</td>
                  <td><input class="form-control" type="number" id="sr" name="sr" value="1200000" /></td>
                  <td>Uang Sarpras</td>
                  <td><input class="form-control" type="number" id="us" name="us" value="1800000" /></td>
                </tr>
              </table>
              <button class="btn btn-primary m-1" onclick="calculate()">Setuju</button>
            </div>
            <form action="closing<?= $datadiri['id']; ?>" method="post">
              <?= csrf_field(); ?>
              <div class="row">
                <div class="mb-1 col-lg-6">
                  <label for="du" class="form-label">Daftar Ulang</label>
                  <input type="number" class="form-control" id="du" name="du" value="<?= format_rupiah($datadiri['daftarulang']); ?>" />
                </div>
                <div class="mb-1 col-lg-3">
                  <label for="spp" class="form-label">SPP</label>
                  <input type="number" class="form-control" id="spp" name="spp" value="<?= $datadiri['spp']; ?>" />
                </div>
                <div class="row">
                  <div class="mb-1 col-lg-3">
                    <button type="submit" class="form-control btn btn-primary m-1">Terima dan Setuju</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function calculate() {
    var nilai_ig = parseFloat(document.getElementById('ig').value);
    var nilai_spp2 = parseFloat(document.getElementById('spp2').value);
    var nilai_uk = parseFloat(document.getElementById('uk').value);
    var nilai_ub = parseFloat(document.getElementById('ub').value);
    var nilai_sr = parseFloat(document.getElementById('sr').value);
    var nilai_us = parseFloat(document.getElementById('us').value);

    var nilai_du = nilai_ig + nilai_spp2 + nilai_uk + nilai_ub + nilai_sr + nilai_us;

    document.getElementById('du').value = nilai_du;
    document.getElementById('spp').value = nilai_spp2;
  }
</script>

<?= $this->endSection(); ?>