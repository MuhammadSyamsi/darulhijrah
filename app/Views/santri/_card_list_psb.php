<!--selected all santri-->
<?php if (count($santri) > 0): ?>
<div class="flex items-center gap-4 mb-4">
  <label class="flex items-center gap-2 text-sm cursor-pointer">
    <input type="checkbox" id="checkAll" class="rounded border-gray-300">
    Pilih Semua
  </label>

  <button
    type="button"
    id="btnEditMasal"
    class="bg-blue-600 text-white text-sm px-3 py-1.5 rounded-lg disabled:opacity-50"
    onclick="openEditMasal()"
    disabled>
    Edit Massal
  </button>
</div>
<?php endif; ?>

<!--selected santri-->
<div class="space-y-3">
<?php foreach ($santri as $s): ?>

<div
  class="relative bg-white border rounded-xl shadow-sm p-3 santri-card selectable-card"
  data-jenjang="<?= strtolower($s['jenjang']) ?>"
  data-kelas="<?= strtolower($s['kelas']) ?>"
  data-status="<?= strtolower($s['status']) ?>"
  data-nama="<?= strtolower($s['nama']) ?>">

  <!-- Checkbox -->
  <input
    type="checkbox"
    class="santri-check absolute left-3 top-1/2 -translate-y-1/2 rounded border-gray-300"
    value="<?= $s['id'] ?>">

  <!-- Konten -->
  <label class="block pl-10 cursor-pointer">
    <h6 class="font-semibold text-gray-800 mb-1">
      <?= $s['nama'] ?>
    </h6>

    <p class="text-xs text-gray-500 flex flex-wrap gap-x-3">
      <span><strong>NISN:</strong> <?= $s['nisn'] ?></span>
      <span><strong>Program:</strong> <?= $s['program'] ?></span>
      <span><strong>Jenjang:</strong> <?= $s['jenjang'] ?></span>
      <span><strong>Kelas:</strong> <?= $s['kelas'] ?></span>
      <span><strong>Status:</strong> <?= $s['status'] ?></span>
    </p>
  </label>

  <!-- Aksi -->
  <div class="flex flex-wrap gap-2 mt-3">
    <button
      class="flex-1 border border-blue-500 text-blue-600 text-sm px-3 py-1.5 rounded-lg"
      onclick='openEditSantri(<?= json_encode($s, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
      <i class="bi bi-pencil"></i> Edit
    </button>

    <button
      class="flex-1 border border-red-500 text-red-600 text-sm px-3 py-1.5 rounded-lg">
      <i class="bi bi-box-arrow-right"></i> Keluar
    </button>

    <button
      class="flex-1 border border-green-600 text-green-600 text-sm px-3 py-1.5 rounded-lg">
      <i class="bi bi-archive"></i> Arsip
    </button>
  </div>

</div>

<?php endforeach; ?>
</div>

<?php if (count($santri) === 0): ?>
<div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm">
  Tidak ada data santri ditemukan.
</div>
<?php endif; ?>

<!--modal edit santri massal-->
<div id="modalEditMasal"
     class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center">

  <div class="bg-white w-full max-w-xl rounded-xl shadow-lg">

    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h3 class="font-semibold text-lg">Edit Santri Terpilih</h3>
      <button onclick="closeEditMasal()">✕</button>
    </div>

    <form action="<?= base_url('psb/updateMasal') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="ids" id="edit_ids">

      <div class="px-6 py-4 space-y-4">
        <div>
          <label class="text-sm">Program</label>
          <select name="program" class="form-input">
            <option value="">- Tidak diubah -</option>
            <option value="MANDIRI">MANDIRI</option>
            <option value="BEASISWA">BEASISWA</option>
          </select>
        </div>

        <div>
          <label class="text-sm">Status</label>
          <select name="status" class="form-input">
            <option value="">- Tidak diubah -</option>
            <option value="formulir">Formulir</option>
            <option value="diterima">Diterima</option>
            <option value="lulus">Lulus</option>
          </select>
        </div>
      </div>

      <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50">
        <button type="button" onclick="closeEditMasal()" class="px-4 py-2 border rounded-lg">
          Batal
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
          Simpan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit Santri -->
<div id="modalEditSantri"
     class="fixed inset-0 bg-black/40 hidden z-50 flex items-center justify-center">

  <div class="bg-white w-full max-w-4xl rounded-xl shadow-lg">

    <!-- HEADER -->
    <div class="flex justify-between items-center px-6 py-4 border-b">
      <h3 class="font-semibold text-lg text-gray-800">
        Edit Data Santri (PSB)
      </h3>
      <button onclick="closeEditSantri()" class="text-gray-500 hover:text-red-600">✕</button>
    </div>

    <form action="<?= base_url('psb/updateSantri') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="id" id="edit_id">

      <!-- ACCORDION -->
      <div class="divide-y">

        <!-- ================= IDENTITAS ================= -->
        <details open class="group">
          <summary class="accordion-title">
            Identitas Santri
          </summary>

          <div class="accordion-body grid md:grid-cols-3 gap-4">
            <div>
              <label>NISN</label>
              <input id="edit_nisn" name="nisn" class="form-input">
            </div>
            
            <div>
              <label>Nama</label>
              <input id="edit_nama" name="nama" class="form-input">
            </div>
            
            <div>
              <label>Jenjang</label>
              <select id="edit_jenjang" name="jenjang" class="form-input">
                <option value="MTs">MTs</option>
                <option value="MA">MA</option>
              </select>
            </div>

            <div>
              <label>Program</label>
              <input id="edit_program" name="program" class="form-input">
            </div>

            <div>
              <label>Kelas</label>
              <input id="edit_kelas" name="kelas" class="form-input">
            </div>

            <div>
              <label>Tahun Masuk</label>
              <input id="edit_tahunmasuk" name="tahunmasuk" class="form-input">
            </div>

            <div>
              <label>Status</label>
              <select id="edit_status" name="status" class="form-input">
                <option value="formulir">Formulir</option>
                <option value="diterima">Diterima</option>
                <option value="lulus">Lulus</option>
              </select>
            </div>
          </div>
        </details>

        <!-- ================= PEMBAYARAN ================= -->
        <details class="group">
          <summary class="accordion-title">
            Pembayaran & Administrasi
          </summary>

          <div class="accordion-body grid md:grid-cols-4 gap-4">
            <div>
              <label>Formulir</label>
              <input id="edit_formulir" name="formulir" type="number" class="form-input">
            </div>

            <div>
              <label>SPP</label>
              <input id="edit_spp" name="spp" type="number" class="form-input">
            </div>

            <div>
              <label>DU</label>
              <input id="edit_tunggakandu" name="tunggakandu" type="number" class="form-input">
            </div>

            <div>
              <label>Rekening</label>
              <select id="edit_rekening" name="rekening" class="form-input">
                <option>Muamalat Salam</option>
                <option>BSI</option>
                <option>Jatim Syariah</option>
                <option>Tunai</option>
                <option>Lain-lain</option>
              </select>
            </div>
          </div>
        </details>

        <!-- ================= ORANG TUA ================= -->
        <details class="group">
          <summary class="accordion-title">
            Data Orang Tua
          </summary>

          <div class="accordion-body grid md:grid-cols-2 gap-4">
            <div>
              <label>Nama Ayah</label>
              <input id="edit_ayah" name="ayah" class="form-input">
            </div>

            <div>
              <label>Pekerjaan Ayah</label>
              <input id="edit_pekerjaanayah" name="pekerjaanayah" class="form-input">
            </div>

            <div>
              <label>Nama Ibu</label>
              <input id="edit_ibu" name="ibu" class="form-input">
            </div>

            <div>
              <label>Pekerjaan Ibu</label>
              <input id="edit_pekerjaanibu" name="pekerjaanibu" class="form-input">
            </div>

            <div>
              <label>Kontak 1</label>
              <input id="edit_kontak1" name="kontak1" class="form-input">
            </div>

            <div>
              <label>Kontak 2</label>
              <input id="edit_kontak2" name="kontak2" class="form-input">
            </div>
          </div>
        </details>

      </div>

      <!-- FOOTER -->
      <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50">
        <button type="button"
                onclick="closeEditSantri()"
                class="px-4 py-2 text-sm border rounded-lg">
          Batal
        </button>
        <button type="submit"
                class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg">
          Simpan
        </button>
      </div>

    </form>
  </div>
</div>

<!--js-->
<script>
/* =====================================================
   UTILITAS
===================================================== */
function formatRibuan(angka) {
  if (!angka) return '';
  return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function unformatRibuan(nilai) {
  return nilai ? nilai.replace(/\./g, '') : '';
}

/* =====================================================
   CHECKBOX & EDIT MASAL
===================================================== */
function updateEditMasalButton() {
  const selected = $('.santri-check:checked').length;
  $('#btnEditMasal').prop('disabled', selected === 0);
  $('.jumlah-cek').text(selected);

  const ids = $('.santri-check:checked')
    .map(function () {
      return $(this).val();
    }).get();

  $('#edit_ids').val(ids.join(','));
}

// Pilih semua
$(document).on('change', '#checkAll', function () {
  const checked = this.checked;

  $('.santri-check').each(function () {
    $(this).prop('checked', checked);
    $(`label[for="${this.id}"]`).toggleClass('checked', checked);
  });

  updateEditMasalButton();
});

// Checkbox individual
$(document).on('change', '.santri-check', function () {
  $(`label[for="${this.id}"]`).toggleClass('checked', this.checked);
  updateEditMasalButton();
});

/* =====================================================
   MODAL EDIT MASAL
===================================================== */
function openEditMasal() {
  $('#modalEditMasal').removeClass('hidden');
}

function closeEditMasal() {
  $('#modalEditMasal').addClass('hidden');
}

/* =====================================================
   MODAL EDIT INDIVIDUAL
===================================================== */
function openEditSantri(data) {
  $('#modalEditSantri').removeClass('hidden');

  Object.keys(data).forEach(key => {
    const el = document.getElementById('edit_' + key);
    if (el) el.value = data[key] ?? '';
  });
}

function closeEditSantri() {
  $('#modalEditSantri').addClass('hidden');
}

/* =====================================================
   FORMAT INPUT UANG
===================================================== */
$('#editSPPView').on('input', function () {
  const nilai = $(this).val().replace(/[^\d]/g, '');
  $(this).val(formatRibuan(nilai));
  $('#editSPP').val(unformatRibuan($(this).val()));
});

$('#editDaftarulangView').on('input', function () {
  const nilai = $(this).val().replace(/[^\d]/g, '');
  $(this).val(formatRibuan(nilai));
  $('#editDaftarulang').val(unformatRibuan($(this).val()));
});

/* =====================================================
   EDIT SANTRI (AJAX)
===================================================== */
$(document).on('click', '.btn-edit', function () {
  const id = $(this).data('id');

  $.get('<?= base_url('psb/getSantriById/') ?>' + id, function (res) {
    if (!res.status) {
      alert(res.msg || 'Data tidak ditemukan.');
      return;
    }

    const s = res.data;

    Object.keys(s).forEach(key => {
      const el = $('#edit' + key.charAt(0).toUpperCase() + key.slice(1));
      if (el.length) el.val(s[key]);
    });

    $('#editSPPView').val(formatRibuan(s.spp));
    $('#editDaftarulangView').val(formatRibuan(s.daftarulang));

    $('#modalEditSantri').modal('show');
  });
});

// Submit edit individual
$(document).on('submit', '#formEditSantri', function (e) {
  e.preventDefault();

  $.post('<?= base_url('psb/updateSantri') ?>', $(this).serialize(), function (res) {
    alert(res.msg);
    if (res.status) {
      $('#modalEditSantri').modal('hide');
      location.reload();
    }
  });
});

/* =====================================================
   MIGRASI SANTRI
===================================================== */
$('#btnMigrasiSantri').on('click', function () {
  const ids = $('.santri-check:checked').map(function () {
    return $(this).val();
  }).get();

  if (ids.length === 0) return alert('Pilih minimal satu santri.');

  if (!confirm('Yakin ingin migrasi data ke tabel santri?')) return;

  $.post('<?= base_url('psb/migrasiKeSantri') ?>', { ids }, function (res) {
    alert(res.message || res.msg);
    if (res.status) location.reload();
  }, 'json');
});

/* =====================================================
   AKSI MASAL
===================================================== */
function aksiMasal(url, data, confirmMsg) {
  if (confirmMsg && !confirm(confirmMsg)) return;

  $.post(url, data, function (res) {
    alert(res.msg);
    if (res.status) location.reload();
  });
}

$('.btn-kurangi-spp').on('click', () =>
  aksiMasal('<?= base_url('Santri/kurangiSPPMasal') ?>', { ids: $('#edit_ids').val().split(',') })
);

$('.btn-migrasi-masal').on('click', function () {
  const kelas = $('#kelasTujuan').val();
  if (!kelas) return alert('Kelas tujuan harus diisi.');

  aksiMasal('<?= base_url('Santri/migrasiMasal') ?>', {
    ids: $('#edit_ids').val().split(','),
    kelas_baru: kelas
  });
});

$('.btn-arsip-masal').on('click', () =>
  aksiMasal(
    '<?= base_url('Santri/arsipMasal') ?>',
    { ids: $('#edit_ids').val().split(',') },
    'Yakin ingin mengarsipkan santri terpilih?'
  )
);

$('.btn-angkatan').on('click', function () {
  const tahun = $('#TahunMasuk').val();
  if (!tahun || isNaN(tahun)) return alert('Tahun masuk tidak valid.');

  aksiMasal('<?= base_url('Santri/gantiTahunMasuk') ?>', {
    ids: $('#edit_ids').val().split(','),
    tahun
  });
});
</script>
