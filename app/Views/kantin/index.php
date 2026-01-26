<?= $this->extend('template'); ?>

<?= $this->section('konten'); ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-8 mx-auto">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h4 class="card-title fw-bold mb-3">📶 Pembaca NFC (Format 32-bit Little-Endian)</h4>
          <p class="text-muted mb-4">
            Tekan <b>Mulai Scan</b> lalu tempelkan kartu NFC ke belakang ponsel Android (Chrome). 
            Hasil UID akan dikonversi ke format <b>decimal 10 digit (Little-Endian)</b>.
          </p>

          <!-- Tombol kontrol -->
          <div class="mb-3 d-flex gap-2">
            <button id="btnStart" class="btn btn-primary">▶️ Mulai Scan</button>
            <button id="btnStop" class="btn btn-outline-secondary" disabled>⏹️ Stop</button>
          </div>

          <!-- Status -->
          <div id="statusBox" class="alert alert-secondary py-2 px-3 d-flex align-items-center gap-2 mb-4">
            <span id="dot" class="badge bg-secondary rounded-circle p-2"></span>
            <span id="statusText">Menunggu...</span>
          </div>

          <!-- Output -->
          <form>
            <div class="input-group">
              <input type="text" id="nfcOutput" class="form-control text-monospace fw-bold" readonly placeholder="Hasil UID akan muncul di sini">
              <button type="button" id="btnCopy" class="btn btn-outline-success" disabled>📋 Copy</button>
            </div>
          </form>

          <!-- Log -->
          <div class="mt-4">
            <h6 class="fw-semibold mb-2">Log</h6>
            <div id="log" class="border rounded bg-dark text-light p-2" 
                 style="max-height:220px;overflow:auto;font-size:14px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const $ = sel => document.querySelector(sel);
  const logBox = $('#log');
  const statusText = $('#statusText');
  const dot = $('#dot');
  const outputEl = $('#nfcOutput');
  const copyBtn = $('#btnCopy');

  let reader = null;
  let abortCtrl = null;

  function setStatus(text, mode){
    statusText.textContent = text;
    dot.className = 'badge rounded-circle p-2 bg-' + (mode || 'secondary');
    const statusBox = $('#statusBox');
    statusBox.className = 'alert alert-' + (mode || 'secondary') + ' py-2 px-3 d-flex align-items-center gap-2 mb-4';
  }

  function appendLog(msg){
    const time = new Date().toLocaleTimeString();
    const line = document.createElement('div');
    line.textContent = `[${time}] ${msg}`;
    logBox.prepend(line);
  }

  // Konversi UID ke 32-bit Little Endian → Decimal 10 digit
  function uidToLittleEndianDec(uid){
    // hapus ":" kalau ada
    const clean = uid.replace(/:/g,'').replace(/\s+/g,'');
    if (clean.length < 8) return '-';
    // potong 4 byte
    const bytes = clean.match(/.{1,2}/g);
    // balik urutan byte
    const reversed = bytes.reverse().join('');
    // konversi ke decimal
    return BigInt("0x" + reversed).toString().padStart(10,"0");
  }

  async function startScan(){
    if (!('NDEFReader' in window)){
      setStatus('Browser tidak mendukung Web NFC. Gunakan Chrome Android & HTTPS.', 'danger');
      appendLog('NDEFReader tidak tersedia.');
      $('#btnStart').disabled = false;
      return;
    }
    try{
      abortCtrl = new AbortController();
      reader = new NDEFReader();
      await reader.scan({ signal: abortCtrl.signal });

      setStatus('Sedang menunggu tag... Dekatkan kartu ke ponsel.', 'warning');
      $('#btnStart').disabled = true;
      $('#btnStop').disabled = false;
      appendLog('Scan dimulai.');

      reader.onreadingerror = (ev) => {
        setStatus('Gagal membaca. Coba tempel ulang kartu.', 'danger');
        appendLog('onreadingerror: ' + (ev?.message || 'unknown'));
      };

      reader.onreading = (event) => {
        setStatus('Tag terbaca ✅', 'success');
        const serial = event.serialNumber || '-';
        const dec10 = uidToLittleEndianDec(serial);
        outputEl.value = dec10;
        copyBtn.disabled = dec10 === '-' ? true : false;
        appendLog('UID raw: ' + serial + ' → LE 10 digit: ' + dec10);
      };
    } catch(err){
      console.error(err);
      appendLog('Error: ' + err);
      setStatus('Tidak bisa mulai scan: ' + (err?.message||err), 'danger');
      $('#btnStart').disabled = false;
      $('#btnStop').disabled = true;
    }
  }

  function stopScan(){
    try {
      abortCtrl?.abort();
      appendLog('Scan dihentikan.');
    } catch {}
    setStatus('Scan dihentikan.', 'secondary');
    $('#btnStart').disabled = false;
    $('#btnStop').disabled = true;
  }

  $('#btnStart').addEventListener('click', startScan);
  $('#btnStop').addEventListener('click', stopScan);

  // Tombol copy
  copyBtn.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(outputEl.value);
      copyBtn.textContent = "✅ Copied";
      setTimeout(()=>copyBtn.textContent="📋 Copy", 1500);
    } catch(err){
      console.error("Copy gagal:", err);
    }
  });

  if (!('NDEFReader' in window)){
    setStatus('Peramban tidak mendukung Web NFC. Gunakan Chrome Android & HTTPS.', 'danger');
  } else {
    setStatus('Siap. Tekan Mulai Scan.', 'secondary');
  }
</script>

<?= $this->endSection(); ?>