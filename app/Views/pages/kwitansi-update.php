<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="max-w-4xl mx-auto px-4 py-6">
  <div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div id="kwitansi-area" class="p-6">

      <!-- Header -->
      <div class="flex items-center justify-between p-4 rounded-md mb-6" style="background: linear-gradient(135deg, #2e7d32, #1b5e20); border-bottom: 4px solid #fbc02d;">
        <div class="text-white">
          <h4 class="text-xl font-semibold">Kwitansi Pembayaran</h4>
          <p class="text-sm opacity-90">Darul Hijrah Salam<br>Jl. Ketanireng, Prigen, Pasuruan</p>
        </div>
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" class="h-14">
      </div>

      <!-- Identitas -->
      <p>Assalamu'alaikum Wr. Wb.</p>
      <p class="mt-2">Alhamdulillah, telah kami terima amanah dari Bapak/Ibu Wali Santri atas nama:</p>

      <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
        <div>
          <div class="flex">
            <div class="w-40 font-semibold">Nama Santri</div>
            <div><?= $transfer['nama']; ?></div>
          </div>

          <div class="flex mt-2">
            <div class="w-40 font-semibold">Jenjang / Kelas</div>
            <div><?= isset($santri['jenjang']) ? $santri['jenjang'].' / '.$santri['kelas'] : '-' ?></div>
          </div>
        </div>

        <div>
          <div class="flex">
            <div class="w-40 font-semibold">Tanggal Pembayaran</div>
            <div><?= tanggal_indo($transfer['tanggal']); ?></div>
          </div>
        </div>
      </div>

      <p class="mt-6">Dengan rincian sebagai berikut:</p>

      <!-- Table -->
      <div class="mt-2 overflow-x-auto">
        <table class="min-w-full border border-gray-200 text-sm">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2 text-left">Jumlah Pembayaran</th>
              <th class="px-4 py-2 text-left">Rekening</th>
              <th class="px-4 py-2 text-left">Keterangan</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-t">
              <td class="px-4 py-3"><?= format_rupiah($transfer['saldomasuk']); ?></td>
              <td class="px-4 py-3"><?= $transfer['rekening']; ?></td>
              <td class="px-4 py-3"><?= $transfer['keterangan']; ?></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Kekurangan -->
      <p class="mt-6">Adapun setelah pembayaran ini, masih terdapat kekurangan kewajiban yang perlu diselesaikan sebagai berikut:</p>
      <ul class="mt-2 list-disc list-inside text-sm">
        <li>SPP: <strong><?= format_rupiah(max(0, $santri['tunggakanspp'] ?? 0)); ?></strong></li>
        <li>Daftar Ulang Kelas 1: <strong><?= format_rupiah(max(0, $santri['tunggakandu'] ?? 0)); ?></strong></li>
        <li>Daftar Ulang Kelas 2: <strong><?= format_rupiah(max(0, $santri['tunggakandu2'] ?? 0)); ?></strong></li>
        <li>Daftar Ulang Kelas 3: <strong><?= format_rupiah(max(0, $santri['tunggakandu3'] ?? 0)); ?></strong></li>
      </ul>

      <!-- Tanda Tangan -->
      <div class="mt-8 text-right text-sm">
        Mengetahui,<br><br><br>
        <strong>Keuangan Darul Hijrah Salam</strong>
      </div>

    </div>
  </div>

  <!-- Tombol -->
  <div class="flex justify-end mt-6">
    <button 
      id="btnKwitansi"
      class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow"
    >
      <span class="material-symbols-outlined">download</span>
      Download Kwitansi
    </button>
  </div>
</div>

<!-- Script download -->
  
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  
<script>
  
function downloadAndSendWa() {
  
    const area = document.getElementById('kwitansi-area');
  

  
    html2canvas(area).then(canvas => {
  
        // 1. Download gambar kwitansi
  
        const link = document.createElement('a');
  
        link.download = "kwitansi-<?= $transfer['idtrans'] ?>-<?= $transfer['nama'] ?>.png";
  
        link.href = canvas.toDataURL("image/png");
  
        link.click();
  

  
        // 2. Buka WhatsApp dengan pesan
  
        const nomor = "<?= $santri['kontak1'] ?>"; // ex: 6281234567890
  
        const pesan = encodeURIComponent("Jazakallah khoir atas pembayarannya. Semoga sehat selalu dan dilancarkan rizkinya. Aamiin");
  
        window.open(`https://wa.me/${nomor}?text=${pesan}`, "_blank");
  
    });
  
}
  

  
document.getElementById('btnKwitansi').addEventListener('click', downloadAndSendWa);
  
</script>
  
<?= $this->endSection(); ?>