<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Jadwal Harian</h5>
          <table class="table table-bordered table-sm align-middle">
            <thead class="table-light text-center">
              <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam Ke</th>
                <th>Guru</th>
                <th>Mapel</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jadwalHarian as $i => $j): ?>
                <tr>
                  <td><?= $i + 1 ?></td>
                  <td><?= esc($j['nama_kelas']) ?></td>
                  <td><?= esc($j['nama_hari']) ?></td>
                  <td class="text-center"><?= esc($j['jam_ke']) ?></td>
                  <td><?= esc($j['nama_guru']) ?></td>
                  <td><?= esc($j['nama_mapel']) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
