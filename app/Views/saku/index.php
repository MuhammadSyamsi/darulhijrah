<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">

          <h5 class="mb-3">Data Saku Santri</h5>
          
          <div class="mb-3">
  <button id="aksiMassal" class="btn btn-warning">
    <i class="bi bi-pencil-square me-2"></i>Aksi Massal: Voucher Belanja
  </button>
</div>

          <!-- 🔍 Pencarian dan Filter -->
          <div class="row mb-3">
            <div class="col-md-6 mb-2">
              <input type="text" id="searchNama" class="form-control" placeholder="Cari nama santri...">
            </div>
            <div class="col-md-4 mb-2">
              <select id="filterJenjang" class="form-select">
                <option value="">-- Semua Jenjang --</option>
                <option value="MTs">MTs</option>
                <option value="MA">MA</option>
              </select>
            </div>
          </div>

          <!-- 💰 Rekap -->
          <h5 class="mb-3">Rekap Keuangan per Jenjang</h5>

          <div class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead class="table-light">
                <tr>
                  <th>Jenjang</th>
                  <th>Total Saldo</th>
                  <th>Total Debit</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rekapJenjang as $r): ?>
                  <tr>
                    <td><?= esc($r['jenjang']) ?></td>
                    <td><?= number_format($r['total_saldo']) ?></td>
                    <td><?= number_format($r['total_debit']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- 📋 Tabel Data -->
          <div class="table-responsive">
            <table id="tabelSaku" class="table table-striped table-bordered table-hover">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>NISN</th>
                  <th>Nama</th>
                  <th>Jenjang</th>
                  <th>Saldo</th>
                  <th>Debit</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($saku as $i => $row): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($row['nisn']) ?></td>
                    <td class="nama"><?= esc($row['nama']) ?></td>
                    <td class="jenjang"><?= esc($row['jenjang']) ?></td>
                    <td><?= number_format($row['total_in']) ?></td>
                    <td><?= number_format($row['total_out']) ?></td>
                    <td>
                      <a href="/saku/<?= $row['id'] ?>" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                      <a href="/saku/edit/<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                      <a href="/saku/delete/<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- script sistaff -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- baru kemudian -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- 🔧 JavaScript Filter -->
<script>
  document.getElementById('searchNama').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tabelSaku tbody tr');

    rows.forEach(row => {
      const nama = row.querySelector('.nama').textContent.toLowerCase();
      row.style.display = nama.includes(keyword) ? '' : 'none';
    });
  });

  document.getElementById('filterJenjang').addEventListener('change', function () {
    const jenjangFilter = this.value;
    const rows = document.querySelectorAll('#tabelSaku tbody tr');

    rows.forEach(row => {
      const jenjang = row.querySelector('.jenjang').textContent;
      row.style.display = (jenjangFilter === '' || jenjang === jenjangFilter) ? '' : 'none';
    });
  });
</script>
<script>
  $('#aksiMassal').on('click', function () {
    if (confirm('Terapkan voucher belanja ke semua santri?')) {
      $.ajax({
        url: '<?= base_url("saku/aksi-massal") ?>',
        method: 'POST',
        data: {},
        success: function (res) {
          alert('Berhasil diterapkan!');
          location.reload();
        },
        error: function () {
          alert('Gagal menerapkan aksi massal.');
        }
      });
    }
  });
</script>

<?= $this->endSection() ?>
