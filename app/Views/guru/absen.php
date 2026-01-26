<?= $this->extend('template_musrif'); ?>
<?= $this->section('konten'); ?>

<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header bg-success text-white">
      <h5 class="mb-0">Absensi Kehadiran Guru Kelas</h5>
    </div>
    <div class="card-body">

      <p class="mb-1"><strong>Petugas:</strong> <?= 'esc($namaMusrif);' ?></p>
      <p class="mb-3"><strong>Hari / Tanggal:</strong> <span id="tanggal-hari-ini"></span></p>

      <form action="<?= base_url('absensi/save'); ?>" method="post">
        <div class="mb-3">
          <label for="jam_ke" class="form-label">Jam ke-</label>
          <select class="form-select" id="jam_ke" name="jam_ke" required>
            <option value="">-- Pilih Jam --</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="malam">Malam</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="kelas" class="form-label">Kelas</label>
          <select class="form-select" id="kelas" name="kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <option>7A</option>
            <option>7B</option>
            <option>8A</option>
            <option>8B</option>
            <option>9A</option>
            <option>9B</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="nama" class="form-label">Nama Guru</label>
          <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <div class="mb-3">
          <label for="kehadiran" class="form-label">Kehadiran</label>
          <select class="form-select" id="kehadiran" name="kehadiran" required>
            <option value="">-- Pilih Status Kehadiran --</option>
            <option value="tepat waktu">Tepat Waktu</option>
            <option value="terlambat">Terlambat</option>
            <option value="tidak hadir">Tidak Hadir</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="kerapian" class="form-label">Kerapian</label>
          <select class="form-select" id="kerapian" name="kerapian" required>
            <option value="">-- Pilih Kerapian --</option>
            <option value="seragam sesuai">Seragam Sesuai</option>
            <option value="seragam tidak sesuai">Seragam Tidak Sesuai</option>
            <option value="tidak berseragam">Tidak Berseragam</option>
          </select>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">Simpan Absensi</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  // Format hari dan tanggal Indonesia
  document.addEventListener("DOMContentLoaded", function () {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const today = new Date().toLocaleDateString('id-ID', options);
    document.getElementById("tanggal-hari-ini").textContent = today;
  });
</script>

<?= $this->endSection(); ?>
