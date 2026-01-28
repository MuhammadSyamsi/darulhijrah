<!doctype html>
<html lang="en" x-data="{ openSantri:false, openKeuangan:false, openPembayaran:false, openGuru:false }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISTAFF</title>
      <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
          rel="stylesheet" />

    <!-- Galaxy Tab A9 optimization -->
    <link rel="stylesheet" href="<?= base_url('assets/css/mobile-a9.css'); ?>">
    
    <!-- jQuery + Select2 (keperluan select Ajax) -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body class="bg-gray-100">

<!-- Top Navbar -->
<nav class="fixed top-0 left-0 right-0 bg-white shadow border-b z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

        <a href="<?= base_url() ?>" class="text-green-600 text-xl font-bold">
            SISTAFF
        </a>

        <div class="flex items-center gap-4">
            <div>
                <p class="text-blue-600 text-sm font-semibold">Assalamu'alaikum</p>
                <p class="text-sm">Ustadz <?= ucfirst(user()->username ?? 'Nama') ?></p>
            </div>

            <!-- Dropdown Settings -->
            <div x-data="{open:false}" class="relative">
                <button @click="open=!open"
                    class="p-2 border rounded-lg hover:bg-gray-100">
                    <span class="material-symbols-outlined w-6 h-6 text-gray-600">settings</span>
                </button>

                <div x-show="open" @click.away="open=false"
                     class="absolute right-0 mt-2 w-44 bg-white shadow-lg rounded-lg p-2 z-50">
                    <h6 class="px-3 py-2 text-gray-500 text-xs">Menu</h6>

                    <a href="<?= base_url('kantin') ?>" class="block px-3 py-2 text-sm hover:bg-gray-100">
                        Tools
                    </a>

                    <a href="<?= base_url('tentang') ?>" class="block px-3 py-2 text-sm hover:bg-gray-100">
                        Tentang
                    </a>

                    <hr class="my-1">

                    <a href="<?= base_url('logout') ?>"
                       class="block px-3 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- Wrapper -->
<div class="pt-20 pb-20 max-w-7xl mx-auto px-4">

    <div class="grid grid-cols-1 gap-4">

        <!-- Main Content -->
        <main class="col-span-1">
            <?= $this->renderSection('konten'); ?>
        </main>

    </div>
</div>



<!-- BOTTOM NAV (Mobile) -->
<nav class="fixed bottom-0 left-0 right-0 bg-white shadow-lg p-2 flex justify-around z-50">

    <!-- Santri -->
    <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="text-center">
            <span class="material-symbols-outlined w-6 h-6 mx-auto">group</span>
            <div class="text-xs">Santri</div>
        </button>

        <div x-show="open" @click.away="open=false"
             class="absolute bottom-12 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-xl w-40 p-2">

            <a class="block px-3 py-2 text-sm" href="<?= base_url('data-santri') ?>">Data Santri</a>
            <a class="block px-3 py-2 text-sm" href="<?= base_url('data-psb') ?>">Data PSB</a>

            <button class="block px-3 py-2 text-sm w-full text-left"
                    @click="$dispatch('open-santri-modal'); open=false">Lainnya</button>
        </div>
    </div>

    <!-- Keuangan -->
    <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="text-center">
            <span class="material-symbols-outlined w-6 h-6 mx-auto">account_balance_wallet</span>
            <div class="text-xs">Keuangan</div>
        </button>

        <div x-show="open" @click.away="open=false"
             class="absolute bottom-12 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-xl w-40 p-2">

            <a class="block px-3 py-2 text-sm" href="<?= base_url('beranda') ?>">Rekap</a>
            <a class="block px-3 py-2 text-sm" href="<?= base_url('laporan-pemasukan') ?>">Pemasukan</a>
            <a class="block px-3 py-2 text-sm" href="<?= base_url('claim') ?>">Pengeluaran</a>
        </div>
    </div>

    <!-- Pembayaran -->
    <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="text-center">
            <span class="material-symbols-outlined w-6 h-6 mx-auto">credit_card</span>
            <div class="text-xs">Pembayaran</div>
        </button>

        <div x-show="open" @click.away="open=false"
             class="absolute bottom-12 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-xl w-40 p-2">

            <a class="block px-3 py-2 text-sm" href="<?= base_url('riwayat-pembayaran') ?>">Data Pembayaran</a>

            <button class="block px-3 py-2 text-sm w-full text-left"
                    @click="$dispatch('open-pembayaran-modal'); open=false">
                Input Pembayaran
            </button>

            <a class="block px-3 py-2 text-sm" href="<?= base_url('tunggakan-admin') ?>">Tunggakan</a>
        </div>
    </div>

    <!-- Guru -->
    <div x-data="{open:false}" class="relative">
        <button @click="open=!open" class="text-center">
            <span class="material-symbols-outlined w-6 h-6 mx-auto">school</span>
            <div class="text-xs">Guru</div>
        </button>

        <div x-show="open" @click.away="open=false"
             class="absolute bottom-12 left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-xl w-40 p-2">

            <a class="block px-3 py-2 text-sm" href="<?= base_url('guru') ?>">Data Guru</a>
            <a class="block px-3 py-2 text-sm" href="<?= base_url('jadwal-pelajaran') ?>">Jadwal</a>

            <button class="block px-3 py-2 text-sm w-full text-left"
                    @click="$dispatch('open-guru-modal'); open=false">
                Lainnya
            </button>
        </div>
    </div>

</nav>


<!-- MODAL SANTRI -->
<div x-data="{open:false}"
     @open-santri-modal.window="open=true"
     x-show="open"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-80 rounded-2xl shadow-xl p-6" @click.away="open=false">

        <h3 class="text-lg font-semibold mb-4">Menu Santri Lainnya</h3>

        <div class="grid grid-cols-2 gap-3">
            <a href="<?= base_url('seragam') ?>" class="p-3 bg-gray-50 rounded-xl text-center shadow">Seragam</a>
            <a href="<?= base_url('saku') ?>" class="p-3 bg-gray-50 rounded-xl text-center shadow">Saku</a>
            <a href="<?= base_url('alumni') ?>" class="p-3 bg-gray-50 rounded-xl text-center shadow">Alumni</a>
            <a href="<?= base_url('checkin') ?>" class="p-3 bg-gray-50 rounded-xl text-center shadow">Check-in</a>

            <a href="<?= base_url('kewajiban') ?>"
               class="col-span-2 p-3 bg-gray-50 rounded-xl text-center shadow">Statu Kewajiban</a>
        </div>
    </div>
</div>


<!-- MODAL PEMBAYARAN -->
<div x-data="{open:false}"
     @open-pembayaran-modal.window="open=true"
     x-show="open"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-80 rounded-2xl shadow-xl p-6" @click.away="open=false">

        <h3 class="text-lg font-semibold mb-4">Pilih Jenis Pembayaran</h3>

        <div class="space-y-3">
            <a href="<?= base_url('pembayaran-kewajiban') ?>"
               class="block p-3 bg-gray-50 rounded-xl shadow text-center">Kewajiban</a>

            <a href="<?= base_url('pembayaran-psb') ?>"
               class="block p-3 bg-gray-50 rounded-xl shadow text-center">PSB</a>

            <a href="<?= base_url('pembayaran-alumni') ?>"
               class="block p-3 bg-gray-50 rounded-xl shadow text-center">Alumni</a>
        </div>
    </div>
</div>


<!-- MODAL GURU -->
<div x-data="{open:false}"
     @open-guru-modal.window="open=true"
     x-show="open"
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-80 rounded-2xl shadow-xl p-6" @click.away="open=false">

        <h3 class="text-lg font-semibold mb-4">Menu Guru Lainnya</h3>

        <div class="grid grid-cols-2 gap-3">
            <a href="<?= base_url('validasi') ?>" class="p-3 bg-gray-50 rounded-xl shadow text-center">Absen</a>
            <a href="<?= base_url('rekap') ?>" class="p-3 bg-gray-50 rounded-xl shadow text-center">Rekap</a>
        </div>

    </div>
</div>

</body>
</html>