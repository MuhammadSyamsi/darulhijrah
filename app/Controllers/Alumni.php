<?php

namespace App\Controllers;

use App\Models\AlumniModel;
use App\Models\DetailModel;
use App\Models\PsbModel;
use App\Models\TransferModel;
use App\Models\SantriModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Alumni extends BaseController
{

    public function tambah()
    {

        $cariNama = new AlumniModel();
        $data['cari'] = $cariNama->findAll();
        return view('alumni/insert', $data);
    }

    public function save()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $postKewajiban = new AlumniModel();
        $postModel->insert([
            'idtrans' => $this->request->getPost('id'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'program' => $this->request->getPost('program'),
            'kelas' => $this->request->getPost('kelas'),
            'saldomasuk' => $this->request->getPost('saldomasuk'),
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'rekening' => $this->request->getPost('rekening')
        ]);

        $postDetail->insert([
            'id' => $this->request->getPost('id'),
            'program' => $this->request->getPost('program'),
            'tanggal' => $this->request->getPost('tanggal'),
            'rekening' => $this->request->getPost('rekening'),
            'daftarulang' => $this->request->getPost('tunggakandu'),
            'tunggakan' => $this->request->getPost('tunggakantl'),
            'spp' => $this->request->getPost('tunggakanspp'),
            'uangsaku' => $this->request->getPost('uangsaku'),
            'infaq' => $this->request->getPost('infaq'),
            'formulir' => $this->request->getPost('formulir')
        ]);

        $hitungDu = 0;
        $hitungTl = 0;
        $hitungSpp = 0;
        $spp = $postKewajiban->where('nisn', $this->request->getPost('nisn'))->findAll();
        foreach ($spp as $ts) {
            $hitungDu = $ts['tunggakandu'] - $this->request->getPost('tunggakandu');
            $hitungTl = $ts['tunggakantl'] - $this->request->getPost('tunggakantl');
            $hitungSpp = $ts['tunggakanspp'] - $this->request->getPost('tunggakanspp');
        };
        $postKewajiban->save([
            'nisn' => $this->request->getPost('nisn'),
            'tunggakandu' => $hitungDu,
            'tunggakantl' => $hitungTl,
            'tunggakanspp' => $hitungSpp
        ]);

    $id = $this->request->getPost('id');
    return redirect()->to('/kwitansi-alumni/' . $id);
    }

    public function cetak()
    {

        $data = explode(",", $_POST["img"]);
        $data = base64_decode($data[1]);

        $file = fopen("kwitansi/kwitansi.png", "w");
        fwrite($file, $data);
        fclose($file);
    }

    public function tunggakan()
    {
        $santriModel = new AlumniModel();
        $tunggakan = $santriModel->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'nama')
            ->setCellValue('B1', 'program')
            ->setCellValue('C1', 'jenjang')
            ->setCellValue('D1', 'kelas')
            ->setCellValue('E1', 'tunggakan daftar ulang')
            ->setCellValue('F1', 'tunggakan sebelumnya')
            ->setCellValue('G1', 'tunggakan spp')
            ->setCellValue('H1', 'kewajiban spp')
            ->setCellValue('I1', 'kewajiban du');

        $column = 2;
        // tulis data
        foreach ($tunggakan as $d) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $d['nama'])
                ->setCellValue('B' . $column, $d['program'])
                ->setCellValue('C' . $column, $d['jenjang'])
                ->setCellValue('D' . $column, $d['kelas'])
                ->setCellValue('E' . $column, $d['tunggakandu'])
                ->setCellValue('F' . $column, $d['tunggakantl'])
                ->setCellValue('G' . $column, $d['tunggakanspp'])
                ->setCellValue('H' . $column, $d['spp'])
                ->setCellValue('I' . $column, $d['du']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Tunggakan Alumni';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    public function dtransaksi($idtrans)
    {
        $transfer = new TransferModel();
        $detailMod = new DetailModel();
        $santri = new AlumniModel();
        $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
        $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
        $data['detail'] = $detailMod->where('id', $idtrans)->find();
        $data['santri'] = $santri->where('nama', $nama)->find();
        return view('alumni/edit_transaksi', $data);
    }

    public function edit()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $santri = new SantriModel();
        $postModel->save([
            'idtrans' => $this->request->getPost('idtrans'),
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'kelas' => $this->request->getPost('kelas'),
            'saldomasuk' => $this->request->getPost('saldomasuk'),
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
            'rekening' => $this->request->getPost('rekening')
        ]);
        $postDetail->save([
            'id' => $this->request->getPost('idtrans'),
            'tanggal' => $this->request->getPost('tanggal'),
            'daftarulang' => $this->request->getPost('du'),
            'tunggakan' => $this->request->getPost('tunggakan'),
            'spp' => $this->request->getPost('spp'),
            'uangsaku' => $this->request->getPost('uangsaku'),
            'infaq' => $this->request->getPost('infaq'),
            'formulir' => $this->request->getPost('formulir'),
            'rekening' => $this->request->getPost('rekening')
        ]);
        $santri->save([
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'tunggakandu' => $this->request->getPost('santridu'),
            'tunggakantl' => $this->request->getPost('santritl'),
            'tunggakanspp' => $this->request->getPost('santrispp')
        ]);

        $data = [
            'id' => $this->request->getPost('idtrans'),
        ];

        return view('pages/kwitansi', $data);
    }

    public function delet($idtrans)
    {
        $transferModel = new TransferModel();
        $detailModel = new DetailModel();
        $transferModel->delete($idtrans);
        $detailModel->delete($idtrans);

        echo '<script>alert("Proses berhasil dilakukan!");</script>';

        return redirect()->to(base_url('/riwayat-pembayaran'));
    }
}
