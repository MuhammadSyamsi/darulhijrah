<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <h5 class="mb-3">🚚 Distribusi Seragam ke Santri</h5>

  <form action="<?= base_url('seragam/distribusi/simpan') ?>" method="post">
    <div class="row g-3">
      <div class="col-md-4">
        <select name="nisn" class="form-select" required>
          <option value="">Pilih Santri</option>
          <?php foreach ($belum_diberikan as $item): ?>
          <option value="<?= $item['nisn'] ?>">
            <?= $item['nama'] ?> - <?= $item['jenis_seragam'] ?> <?= $item['kategori'] ?> (<?= $item['ukuran'] ?>)
          </option>
          <?php endforeach ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" name="jenis_seragam" class="form-control" placeholder="Jenis" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="kategori" class="form-control" placeholder="Kategori" required>
      </div>
      <div class="col-md-2">
        <input type="text" name="ukuran" class="form-control" placeholder="Ukuran" required>
      </div>
      <div class="col-md-2">
        <button class="btn btn-success">Distribusikan</button>
      </div>
      <div class="col-12 mt-2">
        <textarea name="keterangan" class="form-control" placeholder="Catatan jika ada"></textarea>
      </div>
    </div>
  </form>
</div>

<?= $this->endSection() ?>
