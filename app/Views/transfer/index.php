<?= $this->extend('template') ?>
<?= $this->section('konten') ?>

<div x-data="transferApp()" class="bg-white shadow rounded-2xl">

<!-- Filter Utama -->
<div class="p-4 border-b bg-gray-50 rounded-t-2xl">
    <div class="flex flex-wrap gap-3 items-end">

        <!-- Bulan -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Bulan</label>
            <select x-model="bulan"
                class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                <template x-for="b in 12">
                    <option :value="b" x-text="b"></option>
                </template>
            </select>
        </div>

        <!-- Tahun -->
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tahun</label>
            <select x-model="tahun"
                class="border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua</option>
                <template x-for="t in tahunList">
                    <option :value="t" x-text="t"></option>
                </template>
            </select>
        </div>

        <!-- Filter -->
        <button @click="loadData()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            Tampilkan
        </button>

        <!-- Download -->
        <a :href="`${baseurl}/transfer/csv?bulan=${bulan}&tahun=${tahun}&rekening=${filterRekening}&program=${filterProgram}`"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            Download CSV
        </a>

    </div>
</div>

<!-- Table -->
<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-gray-600">
            
            <!-- Header -->
            <tr>
                <th class="px-3 py-2">Tanggal</th>
                <th class="px-3 py-2">Rekening</th>
                <th class="px-3 py-2">Program</th>
                <th class="px-3 py-2 text-right">Saldo</th>
                <th class="px-3 py-2">Keterangan</th>
                <th class="px-3 py-2 text-right">SPP</th>
                <th class="px-3 py-2 text-right">Tunggakan</th>
                <th class="px-3 py-2 text-right">Inden</th>
                <th class="px-3 py-2 text-right">Daftar Ulang</th>
                <th class="px-3 py-2 text-right">Uang Saku</th>
                <th class="px-3 py-2 text-right">Infaq</th>
                <th class="px-3 py-2 text-right">Formulir</th>
            </tr>

            <!-- Filter Kolom -->
            <tr class="bg-white border-b">
                <th></th>
                <th class="px-2 py-1">
                    <input type="text"
                        x-model="filterRekening"
                        @input.debounce.500ms="loadData()"
                        placeholder="Filter rekening"
                        class="w-full border rounded px-2 py-1 text-xs">
                </th>
                <th class="px-2 py-1">
                    <input type="text"
                        x-model="filterProgram"
                        @input.debounce.500ms="loadData()"
                        placeholder="Filter program"
                        class="w-full border rounded px-2 py-1 text-xs">
                </th>
                <th colspan="9"></th>
            </tr>

        </thead>

        <tbody class="divide-y">
            <template x-for="row in rows" :key="row.idtrans">
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2" x-text="formatDate(row.tanggal)"></td>
                    <td class="px-3 py-2 font-medium" x-text="row.rekening"></td>
                    <td class="px-3 py-2">
                        <span class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs"
                              x-text="row.program"></span>
                    </td>

                    <td class="px-3 py-2 text-right font-semibold text-green-600"
                        x-text="formatUang(row.saldomasuk)"></td>

                    <td class="px-3 py-2 text-gray-500" x-text="row.keterangan"></td>

                    <td class="px-3 py-2 text-right" x-text="formatUang(row.spp)"></td>
                    <td class="px-3 py-2 text-right text-red-500" x-text="formatUang(row.tunggakan_spp)"></td>
                    <td class="px-3 py-2 text-right" x-text="formatUang(row.inden_spp)"></td>
                    <td class="px-3 py-2 text-right" x-text="formatUang(row.daftarulang)"></td>
                    <td class="px-3 py-2 text-right" x-text="formatUang(row.uangsaku)"></td>
                    <td class="px-3 py-2 text-right" x-text="formatUang(row.infaq)"></td>
                    <td class="px-3 py-2 text-right" x-text="formatUang(row.formulir)"></td>
                </tr>
            </template>

            <!-- Empty State -->
            <tr x-show="!loaded">
                <td colspan="12" class="text-center py-10 text-gray-400">
                    Silakan pilih filter lalu klik <b>Tampilkan</b>
                </td>
            </tr>

            <tr x-show="loaded && rows.length === 0">
                <td colspan="12" class="text-center py-10 text-gray-400">
                    Tidak ada data
                </td>
            </tr>

        </tbody>
    </table>
</div>

</div>

<script>
const BASEURL = "<?= base_url() ?>";

function transferApp() {
    return {
        baseurl: BASEURL,
        bulan: '',
        tahun: new Date().getFullYear(),
        tahunList: [2023, 2024, 2025, 2026],

        filterRekening: '',
        filterProgram: '',

        rows: [],
        loaded: false,

        loadData() {
            this.loaded = true;

            let url = `${this.baseurl}/transfer/data?bulan=${this.bulan}&tahun=${this.tahun}&rekening=${this.filterRekening}&program=${this.filterProgram}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    this.rows = data;
                });
        },

        formatUang(n) {
            if (!n) return '0';
            return new Intl.NumberFormat('id-ID').format(n);
        },

        formatDate(tgl) {
            if (!tgl) return '';
            let d = new Date(tgl);
            return d.toLocaleDateString('id-ID');
        }
    }
}
</script>

<?= $this->endSection() ?>