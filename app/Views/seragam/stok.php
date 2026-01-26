<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <h5 class="mb-3">📦 Stok Seragam</h5>

  <form action="<?= base_url('seragam/stok/update') ?>" method="post" class="row g-3 mb-4">
    <div class="col-md-2">
      <select name="jenis_seragam" class="form-select" required>
        <option value="baju">Baju</option>
        <option value="celana">Celana</option>
      </select>
    </div>
    <div class="col-md-2">
      <input type="text" name="kategori" class="form-control" placeholder="Kategori" required>
    </div>
    <div class="col-md-2">
      <select name="ukuran" class="form-select" required>
        <option>S</option><option>M</option><option>L</option>
        <option>XL</option><option>XXL</option><option>XXXL</option>
      </select>
    </div>
    <div class="col-md-2">
      <input type="number" name="jumlah" class="form-control" placeholder="Jumlah" required>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Simpan / Tambah</button>
    </div>
  </form>

  <table class="table table-bordered table-sm">
    <thead class="table-light">
      <tr>
        <th>Jenis</th>
        <th>Kategori</th>
        <th>Ukuran</th>
        <th>Jumlah</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($stok as $s): ?>
      <tr>
        <td><?= ucfirst($s['jenis_seragam']) ?></td>
        <td><?= ucfirst($s['kategori']) ?></td>
        <td><?= $s['ukuran'] ?></td>
        <td><?= $s['jumlah'] ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<?= $this->endSection() ?>
