<?php

use CodeIgniter\Router\RouteCollection;

// ROUTE UMUM (terfilter myth auth sementara)
$routes->get('/', 'Home::index');
$routes->get('/beranda', 'Home::beranda');
$routes->get('/tentang', 'Home::tentang');

$routes->post('/data-tunggakan', 'Page::datatunggakan');
$routes->get('data-tunggakan', 'Page::datatunggakan');
$routes->post('tunggakan/search', 'Page::cariTunggakan');
$routes->post('tunggakan/reminder', 'Page::reminder');
$routes->get('jadwalku', 'Guru::lihat_jadwal');

$routes->get('/lihat-saku', 'Saku::landing');
$routes->get('seragam', 'SeragamController::index');
$routes->get('seragam/downloadcsv', 'SeragamController::downloadcsv');
$routes->get('laporan/downloadDetail', 'Home::downloadDetail');

// GROUP: kamad
$routes->group('', ['filter' => 'role:superadmin,kamad'], function ($routes) {
    $routes->get('guru', 'Guru::index');
    $routes->get('mapel', 'Guru::mapel');
    $routes->get('kelas', 'Guru::kelas');
    $routes->get('jadwal-pelajaran', 'Guru::jadwal');
    $routes->get('jadwal/cetak', 'Guru::cetak_jadwal');
    $routes->get('jadwal/api/cek_jadwal_by_guru/(:num)', 'Guru::api_cek_jadwal_by_guru/$1');
    $routes->post('jadwal/simpanChecklist', 'Guru::simpanChecklist');
    $routes->post('jadwal/update/(:num)', 'JadwalController::update/$1');
    $routes->delete('jadwal/hapus/(:num)', 'JadwalController::hapus/$1');

    $routes->post('guru/save', 'Guru::save');
    $routes->get('guru/edit/(:num)', 'Guru::edit/$1');
    $routes->post('guru/update/(:num)', 'Guru::update/$1');
    $routes->post('guru/delete/(:num)', 'Guru::delete/$1');
    $routes->post('kelas/delete/(:num)', 'Guru::delete_kelas/$1');
    $routes->post('jadwal/simpan', 'JadwalController::simpan');
});
// GROUP: musrif
$routes->group('', ['filter' => 'role:superadmin,musrif'], function ($routes) {
    $routes->get('/musrif', 'Home::musrif');
    $routes->get('/checkin', 'Home::valCheckin');
    $routes->post('check', 'Home::check');
    $routes->get('/home/search', 'Home::search');
    $routes->get('/kantin', 'Saku::kantin');
    $routes->post('/saku/pembelian', 'Saku::pembelian');
});

// GROUP: superadmin dan ustadz
$routes->group('', ['filter' => 'role:superadmin,ustadz'], function ($routes) {
    $routes->get('/data-santri', 'Santri::data');
    $routes->get('/Santri/data', 'Santri::data');
    $routes->get('/Santri/download', 'Santri::download');
    $routes->get('/Santri/getSantriByNISN/(:any)', 'Santri::getSantriByNISN/$1');
    $routes->get('/data-psb', 'Santri::psb');
    $routes->get('/Santri/psb', 'Santri::psb');
    $routes->get('/psb/getSantriById/(:any)', 'Santri::getPsbById/$1');
    $routes->get('/riwayat-alumni', 'Santri::alumni');
//    $routes->get('/pendaftaran-observasi', 'Psb');
    $routes->get('/pendaftaran-formulir', 'Psb::tambah');
    $routes->post('psb/filter', 'Psb::filter');

    $routes->post('Santri/arsipMasal', 'Santri::arsipMasal');
    $routes->post('Santri/gantiTahunMasuk', 'Santri::gantiTahunMasuk');
    $routes->post('Santri/tandaiKeluar', 'Santri::tandaiKeluar');
    $routes->post('/pindah(:any)', 'Santri::pindah/$1');
    $routes->post('/formulir(:num)', 'Psb::formulir/$1');
    $routes->post('Santri/migrasiMasal', 'Santri::migrasiMasal');
    $routes->post('Santri/updateSantri', 'Santri::updateSantri');
    $routes->post('psb/updateSantri', 'Santri::updatePsb');
    $routes->post('daftarulangBeasiswa', 'Page::daftarulangBeasiswa');
    $routes->post('daftarulangMandiri', 'Page::daftarulangMandiri');
    $routes->get('/pembayaran-kewajiban', 'Page::tambah');
    $routes->post('/save', 'Page::save');
    $routes->post('/cetak', 'Page::cetak');
    $routes->post('psb/migrasiKeSantri', 'Psb::migrasiKeSantri');
});

// GROUP: superadmin saja
$routes->group('', ['filter' => 'role:superadmin'], function ($routes) {
    $routes->get('/riwayat-pembayaran', 'Page::mutasi');
    $routes->get('/pembayaran-alumni', 'Alumni::tambah');
    $routes->get('/pembayaran-psb', 'Psb::pembayaran');
    $routes->get('mutasi/download', 'Page::download_datapembayaran');
$routes->get('tunggakan/download', 'Page::download');
$routes->get('/keuangan/tunggakan', 'Page::tunggakan');
    $routes->get('/alumni/tunggakan', 'Alumni::tunggakan');
    $routes->get('/keuangan/transaksi', 'Page::transaksi');
    $routes->get('/keuangan/keterangan', 'Page::keterangan');
    $routes->get('/downloadpsb', 'Psb::laporanpsb');
    $routes->post('jadwal/reset', 'JadwalController::resetJadwal');
    $routes->get('laporan-pemasukan', 'Home::koran');
$routes->get('laporan/downloadBulanan', 'Home::downloadBulanan');
$routes->get('laporan/downloadHarian', 'Home::downloadHarian');
    $routes->get('/tunggakan-admin', 'Page::datatunggakanadmin');
    $routes->post('/tunggakan-admin', 'Page::datatunggakanadmin');
    $routes->post('tunggakan-admin/search', 'Page::cariTunggakanadmin');
$routes->group('tunggakan-admin', function($routes) {
    $routes->get('/', 'Page::datatunggakanadmin');
    $routes->post('load/(:segment)', 'Page::load/$1'); // load santri/psb/alumni
    $routes->post('update', 'Page::update');
});

    $routes->post('/savealumni', 'Alumni::save');
    $routes->post('/cetakpsb', 'Psb::cetak');
    $routes->post('/cetakalumni', 'Alumni::cetak');
    $routes->post('/riwayat-pembayaran', 'Page::mutasi');
    $routes->post('mutasi/search', 'Page::cariMutasi');
    $routes->post('/nextmonth', 'Page::nextmonth');
    $routes->post('/naikkelas', 'Page::naikkelas');
    $routes->get('/psb/(:num)', 'Psb::dtransaksi/$1');
    $routes->get('/edit/(:num)', 'Page::dtransaksi/$1');
    $routes->get('/alumni/(:num)', 'Alumni::dtransaksi/$1');
    $routes->post('/edit', 'Page::edit');
    $routes->get('/delete/(:num)', 'Page::delet/$1');
    $routes->post('/editformulir(:num)', 'Psb::editformulir/$1');
    $routes->post('/mundur(:num)', 'Psb::mundur/$1');
    $routes->post('/formulir_psb', 'Psb::save');
    $routes->post('/bayar', 'Psb::bayar');
    $routes->post('/edittungpsb', 'Psb::edittung');
    $routes->post('/fullform', 'Psb::fullform');
    $routes->post('/fulleditform', 'Psb::fulleditform');
    $routes->post('/daftarbaru_psb', 'Psb::daftarbaru_psb');
    $routes->post('/komitmen(:num)', 'Psb::komitmen/$1');
    $routes->post('/closing(:num)', 'Psb::closing/$1');
    $routes->post('/pembayaran', 'Psb::pembayaran');
    // $routes->post('Santri/kurangiSPPMasal', 'Santri::kurangiSPPMasal');
    
    // khusus kwitansi
    $routes->get('kwitansi/(:num)', 'Page::kwitansi_santri_aktif/$1');
    $routes->get('kwitansi-psb/(:num)', 'Page::kwitansi_santri_psb/$1');
    $routes->get('kwitansi-alumni/(:num)', 'Page::kwitansi_santri_alumni/$1');

    $routes->get('/saku', 'Saku::index');
    $routes->get('/saku/create', 'Saku::create');
    $routes->post('/saku/store', 'Saku::store');
    $routes->get('/saku/edit/(:num)', 'Saku::edit/$1');
    $routes->post('/saku/update/(:num)', 'Saku::update/$1');
    $routes->get('/saku/delete/(:num)', 'Saku::delete/$1');
    $routes->get('/saku/(:num)', 'Saku::show/$1');
    $routes->post('saku/aksi-massal', 'Saku::aksiMassal');
    $routes->get('/saku/laundry', 'Saku::laundry');

    //data-seragam
    $routes->post('seragam/simpan', 'SeragamController::simpanSeragam');
    $routes->get('seragam/pengajuan', 'SeragamController::pengajuan');
    $routes->post('seragam/pengajuan/simpan', 'SeragamController::simpanPengajuan');
    $routes->get('seragam/distribusi', 'SeragamController::distribusi');
    $routes->post('seragam/distribusi/simpan', 'SeragamController::simpanDistribusi');
    $routes->get('seragam/stok', 'SeragamController::stok');
    $routes->post('seragam/stok/update', 'SeragamController::updateStok');
    $routes->post('seragam/update', 'SeragamController::update');

    //data-user
    $routes->get('user/add', 'Admin\UserController::create');
    $routes->post('admin/user/store', 'Admin\UserController::store');

    //percobaan-kantin
    $routes->get('claim', 'Page::claim_laundry'); // tester NFC reader
});

// ROUTE RESOURCE API (umum atau filter via controller)
$routes->resource('api/home',   ['controller' => 'Api\Home']);
$routes->get('api/kedua/(:segment)', 'Api\Kedua::show/$1');
$routes->resource('api/psb',    ['controller' => 'Api\Psb']);
$routes->resource('api/alumni', ['controller' => 'Api\Alumni']);

// // ADMIN: hanya untuk superadmin
$routes->group('', ['filter' => 'role:superadmin, kamad'], function ($routes) {
    //data-absensi
    $routes->get('rekap', 'ValidasiController::rekap'); // Lihat rekap performa guru
    $routes->get('validasi/rekap/download', 'ValidasiController::download');
});

// ---------------- Jadwal ----------------
$routes->group('jadwal', ['filter' => 'role:superadmin,kamad'], function ($routes) {
    $routes->get('/', 'JadwalController::index');
    $routes->get('mapel', 'JadwalController::mapel');
    $routes->post('mapel/simpan', 'JadwalController::tambahMapel');

    $routes->get('kelas', 'JadwalController::kelas');
    $routes->post('kelas/simpan', 'JadwalController::tambahKelas');

    $routes->get('distribusi', 'JadwalController::distribusiJam');
    $routes->post('distribusi/simpan', 'JadwalController::tambahDistribusiJam');

    $routes->get('slot', 'JadwalController::slot');
    $routes->post('slot/simpan', 'JadwalController::tambahSlot');

    $routes->get('generate', 'JadwalController::generate');
    $routes->post('generate/proses', 'JadwalController::generateJadwal');

});

// ---------------- Validasi Kehadiran ----------------
$routes->group('validasi', ['filter' => 'role:superadmin,musrif'], function ($routes) {
    $routes->get('/', 'ValidasiController::index'); // Form validasi harian
    $routes->post('simpan', 'ValidasiController::simpan'); // Simpan hasil validasi
    $routes->get('jadwal/(:num)', 'ValidasiController::jadwalByHari/$1'); // Tampilkan jadwal per hari (id hari)
});


