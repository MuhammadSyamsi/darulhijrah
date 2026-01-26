<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<!-- TUNGGAKAN PAGE - TAILWINDCSS + ALPINE.JS -->
<div x-data="tunggakanApp()" x-init="init()" x-cloak class="p-4 md:p-6 space-y-6">  <!-- TITLE -->  <h1 class="text-2xl font-bold text-slate-800 mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">account_balance_wallet</span>
    Data Tunggakan Pembayaran
  </h1>
  
  <!-- SANTRI CARD -->
  <div class="bg-white rounded-xl shadow p-5 border border-slate-200">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold text-slate-700">Santri Aktif</h2>
    </div>
    
    <!-- FILTER FORM -->
<form @submit.prevent="load('santri', $event)" x-ref="filterSantri" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
  <select name="jenjang" class="border rounded-lg p-2 text-sm">
    <option value="">Semua Jenjang</option>
    <option value="MTS">MTS</option>
    <option value="MA">MA</option>
  </select>
  <select name="kelas" class="border rounded-lg p-2 text-sm">
    <option value="">Semua Kelas</option>
    <option value="1">7</option>
    <option value="2">8</option>
    <option value="3">9</option>
    <option value="3">10</option>
    <option value="3">11</option>
    <option value="3">12</option>
  </select>
  <input type="text" name="nama" placeholder="Cari nama..." class="border rounded-lg p-2 text-sm" />
  <button class="bg-emerald-600 text-white rounded-lg px-4 py-2 text-sm flex items-center justify-center gap-1">
    <span class="material-symbols-outlined text-base">search</span>
    Filter
  </button>
</form>

<!-- TABLE -->
<div class="overflow-x-auto">
  <table class="min-w-full text-sm border rounded-lg overflow-hidden">
    <thead class="bg-slate-100 text-slate-700">
      <tr>
        <th class="p-2 border">Nama</th>
        <th class="p-2 border">Kelas</th>
        <th class="p-2 border">Jenjang</th>
        <th class="p-2 border">SPP</th>
        <th class="p-2 border">DU</th>
        <th class="p-2 border">DU2</th>
        <th class="p-2 border">DU3</th>
        <th class="p-2 border text-center">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <template x-for="r in lists.santri" :key="r.id">
        <tr class="hover:bg-slate-50">
          <td class="p-2 border" x-text="r.nama"></td>
          <td class="p-2 border text-center" x-text="r.kelas"></td>
          <td class="p-2 border text-center" x-text="r.jenjang"></td>
          <td class="p-2 border text-right" x-text="formatMoney(r.tunggakanspp)"></td>
          <td class="p-2 border text-right" x-text="formatMoney(r.tunggakandu)"></td>
          <td class="p-2 border text-right" x-text="formatMoney(r.tunggakandu2)"></td>
          <td class="p-2 border text-right" x-text="formatMoney(r.tunggakandu3)"></td>
          <td class="p-2 border text-center">
            <button @click="openEdit('santri', r)" class="px-3 py-1 text-xs bg-amber-500 text-white rounded flex gap-1 items-center">
              <span class="material-symbols-outlined text-sm">edit</span>
              Edit
            </button>
          </td>
        </tr>
      </template>
      <tr x-show="lists.santri.length == 0">
        <td colspan="8" class="text-center text-slate-400 p-4">Tidak ada data</td>
      </tr>
    </tbody>
  </table>
</div>

  </div>
  
  <!-- MODAL EDIT -->
  <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div x-show="modalOpen" x-transition class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6"><div class="flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold text-slate-800">Edit Tunggakan</h3>
    <button @click="closeModal()" class="text-slate-500 hover:text-red-500">
      <span class="material-symbols-outlined">close</span>
    </button>
  </div>

  <form @submit.prevent="submitEdit" class="space-y-4">

    <input type="hidden" x-model="edit.tipe">
    <input type="hidden" x-model="edit.id">

    <!-- INFO -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="text-sm text-slate-600">Nama</label>
        <input x-model="edit.nama" readonly class="w-full border bg-slate-100 rounded-lg p-2" />
      </div>
      <div>
        <label class="text-sm text-slate-600">Kelas</label>
        <input x-model="edit.kelas" readonly class="w-full border bg-slate-100 rounded-lg p-2" />
      </div>
      <div>
        <label class="text-sm text-slate-600">Jenjang</label>
        <input x-model="edit.jenjang" readonly class="w-full border bg-slate-100 rounded-lg p-2" />
      </div>
    </div>

    <!-- NOMINAL -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      <div>
        <label class="text-sm text-slate-600">SPP</label>
        <input type="number" x-model.number="edit.tunggakanspp" class="w-full border rounded-lg p-2" />
      </div>
      <div>
        <label class="text-sm text-slate-600">DU</label>
        <input type="number" x-model.number="edit.tunggakandu" class="w-full border rounded-lg p-2" />
      </div>
      <div>
        <label class="text-sm text-slate-600">DU2</label>
        <input type="number" x-model.number="edit.tunggakandu2" class="w-full border rounded-lg p-2" />
      </div>
      <div>
        <label class="text-sm text-slate-600">DU3</label>
        <input type="number" x-model.number="edit.tunggakandu3" class="w-full border rounded-lg p-2" />
      </div>
    </div>

    <div class="flex justify-end gap-2">
      <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg bg-slate-100">Batal</button>
      <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Simpan</button>
    </div>

  </form>
</div>

  </div>
</div>

<!-- ALPINE COMPONENT -->
<script>
function tunggakanApp() {
  return {
    lists: { santri: [] },
    modalOpen: false,
    edit: {},

    init() {
      this.submitAllFilters();
    },

    submitAllFilters() {
      if (this.$refs.filterSantri) this.load('santri', {target: this.$refs.filterSantri});
    },

    async load(tipe, ev) {
      let body = new URLSearchParams(new FormData(ev.target));
      let res = await fetch(`/tunggakan-admin/load/${tipe}`, { method:'POST', body });
      this.lists[tipe] = await res.json();
    },

    openEdit(tipe, row) {
      this.edit = JSON.parse(JSON.stringify({tipe, ...row}));
      this.modalOpen = true;
    },

    closeModal() { this.modalOpen = false; },

    async submitEdit() {
      let body = new URLSearchParams(this.edit);
      let res = await fetch('/tunggakan-admin/update', { method:'POST', body });
      let json = await res.json();
      if (json.success) {
        alert('Berhasil Update');
        this.closeModal();
        this.submitAllFilters();
      } else alert('Gagal update');
    },

    formatMoney(v) { return Number(v).toLocaleString('id-ID'); }
  };
}
</script>

<?= $this->endSection(); ?>