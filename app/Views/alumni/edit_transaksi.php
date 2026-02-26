<?= $this->extend('template'); ?>
<?= $this->section('konten'); ?>

<div class="w-full pb-24">
    <div class="flex justify-center">
        <div class="w-full lg:w-10/12">

            <div class="backdrop-blur-md bg-white/60 shadow-md rounded-2xl p-5 mt-4 border border-white/40">

                <?php foreach ($edit as $c) : ?>

                <!-- Header -->
                <h2 class="text-xl font-bold text-slate-700 flex items-center gap-2 mb-1">
                    <span class="material-symbols-outlined">edit</span>
                    Edit Transaksi Alumni
                </h2>
                <p class="text-sm text-slate-500 mb-5"><?= $c['nama']; ?></p>

                <form action="<?= base_url('edittransalumni'); ?>" method="post" class="space-y-6">
                    <?= csrf_field(); ?>

                    <!-- INPUT UTAMA -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <div>
                            <label class="text-xs text-slate-600">Saldo Masuk</label>
                            <input type="text" id="saldomasuk" name="saldomasuk"
                                value="<?= $c['saldomasuk']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Tanggal</label>
                            <input type="date" name="tanggal"
                                value="<?= $c['tanggal']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Rekening</label>
                            <select name="rekening"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                                <option selected><?= $c['rekening']; ?></option>
                                <option>Muamalat Salam</option>
                                <option>Jatim Syariah</option>
                                <option>BSI</option>
                                <option>Uang Saku</option>
                                <option>Muamalat Yatim</option>
                                <option>Tunai</option>
                                <option>Lain-lain</option>
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="text-xs text-slate-600">Keterangan</label>
                            <input type="text" name="keterangan"
                                value="<?= $c['keterangan']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>

                            <input type="hidden" name="nama" value="<?= $c['nama']; ?>">
                            <input type="hidden" name="idtrans" value="<?= $c['idtrans']; ?>">
                            <input type="hidden" name="nisn" value="<?= $c['nisn']; ?>">
                            <input type="hidden" name="kelas" value="<?= $c['kelas']; ?>">
                        </div>

                    </div>
                <?php endforeach; ?>


                <!-- TUNGGAKAN -->
                <div class="border-t pt-6">
                    <h3 class="flex items-center gap-2 font-semibold text-slate-700 mb-3">
                        <span class="material-symbols-outlined">warning</span>
                        Edit Tunggakan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php foreach ($santri as $s) : ?>

                        <div>
                            <label class="text-xs text-slate-600">Tunggakan Daftar Ulang</label>
                            <input type="text" id="santridu" name="santridu"
                                value="<?= $s['tunggakandu']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Tunggakan Lain-lain</label>
                            <input type="text" id="santritl" name="santritl"
                                value="<?= $s['tunggakantl']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Tunggakan SPP</label>
                            <input type="text" id="santrispp" name="santrispp"
                                value="<?= $s['tunggakanspp']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                        </div>

                        <?php endforeach; ?>
                    </div>
                </div>


                <!-- DETAIL -->
                <div class="border-t pt-6">
                    <h3 class="flex items-center gap-2 font-semibold text-slate-700 mb-3">
                        <span class="material-symbols-outlined">credit_card</span>
                        Detail Pembayaran
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php foreach ($detail as $d) : ?>

                        <input type="text" id="du" name="du" value="<?= $d['daftarulang']; ?>" class="hidden">

                        <div>
                            <label class="text-xs text-slate-600">Bayar Daftar Ulang</label>
                            <input type="text" id="du" name="du" value="<?= $d['daftarulang']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Bayar Tunggakan</label>
                            <input type="text" id="tunggakan" name="tunggakan" value="<?= $d['tunggakan']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Bayar SPP</label>
                            <input type="text" id="spp" name="spp" value="<?= $d['spp']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Uang Saku</label>
                            <input type="text" id="uangsaku" name="uangsaku" value="<?= $d['uangsaku']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Infaq</label>
                            <input type="text" id="infaq" name="infaq" value="<?= $d['infaq']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="text-xs text-slate-600">Formulir</label>
                            <input type="text" id="formulir" name="formulir" value="<?= $d['formulir']; ?>"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-slate-300 bg-white text-sm focus:ring-2 focus:ring-blue-400">
                        </div>

                        <?php endforeach; ?>
                    </div>
                </div>


                <!-- STICKY BUTTON -->
                <div class="fixed bottom-0 left-0 right-0 bg-white border-t py-2 px-5 flex justify-between items-center z-50">
                    <a href="<?= base_url('riwayat-pembayaran'); ?>"
                        class="px-5 py-2 rounded-xl border border-amber-500 text-amber-600 text-sm font-medium flex items-center gap-2">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali
                    </a>

                    <button type="submit"
                        class="px-6 py-2 rounded-xl bg-green-600 text-white text-sm font-medium flex items-center gap-2 shadow">
                        <span class="material-symbols-outlined">save</span>
                        Simpan
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

  const numericFields = [
    'saldomasuk','santridu','santrispp','santritl',
    'du','tunggakan','spp','uangsaku','infaq','formulir'
  ];

  numericFields.forEach(id => {
    const input = document.getElementById(id);
    if (input) {
      new Cleave(input, {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalMark: ',',
        delimiter: '.',
        numeralPositiveOnly: false // <-- penting: izinkan minus
      });
    }
  });

  // Bersihkan saat submit
  document.querySelector('form').addEventListener('submit', function () {
    numericFields.forEach(id => {
      const input = document.getElementById(id);
      if (input) {
        input.value = input.value
          .replace(/\./g, '')
          .replace(/,/g, '');
      }
    });
  });

});
</script>
<?= $this->endSection(); ?>