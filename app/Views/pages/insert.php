<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<?php
use App\Models\DetailModel;

$TransferModel = new DetailModel();
$id = $TransferModel->orderBy('id', 'desc')->limit(1)->findColumn('id');
$today = date('Y-m-d');
$i = ($id == null) ? 1 : ($id[0] + 1);
?>

<div class="px-4 py-6" x-data>
  <div class="max-w-6xl mx-auto">

    <!-- Card -->
    <div class="bg-white shadow-sm rounded-xl p-6 mb-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-2xl font-semibold text-sky-700 flex items-center gap-2">
          <span class="material-symbols-outlined text-sky-700">payments</span>
          Pembayaran Kewajiban Santri
        </h3>
      </div>

      <form action="<?= site_url('save') ?>" method="post" class="space-y-4">
        <?= csrf_field(); ?>
        <input type="hidden" name="id" value="<?= $i; ?>" />

        <!-- Select Santri -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Nama Santri</label>
          <select id="namaSantri" name="nisn" class="w-full rounded-md border-gray-300 shadow-sm"></select>

          <!-- Informasi Tambahan -->
          <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
            <span id="info-program" class="hidden inline-flex items-center px-2 py-1 rounded text-white" style="background-color: #1B4332;">
              <span class="material-symbols-outlined mr-1 text-xs">school</span>
              program: -
            </span>

            <span id="info-spp" class="hidden inline-flex items-center px-2 py-1 rounded font-semibold" style="background-color: #ffc107; color: #000;">
              <span class="material-symbols-outlined mr-1 text-xs">paid</span>
              SPP: Rp 0
            </span>

            <span id="info-tunggakan-du" class="hidden inline-flex items-center px-2 py-1 rounded text-white" style="background-color: #dc3545;">
              <span class="material-symbols-outlined mr-1 text-xs">report_problem</span>
              Tunggakan DU 1: Rp 0
            </span>

            <span id="info-tunggakan-du2" class="hidden inline-flex items-center px-2 py-1 rounded text-white" style="background-color: #dc3545;">
              <span class="material-symbols-outlined mr-1 text-xs">report_problem</span>
              Tunggakan DU 2: Rp 0
            </span>

            <span id="info-tunggakan-du3" class="hidden inline-flex items-center px-2 py-1 rounded text-white" style="background-color: #dc3545;">
              <span class="material-symbols-outlined mr-1 text-xs">report_problem</span>
              Tunggakan DU 3: Rp 0
            </span>
          </div>

          <!-- hidden inputs untuk dikirim -->
          <input type="hidden" id="nisn" name="nisn" />
          <input type="hidden" id="nama" name="nama" />
          <input type="hidden" id="kelas" name="kelas" />
          <input type="hidden" id="program" name="program" />
        </div>

        <!-- Tombol otomatis (hidden kecuali ada santri terpilih) -->
        <div id="tombol" class="hidden">
          <label class="block text-sm font-medium text-gray-700 mb-2">Input Otomatis</label>
          <div class="flex gap-2 overflow-auto pb-2">
            <button type="button" onclick="isiSPP()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">SPP</button>
            <button type="button" onclick="isiDaftarUlang()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">Daftar Ulang</button>
            <button type="button" onclick="isiSPPLaundry()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">SPP & Laundry</button>
            <button type="button" onclick="isiSPPDaftarUlang()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">SPP & DU</button>
            <button type="button" onclick="isiSPPLaundryDaftarUlang()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">SPP, L & DU</button>
            <button type="button" onclick="isiAngsuranDU()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50 text-red-600">Angsuran DU</button>
            <button type="button" onclick="isiSPPAngsuranDU()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">SPP & DU</button>
            <button type="button" onclick="isiFormulir()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">Formulir</button>
            <button type="button" onclick="isiLain()" class="px-3 py-1 border rounded text-sm bg-white hover:bg-sky-50">Lain</button>
          </div>
        </div>

        <!-- Detail Transaksi Card -->
        <div class="bg-gray-50 rounded-lg border p-4 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label for="saldomasuk" class="block text-sm font-medium text-gray-700">Nominal</label>
              <input type="text" id="saldomasuk" class="mt-1 block w-full rounded-md border-gray-300" required disabled />
              <input type="hidden" id="saldomasuk_raw" name="saldomasuk" />
            </div>

            <div>
              <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" value="<?= $today ?>" class="mt-1 block w-full rounded-md border-gray-300" required />
            </div>

            <div>
              <label for="rekening" class="block text-sm font-medium text-gray-700">Rekening</label>
              <select id="rekening" name="rekening" class="mt-1 block w-full rounded-md border-gray-300" required>
                <option disabled selected value="">-- Pilih Rekening --</option>
                <option value="Muamalat Salam">Muamalat Salam</option>
                <option value="Jatim Syariah">Jatim Syariah</option>
                <option value="BSI">BSI</option>
                <option value="Uang Saku">Uang Saku</option>
                <option value="Muamalat Yatim">Muamalat Yatim</option>
                <option value="Tunai">Tunai</option>
                <option value="lain-lain">Lain-lain</option>
              </select>
            </div>

            <div>
              <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
              <input type="text" id="keterangan" name="keterangan" class="mt-1 block w-full rounded-md border-gray-300" required />
            </div>
          </div>
        </div>

        <!-- Transaksi Sebelumnya (Accordion with Alpine) -->
        <div x-data="{ open: true }" class="space-y-2">
          <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-4 py-2 bg-white border rounded-md">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined">history</span>
              <span class="font-medium">Transaksi Sebelumnya</span>
            </div>
            <span x-show="!open" class="material-symbols-outlined">expand_more</span>
            <span x-show="open" class="material-symbols-outlined">expand_less</span>
          </button>

          <div x-show="open" x-cloak class="bg-white border rounded-md p-4">
            <?php for ($j = 1; $j <= 3; $j++): ?>
              <div class="grid grid-cols-1 md:grid-cols-6 gap-2 mb-3 items-center">
                <div id="lasttanggal<?= $j ?>" class="text-sm text-gray-600 col-span-2"></div>
                <div id="lastrek<?= $j ?>" class="text-sm text-gray-600 col-span-1"></div>
                <div id="lastnom<?= $j ?>" class="text-sm font-semibold text-gray-800 col-span-1"></div>
                <div id="lastket<?= $j ?>" class="text-sm text-gray-700 col-span-2"></div>
              </div>
            <?php endfor; ?>
          </div>
        </div>

        <!-- Detail Pemasukan (Accordion) -->
        <div x-data="{ openDetail: false }" class="space-y-2">
          <button type="button" @click="openDetail = !openDetail" class="w-full flex items-center justify-between px-4 py-2 bg-white border rounded-md">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined">list</span>
              <span class="font-medium">Detail Pemasukan</span>
            </div>
            <span x-show="!openDetail" class="material-symbols-outlined">expand_more</span>
            <span x-show="openDetail" class="material-symbols-outlined">expand_less</span>
          </button>

          <div x-show="openDetail" x-cloak class="bg-white border rounded-md p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label for="tunggakandu_form" class="block text-sm font-medium text-gray-700">Bayar Daftar Ulang</label>
                <input type="text" id="tunggakandu_form" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="tunggakandu_form_raw" name="tunggakandu" value="0" />
              </div>

              <div>
                <label for="tunggakantl_form" class="block text-sm font-medium text-gray-700">Bayar Tunggakan</label>
                <input type="text" id="tunggakantl_form" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="tunggakantl_form_raw" name="tunggakantl" value="0" />
              </div>

              <div>
                <label for="tunggakanspp" class="block text-sm font-medium text-gray-700">Bayar SPP</label>
                <input type="text" id="tunggakanspp" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="tunggakanspp_raw" name="tunggakanspp" value="0" />
              </div>

              <div>
                <label for="uangsaku" class="block text-sm font-medium text-gray-700">Uang Saku</label>
                <input type="text" id="uangsaku" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="uangsaku_raw" name="uangsaku" value="0" />
              </div>

              <div>
                <label for="infaq" class="block text-sm font-medium text-gray-700">Infaq</label>
                <input type="text" id="infaq" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="infaq_raw" name="infaq" value="0" />
              </div>

              <div>
                <label for="formulir" class="block text-sm font-medium text-gray-700">Formulir</label>
                <input type="text" id="formulir" value="0" class="mt-1 block w-full rounded-md border-gray-300" required />
                <input type="hidden" id="formulir_raw" name="formulir" value="0" />
              </div>
            </div>
          </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-sky-600 hover:bg-sky-700 text-white font-semibold rounded-md">
          <span class="material-symbols-outlined">print</span>
          Buat Kwitansi
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Modal (Alpine store used for global control) -->
<script>
document.addEventListener('alpine:init', () => {
  Alpine.store('modalDU', {
    show: false,
    open() { this.show = true; },
    close() { this.show = false; }
  });
});
</script>

<div x-cloak x-show="$store.modalDU.show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
  <div class="bg-white rounded-lg shadow-lg w-80">
    <div class="px-4 py-3 border-b flex items-center justify-between">
      <h5 class="font-medium">Masukkan Nominal</h5>
      <button @click="$store.modalDU.close()" class="text-gray-500 hover:text-gray-700">
        <span class="material-symbols-outlined">close</span>
      </button>
    </div>
    <div class="p-4">
      <input id="inputNominalDU" type="number" placeholder="Contoh: 150000" class="w-full rounded-md border-gray-300 px-3 py-2" />
    </div>
    <div class="p-3 border-t flex justify-end gap-2">
      <button @click="$store.modalDU.close()" class="px-3 py-1 rounded border">Batal</button>
      <button id="btnSubmitNominalDU" onclick="handleSubmitNominalDU()" class="px-3 py-1 rounded bg-sky-600 text-white">OK</button>
    </div>
  </div>
</div>

<!-- Scripts: Select2 + main logic (converted) -->
<script>
let selectedSantri = null;

document.addEventListener('DOMContentLoaded', function () {
  // Initialize Select2
  $('#namaSantri').select2({
    placeholder: "Cari Nama Santri",
    width: '100%',
    language: {
      noResults: function () {
        return 'Santri tidak ditemukan';
      },
      searching: function () {
        return 'Mencari...';
      }
    },
    ajax: {
      url: 'api/home',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          search: params.term
        };
      },
      processResults: function (data) {
        return {
          results: data.map(s => ({
            id: s.nisn,
            nama: s.nama,
            kelas: s.kelas,
            program: s.program,
            spp: Number(s.spp),
            du: Number(s.tunggakandu),
            du2: Number(s.tunggakandu2),
            du3: Number(s.tunggakandu3),
            text: s.nama
          }))
        };
      },
      cache: true
    },
    templateResult: function (data) {
      if (!data.id) return data.text;
      return $(`
        <div>
          <div style="font-weight: bold;">${data.nama}</div>
          <div style="font-size: 0.9em; color: #6b7280;">kelas ${data.kelas} - ${data.program}</div>
        </div>
      `);
    },
    templateSelection: function (data) {
      return data.nama || data.text;
    }
  });

  // When a santri is selected
  $('#namaSantri').on('select2:select', function (e) {
    const nisn = e.params.data;
    if ($(this).val()) {
      $('#tombol').removeClass('hidden');
      $('#tombol').addClass('block');
    } else {
      $('#tombol').addClass('hidden');
    }

    selectedSantri = nisn;
    $('#nisn').val(nisn.id);
    $('#nama').val(nisn.nama);
    $('#kelas').val(nisn.kelas);
    $('#program').val(nisn.program);

    const formattedSPP = typeof nisn.spp === 'number' ? nisn.spp.toLocaleString('id-ID') : '0';
    const formattedTunggakanDU = typeof nisn.du === 'number' ? nisn.du.toLocaleString('id-ID') : '0';
    const formattedTunggakanDU2 = typeof nisn.du2 === 'number' ? nisn.du2.toLocaleString('id-ID') : '0';
    const formattedTunggakanDU3 = typeof nisn.du3 === 'number' ? nisn.du3.toLocaleString('id-ID') : '0';

    $('#info-program').text(nisn.program).removeClass('hidden');
    $('#info-spp').text('SPP: Rp ' + formattedSPP);
    const tunggakanDU = Number(nisn.du) || 0;
    const tunggakanDU2 = Number(nisn.du2) || 0;
    const tunggakanDU3 = Number(nisn.du3) || 0;

    $('#info-tunggakan-du').text('Tunggakan DU 1: Rp ' + formattedTunggakanDU);
    $('#info-tunggakan-du2').text('Tunggakan DU 2: Rp ' + formattedTunggakanDU2);
    $('#info-tunggakan-du3').text('Tunggakan DU 3: Rp ' + formattedTunggakanDU3);

    if (nisn.spp > 0) {
      $('#info-spp').removeClass('hidden');
    } else {
      $('#info-spp').addClass('hidden');
    }

    if (nisn.du > 0) {
      $('#info-tunggakan-du').removeClass('hidden');
    } else {
      $('#info-tunggakan-du').addClass('hidden');
    }

    if (nisn.du2 > 0) {
      $('#info-tunggakan-du2').removeClass('hidden');
    } else {
      $('#info-tunggakan-du2').addClass('hidden');
    }

    if (nisn.du3 > 0) {
      $('#info-tunggakan-du3').removeClass('hidden');
    } else {
      $('#info-tunggakan-du3').addClass('hidden');
    }
  });

  // Fetch last 3 transactions when select changes (separate handler)
  $('#namaSantri').on('select2:select', function (e) {
    const nisn = e.params.data.id;

    $.ajax({
      url: 'api/kedua/' + nisn,
      method: 'GET',
      dataType: 'json',
      success: function (data) {
        for (let i = 0; i < 3; i++) {
          const transaksi = data[i] || { tanggal: '-', rekening: '-', nominal: 0, keterangan: '-' };

          // Tanggal format Indonesia
          const tanggalIndo = transaksi.tanggal !== '-' ? new Date(transaksi.tanggal).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }) : '-';

          const nominalFormatted = Number(transaksi.nominal || 0).toLocaleString('id-ID');

          $('#lasttanggal' + (i + 1)).text(tanggalIndo);
          $('#lastrek' + (i + 1)).text(transaksi.rekening);
          $('#lastnom' + (i + 1)).text(transaksi.nominal ? 'Rp ' + nominalFormatted : '-');
          $('#lastket' + (i + 1)).text(transaksi.keterangan);
        }
      },
      error: function () {
        for (let i = 0; i < 3; i++) {
          $('#lasttanggal' + (i + 1)).text('-');
          $('#lastrek' + (i + 1)).text('-');
          $('#lastnom' + (i + 1)).text('-');
          $('#lastket' + (i + 1)).text('-');
        }
      }
    });
  });

  // Inputs formatting registration
  function formatRibuan(angka) {
    return angka.replace(/\D/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function updateInput(originalInput, hiddenInput) {
    originalInput.addEventListener('input', function () {
      const raw = this.value.replace(/\D/g, '');
      this.value = formatRibuan(raw);
      hiddenInput.value = raw;
    });
  }

  const saldomasukInput = document.getElementById('saldomasuk');
  const duInput = document.getElementById('tunggakandu_form');
  const tlInput = document.getElementById('tunggakantl_form');
  const sppInput = document.getElementById('tunggakanspp');
  const formulirInput = document.getElementById('formulir');
  const sakuInput = document.getElementById('uangsaku');
  const infaqInput = document.getElementById('infaq');

  const saldomasukRaw = document.getElementById('saldomasuk_raw');
  const duRaw = document.getElementById('tunggakandu_form_raw');
  const tlRaw = document.getElementById('tunggakantl_form_raw');
  const sppRaw = document.getElementById('tunggakanspp_raw');
  const formulirRaw = document.getElementById('formulir_raw');
  const sakuRaw = document.getElementById('uangsaku_raw');
  const infaqRaw = document.getElementById('infaq_raw');

  // Safely attach only if elements exist
  if (saldomasukInput && saldomasukRaw) updateInput(saldomasukInput, saldomasukRaw);
  if (duInput && duRaw) updateInput(duInput, duRaw);
  if (tlInput && tlRaw) updateInput(tlInput, tlRaw);
  if (sppInput && sppRaw) updateInput(sppInput, sppRaw);
  if (formulirInput && formulirRaw) updateInput(formulirInput, formulirRaw);
  if (sakuInput && sakuRaw) updateInput(sakuInput, sakuRaw);
  if (infaqInput && infaqRaw) updateInput(infaqInput, infaqRaw);
});

// Utility formatting
function formatRupiah(angka) {
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function isiField(id, value) {
  const el = document.getElementById(id);
  const rawEl = document.getElementById(id + '_raw');
  if(el) el.value = formatRupiah(String(value || 0));
  if(rawEl) rawEl.value = value || 0;
}

// Auto-fill buttons
function isiSPP() {
  const spp = selectedSantri?.spp || 0;
  isiField("saldomasuk", spp);
  isiField("tunggakanspp", spp);
  isiField("uangsaku", 0);
  isiField("tunggakandu_form", 0);
  document.getElementById("keterangan").value = "SPP bulan ...";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiDaftarUlang() {
  const tunggakandu = selectedSantri?.du || 0;
  isiField("saldomasuk", tunggakandu);
  isiField("tunggakandu_form", tunggakandu);
  isiField("tunggakanspp", 0);
  isiField("uangsaku", 0);
  document.getElementById("keterangan").value = "Pelunasan daftar ulang";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiSPPLaundry() {
  const spp = selectedSantri?.spp || 0;
  const total = Number(spp) + 50000;
  isiField("saldomasuk", total);
  isiField("tunggakanspp", spp);
  isiField("uangsaku", 50000);
  isiField("tunggakandu_form", 0);
  document.getElementById("keterangan").value = "SPP bulan ... dan laundry";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiSPPDaftarUlang() {
  const spp = selectedSantri?.spp || 0;
  const tunggakandu = selectedSantri?.du || 0;
  const total = Number(spp) + Number(tunggakandu);
  isiField("saldomasuk", total);
  isiField("tunggakanspp", spp);
  isiField("tunggakandu_form", tunggakandu);
  isiField("uangsaku", 0);
  document.getElementById("keterangan").value = "SPP bulan ... dan pelunasan daftar ulang";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiSPPLaundryDaftarUlang() {
  const spp = selectedSantri?.spp || 0;
  const tunggakandu = selectedSantri?.du || 0;
  const total = Number(spp) + Number(tunggakandu) + 50000;
  isiField("saldomasuk", total);
  isiField("tunggakanspp", spp);
  isiField("tunggakandu_form", tunggakandu);
  isiField("uangsaku", 50000);
  document.getElementById("keterangan").value = "SPP bulan ... , laundry dan pelunasan daftar ulang";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiAngsuranDU() {
  // open modal via Alpine store
  if (window.Alpine && Alpine.store('modalDU')) {
    Alpine.store('modalDU').open();
  } else {
    // fallback to prompt
    const nominal = parseInt(prompt('masukkan nominal') || 0);
    isiField("tunggakandu_form", nominal);
    isiField("saldomasuk", nominal);
    isiField("tunggakanspp", 0);
    isiField("uangsaku", 0);
    document.getElementById("keterangan").value = "Angsuran daftar ulang";
    $('#keterangan').focus();
    $('#saldomasuk').prop('disabled', true);
  }
}

function isiSPPAngsuranDU() {
  const nominal = parseInt(prompt('masukkan nominal') || 0);
  const spp = selectedSantri?.spp || 0;
  if (nominal < spp) {
    alert("nominal kurang dari nilai spp");
    return;
  }
  isiField("tunggakanspp", spp);
  isiField("saldomasuk", nominal);
  isiField("tunggakandu_form", nominal - spp);
  isiField("uangsaku", 0);
  document.getElementById("keterangan").value = "SPP bulan ... dan angsuran daftar ulang";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
}

function isiFormulir() {
  $('#saldomasuk').prop('disabled', false);
  document.getElementById("keterangan").value = "Formulir Peserta Santri Baru";
  $('#saldomasuk').focus();
}

function isiLain() {
  $('#saldomasuk').prop('disabled', false);
  $('#saldomasuk').focus();
}

// Called by modal OK button
function handleSubmitNominalDU() {
  const val = Number(document.getElementById('inputNominalDU').value) || 0;
  isiField("tunggakandu_form", val);
  isiField("saldomasuk", val);
  isiField("tunggakanspp", 0);
  isiField("uangsaku", 0);
  document.getElementById("keterangan").value = "Angsuran daftar ulang";
  $('#keterangan').focus();
  $('#saldomasuk').prop('disabled', true);
  if (window.Alpine && Alpine.store('modalDU')) {
    Alpine.store('modalDU').close();
  }
}
</script>

<?= $this->endSection(); ?>