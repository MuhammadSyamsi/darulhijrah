<?= $this->extend('template'); ?>  
<?= $this->section('konten'); ?>

<div x-data="kewajibanApp()" class="p-6">

    <div class="flex flex-col gap-4 mb-6">

        <!-- Title -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                📘 Data Kewajiban Santri
            </h2>

            <!-- Download -->
            <a
                :href="kelasAktif
                    ? '<?= base_url('kewajiban/download-csv'); ?>?kelas=' + kelasAktif
                    : '<?= base_url('kewajiban/download-csv'); ?>'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 
                    text-white rounded-lg text-sm font-medium shadow hover:opacity-90 transition">
                ⬇️ Export CSV
            </a>
        </div>

        <!-- Filter & Action -->
        <div class="bg-white p-4 rounded-xl shadow flex flex-wrap gap-3 items-center">

            <select x-model="kelasAktif"
                @change="loadByKelas"
                class="border-gray-300 focus:ring-2 focus:ring-blue-500 rounded-lg text-sm">
                <option value="">📂 Pilih Kelas</option>
                <template x-for="k in kelas">
                    <option :value="k.kelas" x-text="k.kelas"></option>
                </template>
            </select>

            <button
                x-show="kelasAktif"
                @click="openForm=true"
                class="ml-auto inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 
                    text-white px-4 py-2 rounded-lg text-sm shadow transition">
                ➕ Tambah Tag
            </button>

        </div>
    </div>

<table
    class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden shadow-sm"
    x-show="kelasAktif"
>
    <!-- ================= HEADER ================= -->
    <thead class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <tr>
            <th class="p-3 text-left font-semibold">Santri</th>
            <th class="p-3 text-left font-semibold">Tag / Status</th>
            <th class="p-3 text-center font-semibold w-28">Aksi</th>
        </tr>
    </thead>

    <!-- ================= BODY ================= -->
    <tbody class="bg-white divide-y divide-gray-100">
        <template x-for="s in rows" :key="s.nisn">
            <tr class="hover:bg-blue-50 transition align-top">
                <td class="p-3">
                    <div class="flex items-center gap-3">
                    <!-- Avatar -->
                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500
                                   text-white flex items-center justify-center font-semibold"
                            x-text="s.nama.charAt(0)"
                        ></div>

                        <div>
                            <div class="font-semibold text-gray-800" x-text="s.nama"></div>
                            <div class="text-xs text-gray-500">
                                SPP: <span x-text="formatRupiah(s.spp)"></span>
                            </div>
                        </div>
                    </div>
                    </td>

                <td class="p-3">
                    <div class="flex flex-wrap gap-1">
                        <template x-for="i in s.items">
                            <span
                            class="px-2 py-0.5 text-xs rounded-full font-medium"
                            :class="{
                                'bg-red-100 text-red-700': i.status=='tunggakan',
                                'bg-green-100 text-green-700': i.status=='lunas',
                                'bg-yellow-100 text-yellow-700': i.status=='angsur',
                                'bg-blue-100 text-blue-700': i.status=='terbayar'
                            }"
                            x-text="`${i.tag} : ${i.status}`">
                            </span>
                        </template>
                    </td>

                <td class="p-3 text-center">
                    <button
                        class="flex items-center justify-center gap-1.5
                            px-3 py-1.5 rounded-lg
                            border border-blue-500
                            text-blue-600 bg-transparent
                            hover:bg-blue-600 hover:text-white
                            hover:border-blue-600
                            transition duration-200"
                        title="Edit"
                        @click="openEditModal(s)"
                    >
                        <span class="material-symbols-outlined text-[18px]">
                            edit
                        </span>
                        <span class="text-sm font-medium">
                            Edit
                        </span>
                    </button>
                </td>
                </tr>
            </template>
    </tbody>
</table>

    <!-- MODAL ADD KEWAJIBAN -->
    <div x-show="openForm" class="fixed inset-0 bg-black/40 flex items-center justify-center">
        <div class="bg-white p-5 rounded w-96">
            <h3 class="font-semibold mb-4">
                Tambah Kewajiban Kelas <span x-text="kelasAktif"></span>
            </h3>

            <input
                x-model="form.tag"
                type="text"
                placeholder="Tag Kewajiban (SPP Februari)"
                class="w-full border p-2 mb-4">

            <div class="flex justify-end gap-2">
                <button @click="openForm=false"
                    class="border px-3 py-2 rounded">
                    Batal
                </button>
                <button @click="simpanMassal"
                    class="bg-blue-600 text-white px-3 py-2 rounded">
                    Simpan Massal
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT + RIWAYAT -->
    <div x-show="openEdit" class="fixed inset-0 bg-black/40 flex items-center justify-center">
        <div class="bg-white w-[900px] max-h-[50vh] overflow-y-auto rounded p-5">

            <!-- HEADER -->
            <div class="mb-4 border-b pb-2">
                <div class="font-semibold text-lg" x-text="editData.nama"></div>
                    <div class="text-sm text-gray-500">
                        SPP: <span x-text="formatRupiah(editData.spp)"></span>
                    </div>
            </div>

            <h4 class="text-lg font-semibold mb-4">
                Status Kewajiban
            </h4>

                <div class="grid grid-cols-2 lg:grid-cols-2 gap-6">

                    <!-- ================= RIWAYAT PEMBAYARAN ================= -->
                    <div class="bg-white rounded-xl shadow border">
                        <div class="flex items-center gap-2 px-4 py-3 border-b bg-gradient-to-r from-emerald-500 to-green-500 text-white rounded-t-xl">
                            <span class="material-symbols-outlined">receipt_long</span>
                            <h3 class="font-semibold text-sm">Riwayat Pembayaran</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="p-3 text-left">Tanggal</th>
                                        <th class="p-3 text-left">Saldo Masuk</th>
                                        <th class="p-3 text-left">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="r in riwayat" :key="r.idtrans">
                                        <tr class="hover:bg-gray-50 transition" x-data="dateHelper">
                                            <td class="p-3 border-t" x-text="formatTanggal(r.tanggal)"></td>
                                            <td class="p-3 border-t font-medium text-emerald-600"
                                                x-text="formatRupiah(r.saldomasuk)">
                                            </td>
                                            <td class="p-3 border-t text-gray-600" x-text="r.keterangan"></td>
                                        </tr>
                                    </template>

                                    <tr x-show="riwayat.length === 0">
                                        <td colspan="3" class="text-center text-gray-400 p-6">
                                            <span class="material-symbols-outlined block text-3xl mb-1">
                                                inbox
                                            </span>
                                            Tidak ada riwayat pembayaran
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= STATUS TAG KEWAJIBAN ================= -->
                    <div class="bg-white rounded-xl shadow border">
                        <div class="flex items-center gap-2 px-4 py-3 border-b bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-t-xl">
                            <span class="material-symbols-outlined">assignment_turned_in</span>
                            <h3 class="font-semibold text-sm">Status Kewajiban</h3>
                        </div>

                        <div class="p-4 space-y-3">
                            <template x-for="item in editData.items" :key="item.id">
                                <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-lg border">
                                    
                                    <!-- TAG -->
                                    <div class="flex items-center gap-2 flex-1">
                                        <span class="material-symbols-outlined text-gray-500 text-base">
                                            label
                                        </span>
                                        <span class="text-sm font-medium text-gray-700"
                                            x-text="item.tag"></span>
                                    </div>

                                    <!-- STATUS -->
                                    <select
                                        x-model="item.status"
                                        class="border rounded-lg px-2 py-1 text-sm focus:ring focus:ring-blue-200">
                                        <option value="tunggakan">🔴 Tunggakan</option>
                                        <option value="angsur">🟡 Angsur</option>
                                        <option value="lunas">🟢 Lunas</option>
                                        <option value="terbayar">✅ Terbayar</option>
                                    </select>

                                    <!-- BUTTON -->
                                    <button
                                        @click="updateStatus(item)"
                                        class="flex items-center gap-1 text-xs px-3 py-1 rounded-lg
                                            bg-blue-600 text-white hover:bg-blue-700 transition">
                                        <span class="material-symbols-outlined text-sm">
                                            save
                                        </span>
                                        Simpan
                                    </button>

                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            
            <!-- FOOTER -->
            <div class="flex justify-between items-center mt-4 border-t pt-3">
                <div class="text-xs text-gray-500">
                    Perubahan akan disimpan sekaligus
                </div>

                <div class="flex gap-2">
                    <button
                        @click="openEdit=false"
                        class="px-4 py-2 border rounded">
                        Batal
                    </button>

                    <button
                        @click="simpanSemua()"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan Semua
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

<script>
function dateHelper() {
    return {
        formatTanggal(tanggal) {
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const d = new Date(tanggal);
            const hari = String(d.getDate()).padStart(2, '0');
            const namaBulan = bulan[d.getMonth()];
            const tahun = d.getFullYear();

            return `${hari} ${namaBulan} ${tahun}`;
        }
    }
}

function formatRupiah(angka) {
    if (!angka) return '-'
    return new Intl.NumberFormat('id-ID').format(angka)
}

function kewajibanApp() {
    return {        
        kelas: [],
        kelasAktif: '',
        rows: [],
        openForm: false,
        open: false,
        openEdit: false,
        editData: {},
        riwayat: [],

        form: {
            tag: ''
        },

        init() {
            fetch('/kewajiban/kelas')
                .then(r => r.json())
                .then(d => this.kelas = d)
        },

        loadByKelas() {
            fetch(`/kewajiban/list/${this.kelasAktif}`)
                .then(r => r.json())
                .then(d => this.rows = d)
        },

        openEditModal(santri) {
            // clone data kewajiban
            this.editData = JSON.parse(JSON.stringify(santri))
            this.openEdit = true

            // load riwayat pembayaran
            fetch(`/kewajiban/riwayat/${santri.nisn}`)
                .then(r => r.json())
                .then(d => this.riwayat = d)
        },

        updateStatus(item) {
            fetch(`/kewajiban/update-status/${item.id}`, {
                method: 'PUT',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({ status: item.status })
            })
            .then(() => {
                // refresh halaman penuh
                window.location.reload()
            })
        },

        simpanMassal() {
            fetch('/kewajiban/store-massal', {
                method: 'POST',
                headers: {'Content-Type':'application/json'},
                body: JSON.stringify({
                    kelas: this.kelasAktif,
                    tag: this.form.tag
                })
            }).then(() => {
                this.openForm = false
                this.form.tag = ''
                this.loadByKelas()
            })
        },

        lihat(nisn) {
            fetch(`/kewajiban/riwayat/${nisn}`)
                .then(r => r.json())
                .then(d => {
                    this.riwayat = d
                    this.open = true
                })
        },

        editSantri(santri) {
            this.editData = JSON.parse(JSON.stringify(santri))
            this.openEdit = true
        },

        simpanSemua() {
            fetch('/kewajiban/update-status-massal', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(
                    this.editData.items.map(i => ({
                        id: i.id,
                        status: i.status
                    }))
                )
            })
            .then(() => {
                // refresh full page agar state bersih
                window.location.reload()
            })
        }
    }
}
</script>

<?= $this->endSection(); ?>