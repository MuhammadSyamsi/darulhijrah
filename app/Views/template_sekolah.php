<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= $title ?></title>
      <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <style>
         .app-header .nav-link {
         color: #212529;
         font-weight: 500;
         }
         #dropdownMenu {
         min-width: 200px;
         }
         body {
         background-color: #f8f9fa; /* Abu-abu terang (Bootstrap default) */
         }
         /* Warna hover sidebar nav */
         .sidebar-nav .nav-link {
         border-radius: 0.375rem;
         }
         .sidebar-nav .nav-link:hover {
         background-color: #e9f5ee; /* Contoh hijau muda */
         color: #198754; /* Hijau Bootstrap */
         border-radius: 0.375rem;
         }
         .sidebar-nav .nav-link.active {
         background-color: #198754;
         color: #fff;
         font-weight: 500;
         }
         body.dark-mode {
         background-color: #121212 !important;
         color: #e0e0e0 !important;
         }
         body.dark-mode .navbar,
         body.dark-mode .offcanvas,
         body.dark-mode .dropdown-menu,
         body.dark-mode .footer {
         background-color: #1a1a1a !important;
         color: #ffffff !important;
         }
         body.dark-mode .card {
         background-color: #1e1e1e !important;
         border-color: #2c2c2c !important;
         }
         body.dark-mode .card-body {
         color: #e0e0e0 !important;
         }
         body.dark-mode .form-control,
         body.dark-mode .select2-container--default .select2-selection--single {
         background-color: #2a2a2a !important;
         color: #ffffff !important;
         border-color: #444 !important;
         }
         body.dark-mode .btn-outline-secondary {
         color: #ffffff;
         border-color: #cccccc;
         }
         body.dark-mode a {
         color: #90caf9 !important;
         }
         /* Select2 dropdown dark fix */
         body.dark-mode .select2-container--default .select2-results > .select2-results__options {
         background-color: #1e1e1e;
         color: #fff;
         }
      </style>
   </head>
   <body>
      <div class="page-wrapper" id="main-wrapper">
         <!-- Navbar -->
         <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
            <div class="container-fluid d-flex justify-content-between align-items-center">
               <a class="navbar-brand text-success fw-bold" href="<?= base_url('guru') ?>">SISTAFF</a>
               <div class="d-flex align-items-center gap-3">
                  <div class="text-end me-2">
                     <h5 class="text-primary my-0">Assalamu'alaikum</h5>
                     <p class="mb-0">Ustadz <?= ucfirst(user()->username ?? 'Nama') ?></p>
                  </div>
                  <div class="dropdown">
                     <button class="btn btn-outline-secondary rounded-3" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                     <i class="bi bi-gear"></i>
                     </button>
                     <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownMenuButton" style="min-width: 180px;">
                        <li>
                           <h6 class="dropdown-header">Menu</h6>
                        </li>
                        <li><a class="dropdown-item" href="<?= base_url('tentang') ?>"><i class="bi bi-info-circle me-2"></i>Tentang</a></li>
                        <li>
                           <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                     </ul>
                  </div>
               </div>
            </div>
         </nav>
         <!-- Body Wrapper -->
         <div class="container-fluid py-4 mb-5">
            <div class="row">
               <div class="col-lg-12">
                  <div class="row mx-2 py-4">
                     <div class="col-lg-12">
                        <?php $uri = service('uri')->getSegment(1); ?>
                        <div class="d-flex flex-wrap gap-3">
                           <a href="<?= base_url('guru') ?>" class="col-md-2 btn btn-lg <?= $uri == 'guru' ? 'btn-primary active' : 'btn-outline-primary' ?>">
                           <strong><i class="bi bi-person-badge me-1"></i> Data Guru</strong>
                           </a>
                           <a href="<?= base_url('mapel') ?>" class="col-md-3 btn btn-lg <?= $uri == 'mapel' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">
                           <strong><i class="bi bi-journal-bookmark me-1"></i> Data Mapel</strong>
                           </a>
                           <a href="<?= base_url('kelas') ?>" class="col-md-3 btn btn-lg <?= $uri == 'kelas' ? 'btn-warning active' : 'btn-outline-warning' ?>">
                           <strong><i class="bi bi-building me-1"></i> Data Kelas</strong>
                           </a>
                           <a href="<?= base_url('jadwal-pelajaran') ?>" class="col-md-3 btn btn-lg <?= $uri == 'jadwal-pelajaran' ? 'btn-success active' : 'btn-outline-success' ?>">
                           <strong><i class="bi bi-calendar2-week me-1"></i> Jadwal Pelajaran</strong>
                           </a>
                        </div>
                     </div>
                  </div>
                  <?= $this->renderSection("konten") ?>
               </div>
            </div>
         </div>
      </div>
      <!-- Script Libraries -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.js"></script>
   </body>
</html>
