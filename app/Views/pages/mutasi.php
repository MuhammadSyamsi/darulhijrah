<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="w-full p-4" x-data>

  <!-- FILTER ATAS -->
  <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-5">

    <!-- Tanggal & Rekening -->
    <div class="md:col-span-3">
      <div class="bg-white shadow rounded-xl p-3 space-y-2">

        <input id="tanggal-awal-filter" type="date"
               value="<?= esc($tanggalAwal) ?>"
               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-green-300" />

        <input id="tanggal-akhir-filter" type="date"
               value="<?= esc($tanggalAkhir) ?>"
               class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-green-300" />

        <select id="rekening-filter"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-green-300">
          <option value="">Rekening</option>
          <?php foreach ($rekeningList as $rek) : ?>
            <option value="<?= esc($rek) ?>"><?= esc($rek) ?></option>
          <?php endforeach; ?>
        </select>

        <select id="program-filter"
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-green-300">
          <option value="">Program</option>
          <option value="MANDIRI">MANDIRI</option>
          <option value="BEASISWA">BEASISWA</option>
        </select>

      </div>
    </div>

    <!-- Jenis Filter -->
    <div class="md:col-span-3">
      <div class="bg-white shadow rounded-xl p-3">

        <div class="flex flex-col space-y-2">

          <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border hover:bg-green-50">
            <input type="radio" name="jenisFilter" id="filterSantri" value="santri" checked
                   class="text-green-600 focus:ring-green-500" />
            <span class="font-semibold text-green-700">Santri</span>
          </label>

          <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border hover:bg-blue-50">
            <input type="radio" name="jenisFilter" id="filterPSB" value="psb"
                   class="text-blue-600 focus:ring-blue-500" />
            <span class="font-semibold text-blue-700">PSB</span>
          </label>

          <label class="flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg border hover:bg-yellow-50">
            <input type="radio" name="jenisFilter" id="filterAlumni" value="alumni"
                   class="text-yellow-600 focus:ring-yellow-500" />
            <span class="font-semibold text-yellow-700">Alumni</span>
          </label>

        </div>

      </div>
    </div>

    <!-- Search -->
    <div class="md:col-span-4">
      <div class="bg-white shadow rounded-xl p-3">
        <input id="search-input"
               type="search"
               placeholder="🔍 Cari Nama Santri / Keterangan..."
               class="w-full border rounded-lg px-4 py-2 text-base focus:ring focus:ring-green-300"
               autofocus />
      </div>
    </div>

    <!-- Tombol Download -->
    <div class="md:col-span-2 flex items-end">
      <button id="download-btn"
              class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl shadow">
        <span class="material-symbols-outlined align-middle mr-1">download</span>
        Download
      </button>
    </div>

  </div>

  <!-- HASIL -->
  <div id="hasil-container">

    <div id="empty-state"
         class="text-center py-10 text-gray-400">
      <span class="material-symbols-outlined text-5xl mb-2">database</span>
      <p class="text-lg">Silakan gunakan filter di atas untuk menampilkan data mutasi.</p>
    </div>

  </div>

</div>

<script>
function debounce(func, delay) {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(this, args), delay);
  };
}

function formatTanggalIndo(tgl) {
  if (!tgl) return "-";
  const d = new Date(tgl);
  return d.toLocaleDateString("id-ID", {
    day: "2-digit", month: "long", year: "numeric"
  });
};

const searchInput = document.getElementById('search-input');
const tanggalAwalFilter = document.getElementById('tanggal-awal-filter');
const tanggalAkhirFilter = document.getElementById('tanggal-akhir-filter');
const rekeningFilter = document.getElementById('rekening-filter');
const programFilter = document.getElementById('program-filter');
const jenisFilter = document.querySelectorAll('input[name="jenisFilter"]');
const hasilContainer = document.getElementById('hasil-container');
const downloadBtn = document.getElementById('download-btn');

const formatRupiah = val =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val);

const formatItem = (item, type) => {
  let colorClass =
    type === "psb"
      ? "border-blue-500"
      : type === "santri"
      ? "border-green-500"
      : "border-yellow-500";

  // URL berdasarkan tipe
  let editUrl = "";
  let kwitansiUrl = "";

  switch (type) {
    case "psb":
      editUrl = `<?= base_url('psb/'); ?>${item.idtrans}`;
      kwitansiUrl = `<?= base_url('kwitansi-psb/'); ?>${item.idtrans}`;
      break;

    case "santri":
      editUrl = `<?= base_url('edit/'); ?>${item.idtrans}`;
      kwitansiUrl = `<?= base_url('kwitansi/'); ?>${item.idtrans}`;
      break;

    case "alumni":
      editUrl = `<?= base_url('alumni/'); ?>${item.idtrans}`;
      kwitansiUrl = `<?= base_url('kwitansi-alumni/'); ?>${item.idtrans}`;
      break;
  }

  return `
    <div class="bg-white shadow rounded-xl border-l-4 ${colorClass} p-4 mb-3"
         x-data="{ open: false }">

      <div class="flex justify-between items-start gap-3">

        <!-- Kiri -->
        <div class="flex-1">
          <h3 class="font-semibold text-gray-800 text-base">
            ${item.nama} / ${item.kelas}
          </h3>

          <p class="text-sm text-gray-500 mt-1">
            ${formatTanggalIndo(item.tanggal)} • ${item.rekening} • ${formatRupiah(item.saldomasuk)}
          </p>

          <p class="mt-2 text-gray-700 text-sm">
            peruntukan = ${item.keterangan || ""}
          </p>
        </div>

        <!-- Menu -->
        <div class="relative">
          <button @click="open = !open"
                  class="text-gray-600 hover:text-gray-800 p-1 rounded-lg">
            <span class="material-symbols-outlined text-xl">more_vert</span>
          </button>

          <!-- Dropdown -->
          <div x-show="open" @click.outside="open = false"
               class="absolute right-0 mt-1 w-40 bg-white border shadow-lg rounded-lg overflow-hidden z-20">

            <a href="${editUrl}"
               class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100 text-blue-600">
              <span class="material-symbols-outlined text-base">edit</span>
              Edit
            </a>

            <a href="./delete/${item.idtrans}"
               onclick="return confirm('Apakah anda sudah mengupdate tunggakannya?');"
               class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100 text-red-600">
              <span class="material-symbols-outlined text-base">delete</span>
              Delete
            </a>

            <a href="${kwitansiUrl}"
               target="_blank"
               class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-gray-100 text-green-600">
              <span class="material-symbols-outlined text-base">receipt_long</span>
              Kwitansi
            </a>
          </div>
        </div>

      </div>
    </div>
  `;
};

const doSearch = async () => {
  const keyword = searchInput.value.trim();
  const tanggal_awal = tanggalAwalFilter.value;
  const tanggal_akhir = tanggalAkhirFilter.value;
  const rekening = rekeningFilter.value;
  const program = programFilter.value;
  const jenis = document.querySelector('input[name="jenisFilter"]:checked').value;

  const response = await fetch('/mutasi/search', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body: JSON.stringify({ keyword, tanggal_awal, tanggal_akhir, rekening, program, jenis })
  });

  const data = await response.json();

  let results = [];
  if (jenis === 'psb') results = data.psb;
  else if (jenis === 'santri') results = data.santri;
  else results = data.alumni;

  hasilContainer.innerHTML = results.length
    ? results.map(item => formatItem(item, jenis)).join('')
    : `<div class="flex flex-col items-center justify-center py-5 bg-gray-50 rounded-xl select-none">
    <span class="material-symbols-outlined text-gray-400 text-5xl">
        search_off
    </span>
    <p class="text-gray-500 text-sm mt-2 font-medium">Data tidak ditemukan</p>
</div>`;
};

// Tombol download
downloadBtn.addEventListener('click', async () => {
  const tanggal_awal = tanggalAwalFilter.value;
  const tanggal_akhir = tanggalAkhirFilter.value;
  const rekening = rekeningFilter.value;
  const program = programFilter.value;
  const jenis = document.querySelector('input[name="jenisFilter"]:checked').value;

  const url = `/mutasi/download?tanggal_awal=${tanggal_awal}&tanggal_akhir=${tanggal_akhir}&rekening=${rekening}&program=${program}&jenis=${jenis}`;
  window.open(url, '_blank');
});

// Event listener
searchInput.addEventListener('input', debounce(doSearch, 500));
tanggalAwalFilter.addEventListener('change', doSearch);
tanggalAkhirFilter.addEventListener('change', doSearch);
rekeningFilter.addEventListener('change', doSearch);
programFilter.addEventListener('change', doSearch);
jenisFilter.forEach(r => r.addEventListener('change', doSearch));

// init default
doSearch();
</script>

<?= $this->endSection(); ?>
