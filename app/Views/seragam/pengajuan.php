<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <h5 class="mb-3">📝 Pengajuan Seragam</h5>

  <form action="<?= base_url('seragam/pengajuan/simpan') ?>" method="post">
    <div class="mb-3">
      <label for="catatan" class="form-label">Catatan</label>
      <textarea name="catatan" class="form-control" rows="2"></textarea>
    </div>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Jenis</th>
          <th>Kategori</th>
          <th>Ukuran</th>
          <th>Jumlah</th>
        </tr>
      </thead>
      <tbody id="detail-body">
        <tr>
          <td><select name="detail[0][jenis_seragam]" class="form-select" required>
              <option value="baju">Baju</option>
              <option value="celana">Celana</option>
            </select></td>
          <td><input type="text" name="detail[0][kategori]" class="form-control" required></td>
          <td><select name="detail[0][ukuran]" class="form-select" required>
              <option>S</option><option>M</option><option>L</option>
              <option>XL</option><option>XXL</option><option>XXXL</option>
            </select></td>
          <td><input type="number" name="detail[0][jumlah]" class="form-control" required></td>
        </tr>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Ajukan</button>
  </form>
</div>

<script>
  // Optional: tambahkan tombol tambah baris jika perlu
</script>

<?= $this->endSection() ?>
