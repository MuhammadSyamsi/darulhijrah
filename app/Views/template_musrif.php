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
        .mobile-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 1030;
            background-color: #fff;
            border-top: 1px solid #dee2e6;
        }

        .mobile-nav .nav-link {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .mobile-nav .nav-link.active {
            color: #198754;
        }

        .app-header .nav-link {
            color: #212529;
            font-weight: 500;
        }

        #dropdownMenu {
            min-width: 200px;
        }

        .fab {
            position: fixed;
            right: 20px;
            z-index: 1050;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fab-main {
            bottom: 90px;
        }

        .fab-secondary {
            bottom: 150px;
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
  </style>

</head>

<body>
    <div class="page-wrapper" id="main-wrapper">
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

        <div class="body-wrapper mb-5">
            <?= $this->renderSection('konten'); ?>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.js"></script>
</body>

</html>
