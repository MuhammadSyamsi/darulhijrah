<?php
if (in_groups('superadmin')) {
    echo $this->extend('template');
} else {
    echo $this->extend('template_musrif');
}
?>

<?= $this->section('konten') ?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="mb-3">Form Absensi Kehadiran Guru</h5>

          <!-- Pilih Tanggal Absensi -->
          <form id="formTanggal" class="row g-3 mb-4" method="get" action="<?= base_url('validasi') ?>">
            <div class="col-md-4">
              <label class="form-label">Tanggal Absensi</label>
              <input type="date" name="tanggal" class="form-control"
                value="<?= esc($tanggal ?? date('Y-m-d')) ?>"
                <?= in_groups('superadmin') ? '' : 'readonly' ?>>
            </div>
            <?php if (in_groups('superadmin')): ?>
            <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
            <?php endif; ?>
          </form>

          <?php if (empty($jadwalHarian)): ?>
            <div class="alert alert-warning">Tidak ada jadwal pada tanggal ini.</div>
          <?php else: ?>
            <?php
            // Kelompokkan jadwal per jam_ke
            $groupedByJam = [];
            foreach ($jadwalHarian as $item) {
              $groupedByJam[$item['jam_ke']][] = $item;
            }
            $jamIndex = 0;
            ?>
            <div class="accordion" id="accordionJam">
              <?php foreach ($groupedByJam as $jamKe => $jadwals): ?>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingJam<?= $jamIndex ?>">
                    <button class="accordion-button collapsed" type="button"
                      data-bs-toggle="collapse" data-bs-target="#collapseJam<?= $jamIndex ?>">
                      Jam ke-<?= $jamKe ?>
                    </button>
                  </h2>
                  <div id="collapseJam<?= $jamIndex ?>" class="accordion-collapse collapse"
                    data-bs-parent="#accordionJam">
                    <div class="accordion-body">

                      <div class="accordion" id="accordionGuruJam<?= $jamIndex ?>">
                        <?php foreach ($jadwals as $i => $j): ?>
                          <div class="accordion-item">
                            <h2 class="accordion-header" id="headingGuru<?= $jamIndex ?>_<?= $i ?>">
                              <button class="accordion-button collapsed" type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapseGuru<?= $jamIndex ?>_<?= $i ?>">
                                Guru: <?= esc($j['nama_guru']) ?>
                                (<?= esc($j['nama_kelas']) ?> - <?= esc($j['nama_mapel']) ?>)
                              </button>
                            </h2>
                            <div id="collapseGuru<?= $jamIndex ?>_<?= $i ?>"
                              class="accordion-collapse collapse"
                              data-bs-parent="#accordionGuruJam<?= $jamIndex ?>">
                              <div class="accordion-body">
                                <form class="form-absensi" data-index="<?= $jamIndex ?>_<?= $i ?>"
                                  action="<?= base_url('validasi/simpan') ?>" method="post">
                                  
                                  <input type="hidden" name="data[0][jadwal_id]" value="<?= $j['id'] ?>">
                                  <input type="hidden" name="data[0][jam_ke]" value="<?= $j['jam_ke'] ?>">
                                  <input type="hidden" name="data[0][tanggal]" value="<?= esc($tanggal ?? date('Y-m-d')) ?>">

                                  <div class="row g-3">
                                    <div class="col-md-4">
                                      <label class="form-label">Status Kehadiran</label>
                                      <select name="data[0][status_hadir]" class="form-select" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="tepat waktu">Tepat Waktu</option>
                                        <option value="terlambat">Terlambat</option>
                                        <option value="tidak hadir">Tidak Hadir</option>
                                      </select>
                                    </div>

                                    <div class="col-md-4">
                                      <label class="form-label">Kerapian</label><br>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                          name="data[0][kerapian]" value="1" required>
                                        <label class="form-check-label">Rapi</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                          name="data[0][kerapian]" value="0">
                                        <label class="form-check-label">Tidak</label>
                                      </div>
                                    </div>

                                    <div class="col-md-4">
                                      <label class="form-label">Seragam Sesuai</label><br>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                          name="data[0][seragam_sesuai]" value="1" required>
                                        <label class="form-check-label">Ya</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                          name="data[0][seragam_sesuai]" value="0">
                                        <label class="form-check-label">Tidak</label>
                                      </div>
                                    </div>

                                    <div class="col-12">
                                      <label class="form-label">Catatan</label>
                                      <textarea name="data[0][catatan]" class="form-control" rows="2"></textarea>
                                    </div>

                                    <div class="col-12 text-end">
                                      <button type="submit" class="btn btn-success">
                                        <?= in_groups('superadmin') ? 'Simpan / Update Absensi' : 'Simpan Absensi' ?>
                                      </button>
                                      <span class="text-success fw-bold ms-3 status-saved d-none">Tersimpan ✅</span>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>

                    </div>
                  </div>
                </div>
                <?php $jamIndex++; ?>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.form-absensi').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(res => {
        if (!res.ok) throw new Error("Gagal simpan");
        return res.text();
      })
      .then(() => {
        const index = form.getAttribute('data-index');
        const collapse = document.getElementById('collapseGuru' + index);
        const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapse);
        bsCollapse.hide();

        <?php if (!in_groups('superadmin')): ?>
        // Kunci form hanya jika bukan superadmin
        form.querySelectorAll('input, select, textarea, button').forEach(el => {
          el.disabled = true;
        });
        <?php endif; ?>

        form.querySelector('.status-saved').classList.remove('d-none');
      })
      .catch(err => {
        alert('Terjadi kesalahan saat menyimpan: ' + err.message);
      });
    });
  });
</script>

<?= $this->endSection() ?>