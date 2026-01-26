<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>sistaff</title>
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
            <div class="container-fluid">
                <a class="navbar-brand text-success fw-bold" href="<?= base_url('kantin') ?>">Kantin</a>
<!-- Tombol Toggle Mode -->
<button id="darkToggle" class="btn btn-sm btn-outline-secondary" title="Ganti Mode">
  <i class="bi bi-moon-stars"></i>
</button>

                <!-- Mobile Menu -->
            </div>
        </nav>

        <!-- Body Wrapper -->
        <div class="container-fluid py-4 mb-5">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-12">
                    <?= $this->renderSection('konten'); ?>
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
    
    <script>
      const body = document.body;
      const toggle = document.getElementById('darkToggle');
    
      // Cek preferensi awal
      const userPref = localStorage.getItem('theme');
      const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
      if (userPref === 'dark' || (!userPref && systemPrefersDark)) {
        body.classList.add('dark-mode');
      }
    
      toggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const mode = body.classList.contains('dark-mode') ? 'dark' : 'light';
        localStorage.setItem('theme', mode);
      });
    </script>

</body>

</html>
