<?php

use App\Models\TransferModel;
use App\Models\AlumniModel;

$transferModel = new TransferModel();
$santriModel = new AlumniModel();
$data = $transferModel->where('idtrans', $id)->first();
$datsan = $santriModel->where('nama', $data['nama'])->first();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/dh.png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 300px;
            height: auto;
            opacity: 0.05;
            transform: translate(-50%, -50%);
            background-image: url('assets/images/logos/dh.png');
            background-repeat: no-repeat;
            background-size: contain;
            z-index: 0;
            pointer-events: none;
        }

        header {
            background: linear-gradient(to right, #1B4332, #2D6A4F);
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        main {
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        h1 {
            margin: 0;
            font-size: 26px;
        }

        p {
            margin: 8px 0;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
            font-size: 16px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
        }

        th {
            background-color: #f0f0f0;
        }

        .highlight {
            background-color: #F4C430;
            font-weight: bold;
            color: #1B4332;
        }

        footer {
            background-color: #F5F5F5;
            padding: 20px 30px;
            text-align: center;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 45%;
            text-align: right;
        }

        .btn-download {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #1B4332;
            color: white;
            padding: 12px 24px;
            font-family: 'Poppins', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: background 0.3s ease;
        }

        .btn-download:hover {
            background-color: #14532d;
        }
        table.detail-pembayaran {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-radius: 8px;
            overflow: hidden;
        }
        
        table.detail-pembayaran th,
        table.detail-pembayaran td {
            padding: 14px;
            border: none;
            font-size: 15px;
        }
        
        table.detail-pembayaran th {
            background-color: #f9f9f9;
            text-align: left;
            font-weight: 600;
            color: #333;
        }
        
        table.detail-pembayaran td {
            background-color: #fff;
        }
        
        table.detail-pembayaran tr:last-child td {
            background-color: #F4C430;
            font-weight: bold;
            color: #1B4332;
        }
        .btn-kembali {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #1B4332;
            color: white;
            padding: 12px 24px;
            font-family: 'Poppins', sans-serif;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
            z-index: 9999;
        }
        
        .btn-kembali:hover {
            background-color: #14532d;
        }
    </style>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   </head>

<body>
    <div class="watermark"></div>

    <header style="display: flex; align-items: center; justify-content: space-between; padding: 20px 30px; border-bottom: 4px solid #F4C430;">
        <div style="text-align: left;">
            <h1 style="margin: 0; font-size: 24px; color: white;">Kwitansi Pembayaran</h1>
            <p style="margin: 4px 0 0; font-size: 14px; color: #e0e0e0;">
                Darul Hijrah Salam<br>
                Jl. Ketanireng, Prigen, Pasuruan
            </p>
        </div>
        <img src="assets/images/logo.png" alt="Logo" style="height: 60px;" />
    </header>

    <main>
        <p>Assalamu'alaikum Wr. Wb.</p>
        <p>Alhamdulillah, telah kami terima amanah dari Bapak/Ibu Wali Santri atas nama:</p>

        <table>
            <tr>
                <td><strong>Nama Santri</strong></td>
                <td><?= $data['nama']; ?></td>
            </tr>
            <tr>
                <td><strong>Alumni</strong></td>
                <td><?= $datsan['jenjang']; ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal Pembayaran</strong></td>
                <td><?php setlocale(LC_TIME, 'id_ID'); echo strftime('%d %B %Y', strtotime($data['tanggal'])); ?></td>
            </tr>
        </table>

        <p style="margin-top: 25px;">Dengan rincian sebagai berikut:</p>

        <table class="detail-pembayaran">
          <tr>
            <th>Jumlah Pembayaran</th>
            <th>Rekening</th>
            <th>Keterangan</th>
          </tr>
          <tr>
            <td><?= format_rupiah($data['saldomasuk']); ?></td>
            <td><?= $data['rekening']; ?></td>
            <td><?= $data['keterangan']; ?></td>
          </tr>
        </table>

        <p style="margin-top: 25px;"><strong>Kekurangan Kewajiban:</strong></p>
                    <?php if ($datsan['tunggakanspp'] < 0) : $datsan['tunggakanspp'] = 0;
                    endif ?>
                    <?php if ($datsan['tunggakantl'] < 0) : $datsan['tunggakantl'] = 0;
                    endif ?>
                    <?php if ($datsan['tunggakandu'] < 0) : $datsan['tunggakandu'] = 0;
                    endif ?>
        <ul>
            <li>SPP: <strong><?= format_rupiah($datsan['tunggakanspp']); ?></strong></li>
            <li>Tunggakan: <strong><?= format_rupiah($datsan['tunggakantl']); ?></strong></li>
            <li>Daftar Ulang: <strong><?= format_rupiah($datsan['tunggakandu']); ?></strong></li>
        </ul>

        <div class="signature">
            <div></div>
            <div>
                Mengetahui,<br><br>
                <strong>Keuangan Darul Hijrah Salam</strong><br><br><br>
            </div>
        </div>
    </main>

    <footer>
        <p>Jazakumullah Khoiron atas pembayarannya. Semoga barokah dan selalu dilancarkan rezekinya. Aamiin</p>
        <p>Wassalamu’alaikum Wr. Wb.</p>
    </footer>

    <button id="downloadBtn"
        class="btn-download"
        style="z-index: 9999;"
        data-html2canvas-ignore="true"
        onclick="downloadKwitansi()">
        Download Kwitansi
    </button>
    <button onclick="window.location.href='./'" class="btn-kembali" data-html2canvas-ignore="true">
        ← Kembali ke Keuangan
    </button>

</body>
    <script>
        const kwitansiData = {
            idtrans: "<?= $data['idtrans']; ?>",
            nama: "<?= preg_replace('/[^a-zA-Z0-9_\-]/', '_', $data['nama']); ?>",
            tanggal: "<?= date('Y-m-d', strtotime($data['tanggal'])); ?>"
        };
    </script>

    <script>
    function downloadKwitansi() {
        const button = document.getElementById('downloadBtn');
            html2canvas(document.body).then(canvas => {
                const link = document.createElement('a');
                link.download = `${kwitansiData.idtrans}-${kwitansiData.nama}-${kwitansiData.tanggal}.png`;
                link.href = canvas.toDataURL();
                link.click();

                button.style.display = 'block';
            });
    }
    </script>
</html>