<div class="container mx-auto p-4" x-data="santriApp()">

  <!-- Pilih Semua & Tombol Aksi Masal -->
  <?php if(count($santri) > 0): ?>
  <div class="flex items-center mb-4 space-x-4">
    <div>
      <input type="checkbox" id="checkAll" class="form-checkbox h-5 w-5" x-model="allChecked" @change="toggleAll">
      <label for="checkAll" class="ml-2 text-gray-700 font-medium">Pilih Semua</label>
    </div>
    <button type="button" class="bg-blue-600 text-white px-3 py-1 rounded disabled:opacity-50" 
            :disabled="checkedSantri.length===0" 
            @click="openModalEditMasal">
      <span class="material-symbols-outlined align-middle">edit_square</span> Edit Massal
    </button>
  </div>
  <?php endif; ?>

  <!-- List Santri -->
  <div class="grid gap-4">
    <?php foreach($santri as $s): ?>
    <div class="bg-white shadow rounded-lg p-4 relative cursor-pointer selectable-card"
         :class="checkedSantri.includes('<?= $s['nisn'] ?>') ? 'bg-blue-100' : ''"
         @click="toggleSantri('<?= $s['nisn'] ?>')">
      
      <input type="checkbox" class="hidden" :value="'<?= $s['nisn'] ?>'" x-model="checkedSantri">
      
      <!-- Info Santri -->
      <div>
        <h6 class="font-semibold text-gray-800"><?= $s['nama'] ?></h6>
        <p class="text-gray-500 text-sm mt-1">
          <span class="mr-2"><strong>NISN:</strong> <?= $s['nisn'] ?></span>
          <span class="mr-2"><strong>Program:</strong> <?= $s['program'] ?></span>
          <span class="mr-2"><strong>Jenjang:</strong> <?= $s['jenjang'] ?></span>
          <span class="mr-2"><strong>Kelas:</strong> <?= $s['kelas'] ?></span>
        </p>
      </div>

      <!-- Tombol Aksi Individual -->
      <div class="flex space-x-2 mt-2">
        <button class="flex-1 bg-white border border-blue-600 text-blue-600 px-2 py-1 rounded hover:bg-blue-600 hover:text-white transition"
                @click.stop="editSantri('<?= $s['nisn'] ?>')">
          <span class="material-symbols-outlined align-middle">edit</span> Edit
        </button>
        <button class="flex-1 bg-white border border-red-600 text-red-600 px-2 py-1 rounded hover:bg-red-600 hover:text-white transition"
                @click.stop="keluarSantri('<?= $s['nisn'] ?>')">
          <span class="material-symbols-outlined align-middle">logout</span> Keluar
        </button>
        <button class="flex-1 bg-white border border-green-600 text-green-600 px-2 py-1 rounded hover:bg-green-600 hover:text-white transition"
                @click.stop="arsipSantri('<?= $s['nisn'] ?>')">
          <span class="material-symbols-outlined align-middle">archive</span> Arsip
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <?php if(count($santri)===0): ?>
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mt-4">Tidak ada data santri ditemukan.</div>
  <?php endif; ?>

  <!-- Modal Edit Massal -->
  <div x-show="modalEditMasal" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50" x-cloak>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 overflow-y-auto max-h-[90vh]">
      <div class="flex justify-between items-center mb-4">
        <h5 class="font-semibold text-lg flex items-center gap-2">
          <span class="material-symbols-outlined">edit_square</span> Edit Masal
        </h5>
        <button @click="modalEditMasal=false" class="text-gray-500 hover:text-gray-800">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <div class="mb-4 text-sm text-blue-600">
        Perubahan akan diterapkan ke <strong x-text="checkedSantri.length"></strong> santri terpilih.
      </div>

      <div class="grid md:grid-cols-3 gap-4">
        <div>
          <label class="block mb-1 text-gray-600">Program</label>
          <select class="w-full border rounded px-3 py-2" x-model="massal.program">
            <option value="">- Pilih -</option>
            <option value="MANDIRI">MANDIRI</option>
            <option value="BEASISWA">BEASISWA</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 text-gray-600">Kelas</label>
          <select class="w-full border rounded px-3 py-2" x-model="massal.kelas">
            <option value="">- Pilih -</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="lulus">lulus</option>
            <option value="keluar">keluar</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 text-gray-600">Jenjang</label>
          <select class="w-full border rounded px-3 py-2" x-model="massal.jenjang">
            <option value="">- Pilih -</option>
            <option value="MA">MA</option>
            <option value="MTs">MTs</option>
          </select>
        </div>
      </div>

      <div class="mt-6 flex justify-end space-x-2">
        <button @click="submitMassal" class="bg-green-600 text-white px-4 py-2 rounded flex items-center gap-1">
          <span class="material-symbols-outlined">check_circle</span> Simpan
        </button>
        <button @click="modalEditMasal=false" class="bg-gray-300 text-gray-800 px-4 py-2 rounded flex items-center gap-1">
          <span class="material-symbols-outlined">close</span> Tutup
        </button>
      </div>
    </div>
  </div>

</div>

<script>
function santriApp() {
  return {
    checkedSantri: [],
    allChecked: false,
    modalEditMasal: false,
    massal: { program:'', kelas:'', jenjang:'' },

    toggleSantri(nisn) {
      const index = this.checkedSantri.indexOf(nisn);
      if(index === -1) this.checkedSantri.push(nisn);
      else this.checkedSantri.splice(index, 1);
      this.allChecked = this.checkedSantri.length === <?= count($santri) ?>;
    },

    toggleAll() {
      if(this.allChecked) this.checkedSantri = <?= json_encode(array_column($santri,'nisn')) ?>;
      else this.checkedSantri = [];
    },

    openModalEditMasal() {
      this.modalEditMasal = true;
    },

    submitMassal() {
      if(this.checkedSantri.length===0) return alert('Pilih santri terlebih dahulu.');
      // contoh post via fetch
      fetch('<?= base_url('Santri/updateMasal') ?>', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({
          ids: this.checkedSantri,
          ...this.massal
        })
      }).then(res=>res.json()).then(res=>{
        alert(res.msg);
        if(res.status) location.reload();
      });
    },

    editSantri(nisn) {
      // ambil data via fetch & buka modal individual
      alert('Buka modal edit santri: '+nisn);
    },

    keluarSantri(nisn) {
      if(confirm('Yakin menandai santri keluar?')) {
        fetch('<?= base_url('Santri/tandaiKeluar') ?>',{
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body:JSON.stringify({ nisn })
        }).then(res=>res.json()).then(res=>{
          alert(res.msg);
          if(res.status) location.reload();
        });
      }
    },

    arsipSantri(nisn) {
      if(confirm('Yakin ingin mengarsipkan santri?')) {
        fetch('<?= base_url('Santri/arsipMasal') ?>',{
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body:JSON.stringify({ ids:[nisn] })
        }).then(res=>res.json()).then(res=>{
          alert(res.msg);
          if(res.status) location.reload();
        });
      }
    }
  }
}
</script>