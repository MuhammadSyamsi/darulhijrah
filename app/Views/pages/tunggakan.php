<?= $this->extend('template_general'); ?>
<?= $this->section('konten'); ?>

<div class="container-fluid">
    
<?php if (logged_in()): ?>
<!-- Secondary Header -->
<div class="bg-white border-bottom shadow-sm">
  <div class="container-fluid">
    <div class="row g-2 text-center py-2">

      <!-- Tombol Aksi Bulanan -->
      <div class="col">
        <button type="button" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center justify-content-center"
          data-bs-toggle="modal" data-bs-target="#aksiBulananModal">
          <i class="bi bi-calendar-event fs-5 mb-1"></i>
          <span class="small">Bulanan</span>
        </button>
      </div>

      <!-- Tombol Filter Reminder -->
      <div class="col">
        <button id="btn-filter-reminder" type="button" class="btn btn-success w-100 d-flex flex-column align-items-center justify-content-center">
          <i class="bi bi-calendar-check fs-5 mb-1"></i>
          <span class="small">Reminder</span>
        </button>
      </div>

      <!-- Tombol Download Tunggakan -->
      <div class="col">
        <a href="<?= base_url('tunggakan/download'); ?>" class="btn btn-outline-danger w-100 d-flex flex-column align-items-center justify-content-center">
          <i class="bi bi-download fs-5 mb-1"></i>
          <span class="small">Download</span>
        </a>
      </div>

    </div>
  </div>
</div>

<!-- Modal Aksi Bulanan -->
<div class="modal fade" id="aksiBulananModal" tabindex="-1" aria-labelledby="aksiBulananModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title" id="aksiBulananModalLabel">Aksi Bulanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <!-- Tombol Ganti Bulan -->
        <form method="post" action="<?= base_url('nextmonth'); ?>" class="mb-3">
          <?= csrf_field(); ?>
          <button type="submit" class="btn btn-outline-secondary w-100">
            <i class="ti ti-calendar-plus me-1"></i> Ganti Bulan
          </button>
        </form>

        <!-- Tombol Naik Kelas -->
        <form method="post" action="<?= base_url('naikkelas'); ?>">
          <?= csrf_field(); ?>
          <button type="submit" class="btn btn-outline-success w-100">
            <i class="ti ti-arrow-up-right me-1"></i> Naik Kelas
          </button>
        </form>
        
        <!-- Tombol Toggle Collapse -->
<a class="btn btn-outline-dark w-100 mt-3" data-toggle="collapse" href="#collapseDaftarUlangBeasiswa" role="button" aria-expanded="false" aria-controls="collapseDaftarUlangBeasiswa">
    <i class="ti ti-cash me-1"></i> Tambah Daftar Ulang Beasiswa
</a>

<!-- Form Collapse -->
<div class="collapse" id="collapseDaftarUlangBeasiswa">
    <div class="card card-body border shadow-sm">
        <form method="post" action="daftarulangBeasiswa">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="dukelas2" class="font-weight-bold">
                    Nominal Daftar Ulang Kelas 8 & 11 (Beasiswa)
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="dukelas2" name="dukelas2" placeholder="contoh: 1.500.000" required>
                </div>
            </div>

            <div class="form-group mt-3">
                <label for="dukelas3" class="font-weight-bold">
                    Nominal Daftar Ulang Kelas 9 & 12 (Beasiswa)
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="dukelas3" name="dukelas3" placeholder="Contoh: 2.750.000" required>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-success w-100 mt-3">
                <i class="ti ti-check me-1"></i> Proses Daftar Ulang
            </button>
        </form>
    </div>
</div>

 <!-- Tombol Toggle Collapse 2 -->
<a class="btn btn-outline-dark w-100 mt-3" data-toggle="collapse" href="#collapseDaftarUlangMandiri" role="button" aria-expanded="false" aria-controls="collapseDaftarUlangMandiri">
    <i class="ti ti-cash me-1"></i> Tambah Daftar Ulang Mandiri
</a>

<!-- Form Collapse -->
<div class="collapse" id="collapseDaftarUlangMandiri">
    <div class="card card-body border shadow-sm">
        <form method="post" action="daftarulangMandiri">
            <?= csrf_field(); ?>

            <div class="form-group">
                <label for="dukelas2" class="font-weight-bold">
                    Nominal Daftar Ulang Kelas 8 & 11 (Mandiri)
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="dukelas2mdr" name="dukelas2mdr" placeholder="contoh: 2.600.000" required>
                </div>
            </div>

            <div class="form-group">
                <label for="dukelas3" class="font-weight-bold">
                    Nominal Daftar Ulang Kelas 9 & 12 (Mandiri)
                </label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="dukelas3mdr" name="dukelas3" placeholder="Contoh: 3.100.000" required>
                </div>
            </div>

            <button type="submit" class="btn btn-outline-success w-100 mt-3">
                <i class="ti ti-check me-1"></i> Proses Daftar Ulang
            </button>
        </form>
    </div>
</div>
      </div>
    </div>
  </div>
</div>
</div>
<?php endif; ?>

    <!-- Pencarian Real-time -->
    <div class="col-12 my-2">
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <input id="search-input" class="form-control" type="search" placeholder="🔍 Cari Nama Santri" aria-label="Search">
            </div>
        </div>
    </div>

    <!-- Isi Data -->
    <div class="row g-4 mb-5">

        <!-- Santri -->
        <div class="col-md-4">
            <h5 class="fw-semibold text-success mb-3">Santri DH</h5>
            <div id="santri-results">
                <?php foreach ($transfer as $t) : ?>
                    <div class="card shadow-sm border-success border-start border-3 mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?= esc($t['nama']); ?> / <?= esc($t['kelas']); ?></h6>
                            <ul class="ps-3 mb-0">
                                <li>SPP: <b>Rp <?= format_rupiah($t['tunggakanspp']); ?>,-</b></li>
                                <li>Daftar Ulang: <b>Rp <?= format_rupiah($t['tunggakandu']); ?>,-</b></li>
                                <li>Tahun Lalu: <b>Rp <?= format_rupiah($t['tunggakantl']); ?>,-</b></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- PSB -->
        <div class="col-md-4">
            <h5 class="fw-semibold text-primary mb-3">PSB DH</h5>
            <div id="psb-results">
                <?php foreach ($transferpsb as $tp) : ?>
                    <div class="card shadow-sm border-primary border-start border-3 mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?= esc($tp['nama']); ?> / <?= esc($tp['jenjang']); ?></h6>
                            <ul class="ps-3 mb-0">
                                <li>Daftar Ulang: <b>Rp <?= format_rupiah($tp['tunggakandu']); ?>,-</b></li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Alumni -->
        <div class="col-md-4">
            <h5 class="fw-semibold text-warning mb-3">Alumni DH</h5>
            <div id="alumni-results">
                <?php foreach ($transferalumni as $ta) : ?>
                    <div class="card shadow-sm border-warning border-start border-3 mb-3">
                        <div class="card-body">
                            <h6 class="card-title"><?= esc($ta['nama']); ?> / <?= esc($ta['jenjang']); ?></h6>
                            <ul class="ps-3 mb-0">
                                <li>SPP: <b>Rp <?= format_rupiah($ta['tunggakanspp']); ?>,-</b></li>
                                <li>Daftar Ulang: <b>Rp <?= format_rupiah($ta['tunggakandu']); ?>,-</b></li>
                                <li>Tahun Lalu: <b>Rp <?= format_rupiah($ta['tunggakantl']); ?>,-</b></li>
                                <?php if (is_null($ta['uangsaku'])): ?>
  <li class="badge bg-danger text-white">Uang Saku: <b>tidak ada</b></li>
<?php else: ?>
  <li class="badge bg-warning text-dark">Uang Saku: <b><?= $ta['uangsaku']; ?>,-</b></li>
<?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>

<!-- Script Pencarian Real-time -->
<script>
function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

const searchInput = document.getElementById('search-input');

const doSearch = async () => {
    const keyword = searchInput.value.trim();

    const response = await fetch('/tunggakan/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ keyword })
    });

    const data = await response.json();

    const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(val);
};

    const santriCard = (item) => `
        <div class="card shadow-sm border-success border-start border-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">${item.nama} / ${item.kelas}</h6>
                <ul class="ps-3 mb-0">
                    <li>SPP: <b>Rp ${formatRupiah(item.tunggakanspp)},-</b></li>
                    <li>Daftar Ulang: <b>Rp ${formatRupiah(item.tunggakandu)},-</b></li>
                    <li>Tahun Lalu: <b>Rp ${formatRupiah(item.tunggakantl)},-</b></li>
                </ul>
            </div>
        </div>
    `;

    const psbCard = (item) => `
        <div class="card shadow-sm border-primary border-start border-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">${item.nama} / ${item.jenjang}</h6>
                <ul class="ps-3 mb-0">
                    <li>Daftar Ulang: <b>Rp ${formatRupiah(item.tunggakandu)},-</b></li>
                </ul>
            </div>
        </div>
    `;

    const alumniCard = (item) => `
    <div class="card shadow-sm border-warning border-start border-3 mb-3">
        <div class="card-body">
            <h6 class="card-title">${item.nama} / ${item.jenjang}</h6>
            <ul class="ps-3 mb-0">
                <li>SPP: <b>Rp ${formatRupiah(item.tunggakanspp)},-</b></li>
                <li>Daftar Ulang: <b>Rp ${formatRupiah(item.tunggakandu)},-</b></li>
                <li>Tahun Lalu: <b>Rp ${formatRupiah(item.tunggakantl)},-</b></li>
                ${
                    item.uangsaku === null || item.uangsaku === '' 
                        ? '<li class="badge bg-danger text-white">Uang Saku: <b>tidak ada</b></li>'
                        : `<li class="badge bg-warning text-dark">Uang Saku: <b>${item.uangsaku}</b></li>`
                }
            </ul>
        </div>
    </div>
`;

    document.getElementById('psb-results').innerHTML = data.psb.length > 0 ? data.psb.map(psbCard).join(''): `<p class="text-muted">Data tidak ditemukan</p>`;
    document.getElementById('santri-results').innerHTML = data.santri.length > 0 ? data.santri.map(santriCard).join(''): `<p class="text-muted">Data tidak ditemukan</p>`;
    document.getElementById('alumni-results').innerHTML = data.alumni.length > 0 ? data.alumni.map(alumniCard).join(''): `<p class="text-muted">Data tidak ditemukan</p>`;
};

searchInput.addEventListener('input', debounce(doSearch, 500));

document.addEventListener('DOMContentLoaded', () => {
    const numericFields = [
      'dukelas2', 'dukelas3', 'dukelas2mdr', 'dukelas3mdr'
    ];

    numericFields.forEach(id => {
      const input = document.getElementById(id);
      if (input) {
        new Cleave(input, {
          numeral: true,
          numeralThousandsGroupStyle: 'thousand'
        });
      }
    });

    document.querySelectorAll('form').addEventListener('submit', function () {
      numericFields.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
          input.value = input.value.replace(/\./g, '').replace(/,/g, '');
        }
      });
    });
  });
  
  // Fungsi Filter Reminder
document.getElementById('btn-filter-reminder').addEventListener('click', async () => {
    const response = await fetch('/tunggakan/reminder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    const data = await response.json();

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(val);
};

    function formatTanggalIndo(tgl) {
        if (!tgl) return "-";
        const d = new Date(tgl);
        return d.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "long",
            year: "numeric"
        });
    };

    const santriCard = (item) => {
    // Buat pesan WhatsApp
    const bulanIni = new Date().toLocaleString('id-ID', { month: 'long', year: 'numeric' });
    const countSpp = parseInt(item.tunggakanspp) / parseInt(item.spp);
    const total    = formatRupiah(
        parseInt(item.tunggakanspp) + parseInt(item.tunggakandu) + parseInt(item.tunggakantl)
    );
    const lastPay  = item.last_payment 
        ? `${formatTanggalIndo(item.last_payment.tanggal)} sejumlah Rp ${formatRupiah(item.last_payment.jumlah)}`
        : "Belum ada pembayaran";

    let waMessage = `الســــــــلام عليــــــــكم ورحمة اللــــــــــه وبركاته

Alhamdulillah, jazakallahu khairan katsīran kami sampaikan kepada Bapak/Ibu atas kerjasamanya dalam pendidikan ananda *${item.nama}.*

Berikut kami sampaikan informasi terkait kewajiban Syahriyah & administrasi ananda:

📌 SPP sampai bulan ${bulanIni} : Rp ${formatRupiah(item.tunggakanspp)} / ${countSpp} bulan
📌 Daftar Ulang : Rp ${formatRupiah(item.tunggakandu)}
📌 Tunggakan Lainnya : Rp ${formatRupiah(item.tunggakantl)}

Total kewajiban : Rp ${total}
Pembayaran terakhir diterima : ${lastPay}

Bagi Bapak/Ibu yang sudah menyelesaikan sebagian, kami ucapkan terima kasih. Untuk sisanya kami tunggu kabar baiknya hingga tanggal 10 setiap bulan insyaAllah.

Semoga Allah ﷻ senantiasa melancarkan rezeki Bapak/Ibu dan memberkahi keluarga. Āmīn.

والســــــــلام عليــــــــكم ورحمة اللــــــــــه وبركاته`;

    const waUrl = `https://wa.me/${item.kontak1}?text=${encodeURIComponent(waMessage)}`;

    return `
    <div class="card shadow-sm border-success border-start border-3 mb-3">
        <div class="card-body position-relative">
            <!-- Dropdown titik tiga -->
            <div class="dropdown position-absolute top-0 end-0 mt-2 me-2">
                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="${waUrl}" target="_blank">
                            <i class="bi bi-whatsapp text-success me-1"></i> Reminder Walisantri
                        </a>
                    </li>
                </ul>
            </div>

                <h6 class="card-title">${item.nama} / ${item.kelas}</h6>
                <ul class="ps-3 mb-0">
                    <li>SPP: <b>Rp ${formatRupiah(item.tunggakanspp)},-</b></li>
                    <li>Daftar Ulang: <b>Rp ${formatRupiah(item.tunggakandu)},-</b></li>
                    <li>Tahun Lalu: <b>Rp ${formatRupiah(item.tunggakantl)},-</b></li>
                </ul>
            </div>
        </div>
    `;
    };

    const psbCard = (item) => `
        <div class="card shadow-sm border-primary border-start border-3 mb-3">
            <div class="card-body">
                <h6 class="card-title">${item.nama} / ${item.jenjang}</h6>
                <ul class="ps-3 mb-0">
                    <li>Daftar Ulang: <b>Rp ${formatRupiah(item.tunggakandu)},-</b></li>
                </ul>
            </div>
        </div>
    `;

    document.getElementById('psb-results').innerHTML = data.psb.length > 0 ? data.psb.map(psbCard).join(''): `<p class="text-muted">Data tidak ditemukan</p>`;
    document.getElementById('santri-results').innerHTML = data.santri.length > 0 ? data.santri.map(santriCard).join(''): `<p class="text-muted">Data tidak ditemukan</p>`;
    document.getElementById('alumni-results').innerHTML = `<p class="text-muted">Tagihan hanya berlaku untuk syarat administrasi berkas</p>`;
});
</script>

<?= $this->endSection(); ?>
