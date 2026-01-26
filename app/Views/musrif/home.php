<?= $this->extend('template_musrif') ;?>
<?= $this->section('konten');?>
<div class="container mt-5">
<h4 class="mb-4">Daftar Check-in Santri</h4>

<div class="row mb-4">
  <div class="col-md-6">
    <div class="card border-success shadow-sm">
      <div class="card-body">
        <h6 class="card-title text-success">Total Saku MTs</h6>
        <h4 class="fw-bold">Rp <?= number_format($mts['sa'] ?? 0, 0, ',', '.') ?></h4>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card border-primary shadow-sm">
      <div class="card-body">
        <h6 class="card-title text-primary">Total Saku MA</h6>
        <h4 class="fw-bold">Rp <?= number_format($ma['sa'] ?? 0, 0, ',', '.') ?></h4>
      </div>
    </div>
  </div>
</div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Saku</th>
                <th>HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="santriTable">
            <?php foreach ($santri as $row): ?>
            <tr>
                <td><?= esc($row['nama']) ?></td>
                <td><?= esc($row['kelas']) ?></td>
                <td><?= esc(format_rupiah($row['saku'])) ?></td>
                <td><?= esc($row['hp']) ?></td>
                <td>
                    <!-- Button to open modal -->
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['nisn'] ?>">Edit</button>
                </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['nisn'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?= $row['nisn'] ?>" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <form method="post" action="<?= base_url('check') ?>">
                    <input type="hidden" name="id" value="<?= $row['nisn'] ?>">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?= $row['nisn'] ?>"><?= esc($row['nama']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                              <input type="text" name="nama" hidden class="form-control" value="<?= esc($row['nama']) ?>" required>
                              <input type="text" name="kelas" hidden class="form-control" value="<?= esc($row['kelas']) ?>" required>
                          <div class="form-group">
                              <label>Saku</label>
                              <input type="number" name="saku" class="form-control" value="<?= esc($row['saku']) ?>" required>
                          </div>
                        <div class="form-group">
                            <label>No. HP</label>
                            <select name="hp" class="form-control" required>
                                <option value="nitip" <?= $row['hp'] === 'nitip' ? 'selected' : '' ?>>Nitip</option>
                                <option value="tidak nitip" <?= $row['hp'] === 'tidak nitip' ? 'selected' : '' ?>>Tidak Nitip</option>
                            </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    </table>
    
<!-- Fixed Bottom Search Bar -->
<div class="position-fixed w-100 px-3 pb-3" style="bottom: 0; left: 0; z-index: 1030; background: rgba(255,255,255,0.9); backdrop-filter: blur(4px);">
  <div class="input-group shadow">
    <input type="text" id="searchInput" class="form-control rounded-pill border border-success px-4 py-2" placeholder="Cari santri...">
    <div class="input-group-append position-absolute" style="right: 20px; top: 50%; transform: translateY(-50%);">
      <i class="bi bi-search text-success"></i>
    </div>
  </div>
</div>
    
</div>

<script>
function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

const searchInput = document.getElementById('searchInput');

const doSearch = async () => {
    const keyword = searchInput.value.trim();
    if (!keyword) return;

    try {
        const res = await fetch(`<?= base_url('home/search?q=') ?>` + encodeURIComponent(keyword));
        const data = await res.json();

        const tbody = document.getElementById('santriTable');
        tbody.innerHTML = '';

        // Bersihkan modal sebelumnya
        document.querySelectorAll('.modal').forEach(modal => modal.remove());

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>';
            return;
        }

        data.forEach(row => {
            const modalId = `editModal${row.nisn}`;

            // Tabel
            tbody.innerHTML += `
                <tr>
                    <td>${row.nama}</td>
                    <td>${row.kelas}</td>
                    <td>${formatRupiah(row.saku)}</td>
                    <td>${row.hp}</td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#${modalId}">Edit</button>
                    </td>
                </tr>
            `;

            // Modal
            document.body.insertAdjacentHTML('beforeend', `
            <div class="modal fade" id="${modalId}" tabindex="-1" role="dialog" aria-labelledby="label${modalId}" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="<?= base_url('check') ?>">
                        <input type="hidden" name="id" value="${row.nisn}">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="label${modalId}"><?= esc($row['nama']) ?></h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Tutup">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                    <input type="text" hidden name="nama" class="form-control" value="${row.nama}" required>
                                    <input type="text" hidden name="kelas" class="form-control" value="${row.kelas}" required>
                                <div class="form-group">
                                    <label>Saku</label>
                                    <input type="number" name="saku" class="form-control" value="${row.saku}" required>
                                </div>
                            <div class="form-group">
                                <label>No. HP</label>
                                <select name="hp" class="form-control" required>
                                    <option value="nitip" <?= $row['hp'] === 'nitip' ? 'selected' : '' ?>>Nitip</option>
                                    <option value="tidak nitip" <?= $row['hp'] === 'tidak nitip' ? 'selected' : '' ?>>Tidak Nitip</option>
                                </select>
                            </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            `);
        });

    } catch (err) {
        console.error('Gagal fetch:', err);
    }
};

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(angka);
}

searchInput.addEventListener('input', debounce(doSearch, 500));
</script>

<?= $this->endSection();?>
