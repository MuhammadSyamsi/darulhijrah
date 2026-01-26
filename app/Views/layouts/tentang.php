<?= $this->extend('template'); ?>  
<?= $this->section('konten'); ?>

<style>
  .sistaff-header {
    background: linear-gradient(to right, #1B4332, #40916C);
    color: #fff;
    padding: 3rem 1.5rem;
    text-align: center;
    border-radius: 0 0 1.25rem 1.25rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
  }

  .sistaff-header h2 {
    font-weight: 700;
    font-size: 2.25rem;
    margin-bottom: 0.5rem;
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-color: #1B4332;
    padding: 1rem;
    border-radius: 50%;
    opacity: 0.9;
  }

  .carousel-indicators [data-bs-target] {
    background-color: #1B4332;
    width: 12px;
    height: 12px;
    border-radius: 50%;
  }

  .carousel-indicators .active {
    background-color: #F4C430;
  }

  .card {
    border-radius: 1rem;
    border: none;
  }

  .btn-success, .btn-primary {
    border-radius: 2rem;
    font-weight: 500;
  }

  .carousel-indicators {
    bottom: -2rem;
  }
</style>

<!-- Header -->
<div class="sistaff-header mb-4">
  <h2>Selamat Datang di <span style="color:#F4C430">sistaff</span></h2>
  <p class="mb-0">Sistem Administrasi Pondok dan Yayasan Berbasis Web</p>
</div>

<!-- Carousel Bootstrap 5 -->
<div id="sistaffCarousel" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#sistaffCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
    <button type="button" data-bs-target="#sistaffCarousel" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#sistaffCarousel" data-bs-slide-to="2"></button>
    <button type="button" data-bs-target="#sistaffCarousel" data-bs-slide-to="3"></button>
    <button type="button" data-bs-target="#sistaffCarousel" data-bs-slide-to="4"></button>
  </div>

  <div class="carousel-inner">

    <!-- Slide 1 -->
    <div class="carousel-item active">
      <div class="container py-4">
        <div class="card shadow p-4 text-center">
          <h4 class="mb-3">Tentang Aplikasi <strong>sistaff</strong></h4>
          <p><strong>sistaff</strong> adalah sistem berbasis web untuk manajemen administrasi pondok, yayasan, atau sekolah.</p>
          <p>Dikembangkan oleh <strong>Muchammad Samsi</strong>, admin Ma'had Tahfidz Darul Hijrah Pandaan sejak tahun <strong>2023</strong>.</p>
        </div>
      </div>
    </div>
 
    <!-- Slide 3 -->
    <div class="carousel-item">
      <div class="container py-4">
        <div class="card shadow p-4">
          <h4 class="text-center mb-3">Fitur dan Fungsi Utama</h4>
          <h5>1. Administrasi Keuangan</h5>
          <ul>
            <li>Kwitansi & laporan keuangan yayasan</li>
            <li>Program: beasiswa, mandiri, PSB</li>
          </ul>
          <h5 class="mt-3">2. Data Santri & Alumni</h5>
          <ul>
            <li>Tunggakan bulanan & tahunan</li>
            <li>Status alumni & histori pembayaran</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Slide 4 -->
    <div class="carousel-item">
      <div class="container py-4">
        <div class="card shadow p-4">
          <h4 class="text-center mb-3">Pengembangan Terbaru</h4>
          <ul>
            <li>Check-in penitipan HP & uang santri</li>
            <li>Checklist kehadiran & pelanggaran guru</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Slide 5 -->
    <div class="carousel-item">
      <div class="container py-4">
        <div class="card shadow p-4">
          <h4 class="text-center mb-3">Rencana & Kontak</h4>
          <ul>
            <li>Penilaian santri, asrama, dan pengajar</li>
            <li>Integrasi sistem internal yayasan</li>
          </ul>
          <hr>
          <p class="text-center mb-2">
            📞 <strong>Muchammad Samsi</strong><br>
            Ma’had Darul Hijrah Pandaan<br>
          </p>
          <div class="text-center">
            <a href="https://wa.me/6289520821215?text=Assalamu'alaikum%20pak%2C%20saya%20atas%20nama%20*Nama%20Lengkap*%20dari%20*Asal%20Lembaga*%20berminat%20membuat%20akun%20sistaff%20untuk%20kebutuhan%20..."
               target="_blank" class="btn btn-success btn-lg">
              Hubungi via WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Navigasi -->
  <button class="carousel-control-prev" type="button" data-bs-target="#sistaffCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Sebelumnya</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#sistaffCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Selanjutnya</span>
  </button>
</div>

<?= $this->endSection(); ?>
