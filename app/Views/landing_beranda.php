<?php
if (session()->get('isLoggedIn')) {
    header('Location: /beranda');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda Sistaff</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .hero {
      background: #1B4332;
      color: white;
      padding: 60px 0;
      text-align: center;
    }
    .feature-icon {
      font-size: 2rem;
      color: #1E3A8A;
    }
  </style>
</head>
<body>

  <!-- Hero Section: Judul dan tombol navigasi awal -->
  <section class="hero" data-aos="fade-up">
    <div class="container">
      <h1 class="display-4">Sistaff</h1>
      <p class="lead">Sistem Informasi Staff Yayasan – Transparan. Terstruktur. Terdigitalisasi.</p>
      <a href="#fitur" class="btn btn-light mt-3">Lihat Fitur</a>
      <a href="/login" class="btn btn-outline-light mt-3">Login Staff</a>
    </div>
  </section>

  <!-- Alasan menggunakan Sistaff -->
  <section class="py-5 bg-white" data-aos="fade-up">
    <div class="container">
      <h2 class="mb-4 text-center">Kenapa Sistaff?</h2>
      <div class="row text-center">
        <!-- Setiap kolom menampilkan satu masalah dan solusi Sistaff -->
        <div class="col-md-3 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <p>📂 Administrasi amburadul</p>
              <p>✅ Sistaff merapikan dan menyatukan semua data</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <p>🕵️ Tidak ada evaluasi kinerja</p>
              <p>✅ Penilaian berbasis indikator</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <p>💸 Keuangan tidak transparan</p>
              <p>✅ Laporan real-time dan ekspor Excel</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card h-100">
            <div class="card-body">
              <p>📊 Data tersebar</p>
              <p>✅ Terpusat dalam satu sistem</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Fitur Unggulan Sistaff -->
  <section class="py-5 bg-light" id="fitur" data-aos="fade-up">
    <div class="container">
      <h2 class="mb-4 text-center">Fitur Unggulan</h2>
      <div class="row text-center">
        <!-- Setiap kolom menampilkan ikon dan judul fitur -->
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">👨‍🎓</div>
          <h5>Manajemen Santri</h5>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">🧾</div>
          <h5>Pembayaran & Tunggakan</h5>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">📚</div>
          <h5>Absensi & Kelas</h5>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">🧑‍🏫</div>
          <h5>Penilaian Guru / Musyrif</h5>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">📊</div>
          <h5>Dashboard Laporan</h5>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-icon mb-2">🗕</div>
          <h5>Ekspor Excel</h5>
        </div>
      </div>
    </div>
  </section>

  <!-- Panduan Penggunaan dengan Accordion -->
  <section class="py-5 bg-white" data-aos="fade-up">
    <div class="container">
      <h2 class="mb-4 text-center">Panduan Penggunaan</h2>
      <div class="accordion" id="panduanAccordion">
        <!-- Item pertama accordion -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
              1. Cara Login
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#panduanAccordion">
            <div class="accordion-body">
              Gunakan username dan password Anda untuk masuk ke halaman staff.
            </div>
          </div>
        </div>
        <!-- Item kedua accordion -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
              2. Tambah Data Santri
            </button>
          </h2>
          <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#panduanAccordion">
            <div class="accordion-body">
              Masuk ke menu "Santri" lalu klik tombol "Tambah" dan isi formulir sesuai data santri.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer bawah -->
  <footer class="bg-dark text-white text-center py-3">
    <div>Sistaff v1.0 – Yayasan Darul Hijrah Salam &copy; 2025</div>
    <a href="/login" class="text-light">Login Staff</a>
  </footer>

  <!-- Script Bootstrap dan animasi AOS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init();</script>
</body>
</html>
