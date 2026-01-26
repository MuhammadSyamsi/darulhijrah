<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="card">
  <div class="card-header">
    <h5>Daftar Santri yang Memiliki Laundry</h5>
  </div>
  <div class="card-body">
    <?php if (!empty($laundryList)) : ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Jenjang</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($laundryList as $row) : ?>
            <tr>
              <td><?= esc($row['nama']); ?></td>
              <td><?= esc($row['jenjang']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p class="text-muted">Tidak ada santri dengan keterangan laundry.</p>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection(); ?>