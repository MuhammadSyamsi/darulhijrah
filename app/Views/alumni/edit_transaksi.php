<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php foreach ($edit as $c) : ?>
                        <h4 class="text-secondary font-weight-bold mb-3">Edit Transaksi Alumni</h4>
                        <h5><?= $c['nama']; ?></h5>

                        <form action="edittransalumni" method="post">
                            <?= csrf_field(); ?>

                            <!-- Input Utama -->
                            <div class="row mb-3">
                                <div class="col-md-4 mt-3">
                                    <label for="saldomasuk" class="form-label">Saldo Masuk</label>
                                    <input type="text" class="form-control" id="saldomasuk" name="saldomasuk" value="<?= $c['saldomasuk']; ?>" required>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $c['tanggal'] ?>" required>
                                </div>

                                <div class="col-md-4 mt-3">
                                    <label for="rekening" class="form-label">Rekening</label>
                                    <select class="form-select" id="rekening" name="rekening">
                                        <option value="<?= $c['rekening'] ?>" selected><?= $c['rekening']; ?></option>
                                        <option value="Muamalat Salam">Muamalat Salam</option>
                                        <option value="Jatim Syariah">Jatim Syariah</option>
                                        <option value="BSI">BSI</option>
                                        <option value="Uang Saku">Uang Saku</option>
                                        <option value="Muamalat Yatim">Muamalat Yatim</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="lain-lain">Lain-lain</option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $c['keterangan'] ?>" required>
                                    <input type="hidden" name="nama" value="<?= $c['nama']; ?>" />
                                    <input type="hidden" name="idtrans" value="<?= $c['idtrans']; ?>" />
                                    <input type="hidden" name="nisn" value="<?= $c['nisn']; ?>" />
                                    <input type="hidden" name="kelas" value="<?= $c['kelas']; ?>" />
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Tunggakan -->
                        <hr/><div>
                            <h4 class="text-secondary font-weight-bold mb-3">Edit Tunggakan</h4>
                            <div class="row">
                                <?php foreach ($santri as $s) : ?>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Tunggakan Daftar Ulang</label>
                                        <input type="text" class="form-control" id="santridu" name="santridu" value="<?= $s['tunggakandu']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Tunggakan Lain-lain</label>
                                        <input type="text" class="form-control" id="santritl" name="santritl" value="<?= $s['tunggakantl']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Tunggakan SPP</label>
                                        <input type="text" class="form-control" id="santrispp" name="santrispp" value="<?= $s['tunggakanspp']; ?>" required />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Detail Pembayaran -->
                        <hr/><div>
                            <h4 class="text-secondary font-weight-bold mb-3">Detail Pembayaran</h4>
                            <div class="row">
                                <?php foreach ($detail as $d) : ?>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Bayar Daftar Ulang</label>
                                        <input type="text" class="form-control" id="du" name="du" value="<?= $d['daftarulang']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Bayar Tunggakan</label>
                                        <input type="text" class="form-control" id="tunggakan" name="tunggakan" value="<?= $d['tunggakan']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Bayar SPP</label>
                                        <input type="text" class="form-control" id="spp" name="spp" value="<?= $d['spp']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Uang Saku</label>
                                        <input type="text" class="form-control" id="uangsaku" name="uangsaku" value="<?= $d['uangsaku']; ?>" required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Infaq</label>
                                        <input type="text" class="form-control" id="infaq" name="infaq" value="<?= $d['infaq']; ?>" required required />
                                    </div>
                                    <div class="col-md-4 mt-2">
                                        <label class="form-label">Formulir</label>
                                        <input type="text" class="form-control" id="formulir" name="formulir" value="<?= $d['formulir']; ?>" required />
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Tombol -->
                        <div class="mt-4 d-flex justify-content-between">
                            <a href="./riwayat-pembayaran" class="btn btn-warning">Kembali</a>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>

                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const numericFields = [
      'saldomasuk', 'santridu', 'santrispp', 'santritl',
      'du', 'tunggakan', 'spp', 'uangsaku', 'infaq', 'formulir'
    ];

    numericFields.forEach(id => {
      const input = document.getElementById(id);
      if (input) {
        new Cleave(input, {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand'
        });
      }
    });

    document.querySelector('form').addEventListener('submit', function () {
      numericFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.value = input.value.replace(/\./g, '').replace(/,/g, '');
        }
      });
    });
  });
</script>

<?= $this->endSection(); ?>