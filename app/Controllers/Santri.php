<?php

namespace App\Controllers;

use App\Models\DetailModel;
use App\Models\PsbModel;
use App\Models\AlumniModel;
use App\Models\TransferModel;
use App\Models\SantriModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\Echo_;

class Santri extends BaseController
{
  public function data()
{
  $santriModel = new SantriModel();

  // Hitungan total
  $totalAll = $santriModel->selectCount('nama')->whereNotIn('kelas', ['keluar', 'lulus'])->first()['nama'] ?? 0;
  $totalMts = $santriModel->selectCount('nama')->where('jenjang', 'mts')->whereNotIn('kelas', ['keluar', 'lulus'])->first()['nama'] ?? 0;
  $totalMa  = $santriModel->selectCount('nama')->where('jenjang', 'ma')->whereNotIn('kelas', ['keluar', 'lulus'])->first()['nama'] ?? 0;

  // Jenjang & kelas
  $jenjangList = $santriModel->select('jenjang')->groupBy('jenjang')->findAll();
  $kelasByJenjang = [];
  foreach ($jenjangList as $j) {
    $kelas = $santriModel->select('kelas')->where('jenjang', $j['jenjang'])->groupBy('kelas')->orderBy('kelas')->findAll();
    $kelasByJenjang[$j['jenjang']] = array_column($kelas, 'kelas');
  }

  // Filter AJAX
  $keyword = $this->request->getVar('keyword');
  $jenjang = $this->request->getVar('jenjang');
  $kelas   = $this->request->getVar('kelas');

  $query = clone $santriModel;
  if ($jenjang) $query->where('jenjang', $jenjang);
  if ($kelas)   $query->where('kelas', $kelas);
  if ($keyword) $query->like('nama', $keyword);
  //$query->whereNotIn('kelas', ['keluar', 'lulus']);

  $santri = $query->findAll();

  if ($this->request->isAJAX()) {
    $html = view('santri/_card_list_santri', ['santri' => $santri]);

    if (trim($html) === '') {
      $html = '<div class="alert alert-warning">Data tidak ditemukan.</div>';
    }

    return $this->response->setHeader('Content-Type', 'text/html')->setBody($html);
  }

  // Untuk page awal
  return view('santri/data', [
    'kelasmts' => $santriModel->select('count(kelas) as hitung, kelas')->where('jenjang', 'mts')->groupBy('kelas')->findAll(),
    'kelasma'  => $santriModel->select('count(kelas) as hitung, kelas')->where('jenjang', 'ma')->groupBy('kelas')->findAll(),
    'total'    => $totalAll,
    'mts'      => $totalMts,
    'ma'       => $totalMa,
    'filterJenjang' => $jenjangList,
    'kelasByJenjang' => $kelasByJenjang,
  ]);
}

public function download()
{
    $santriModel = new SantriModel();
    $data = $santriModel->findAll();

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"data_santri.xls\"");

    $output = fopen("php://output", "w");
    fputcsv($output, array_keys($data[0]), "\t"); // header kolom

    foreach ($data as $row) {
        fputcsv($output, $row, "\t");
    }

    fclose($output);
    exit;
}

  public function alumni()
  {
    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $total = $santri->select('count(nama) as total')->where('kelas', 'lulus')->findAll();
    $totalmts = $santri->select('count(nama) as total')->where('jenjang', 'mts')->where('kelas', 'lulus')->findAll();
    $totalma = $santri->select('count(nama) as total')->where('jenjang', 'ma')->where('kelas', 'lulus')->findAll();
    foreach ($total as $total) : $totalb = $total['total'];
    endforeach;
    foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
    endforeach;
    foreach ($totalma as $totalma) : $ma = $totalma['total'];
    endforeach;
    $data = [
      'santri' => $santri->where('kelas', 'lulus')
        ->findAll(5),
      'alumni' => $alumni->select('kelas, count(nama) as total')
        ->groupBy('kelas')
        ->findAll(),
      'total' => $totalb,
      'mts' => $mts,
      'ma' => $ma,
    ];

    return view('santri/alumni', $data);
  }

public function psb()
{
  $dataPsb = new PsbModel();

  // Hitung Total per Jenjang
  $totalb = (clone $dataPsb)->where('status', 'diterima')->countAllResults();
  $mts    = (clone $dataPsb)->where(['jenjang' => 'mts', 'status' => 'diterima'])->countAllResults();
  $ma     = (clone $dataPsb)->where(['jenjang' => 'ma', 'status' => 'diterima'])->countAllResults();
  $statusList = (clone $dataPsb)->select('status')->groupBy('status')->findAll();

  // Jenjang & Kelas per Jenjang
  $jenjangList = (clone $dataPsb)->select('jenjang')->groupBy('jenjang')->findAll();
  $kelasByJenjang = [];
  foreach ($jenjangList as $j) {
    $kelas = (clone $dataPsb)
      ->select('kelas')
      ->where('jenjang', $j['jenjang'])
      ->groupBy('kelas')
      ->orderBy('kelas')
      ->findAll();
    $kelasByJenjang[$j['jenjang']] = array_column($kelas, 'kelas');
  }

  // Filter dari GET
  $keyword = $this->request->getVar('keyword');
  $jenjang = $this->request->getVar('jenjang');
  $kelas   = $this->request->getVar('kelas');
  $status  = $this->request->getVar('status');

  $query = new PsbModel();
  if ($jenjang) $query->where('jenjang', $jenjang);
  if ($kelas)   $query->where('kelas', $kelas);
  if ($status)  $query->where('status', $status);
  if ($keyword) $query->like('nama', $keyword);

  $santri = $query->findAll();

  // AJAX request (partial)
  if ($this->request->isAJAX()) {
    if (empty($santri)) {
      return $this->response->setBody('<div class="alert alert-warning">Data tidak ditemukan.</div>');
    }
    return view('santri/_card_list_psb', ['santri' => $santri]);
  }

  // View utama (load awal)
  $data = [
    'psb' => (clone $dataPsb)->where('status', 'diterima')->findAll(),
    'santri' => (clone $dataPsb)->select('kelas, count(nama) as total')
      ->groupBy('kelas')->findAll(),
    'total' => $totalb,
    'mts' => $mts,
    'ma' => $ma,
    'filterJenjang' => $jenjangList,
    'kelasByJenjang' => $kelasByJenjang,
    'statusList' => $statusList,
  ];

  return view('santri/psb', $data);
}

  public function pindah($nisn)
  {
    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $alumni->insert([
      'nisn' => $this->request->getPost('nisn'),
      'nama' => $this->request->getPost('nama'),
      'kelas' => $this->request->getPost('kelas'),
      'program' => $this->request->getPost('program'),
      'jenjang' => $this->request->getPost('jenjang'),
      'tunggakandu' => $this->request->getPost('tunggakandu'),
      'tunggakantl' => $this->request->getPost('tunggakantl'),
      'tunggakanspp' => $this->request->getPost('tunggakanspp'),
      'du' => $this->request->getPost('du'),
      'spp' => $this->request->getPost('spp'),
      'tahunajaran' => $this->request->getPost('tahunajaran')
    ]);
    $santri->delete($nisn);
    return redirect()->to(base_url('/santrialumni'));
  }

  public function lain()
  {
    $santri = new SantriModel();
    $alumni = new AlumniModel();
    $total = $santri->select('count(nama) as total')->where('kelas', 'keluar')->findAll();
    $totalmts = $santri->select('count(nama) as total')->where('jenjang', 'mts')->where('kelas', 'keluar')->findAll();
    $totalma = $santri->select('count(nama) as total')->where('jenjang', 'ma')->where('kelas', 'keluar')->findAll();
    foreach ($total as $total) : $totalb = $total['total'];
    endforeach;
    foreach ($totalmts as $totalmts) : $mts = $totalmts['total'];
    endforeach;
    foreach ($totalma as $totalma) : $ma = $totalma['total'];
    endforeach;
    $data = [
      'santri' => $santri->where('kelas', 'keluar')
        ->findAll(5),
      'alumni' => $alumni->select('kelas, count(nama) as total')
        ->groupBy('kelas')
        ->findAll(),
      'total' => $totalb,
      'mts' => $mts,
      'ma' => $ma,
    ];
    return view('santri/alumni', $data);
  }


  public function tambah()
  {

    $cariNama = new SantriModel();
    $data['cari'] = $cariNama->findAll();
    return view('pages/insert', $data);
  }

  public function save()
  {
    $postModel = new TransferModel();
    $postDetail = new DetailModel();
    $postKewajiban = new SantriModel();
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

    $data = [
      'id' => $this->request->getPost('id')
    ];
    return view('pages/kwitansi', $data);
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
    $santriModel = new SantriModel();
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
    $fileName = 'Tunggakan Santri';

    // Redirect hasil generate xlsx ke web client
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function transaksi()
  {
    $transferModel = new DetailModel();
    $transfer = $transferModel->orderBy('tanggal', 'desc')->findAll();

    $spreadsheet = new Spreadsheet();
    // tulis header/nama kolom 
    $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'tanggal')
      ->setCellValue('B1', 'daftar ulang')
      ->setCellValue('C1', 'tunggakan')
      ->setCellValue('D1', 'spp')
      ->setCellValue('E1', 'uang saku')
      ->setCellValue('F1', 'infaq')
      ->setCellValue('G1', 'formulir')
      ->setCellValue('H1', 'rekening')
      ->setCellValue('I1', 'program');

    $column = 2;
    // tulis data
    foreach ($transfer as $e) {
      $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $column, $e['tanggal'])
        ->setCellValue('B' . $column, $e['daftarulang'])
        ->setCellValue('C' . $column, $e['tunggakan'])
        ->setCellValue('D' . $column, $e['spp'])
        ->setCellValue('E' . $column, $e['uangsaku'])
        ->setCellValue('F' . $column, $e['infaq'])
        ->setCellValue('G' . $column, $e['formulir'])
        ->setCellValue('H' . $column, $e['rekening'])
        ->setCellValue('I' . $column, $e['program']);
      $column++;
    }
    // tulis dalam format .xlsx
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Transaksi';

    // Redirect hasil generate xlsx ke web client
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
  }

  public function keterangan()
  {
    $transferModel = new TransferModel();
    $keterangan = $transferModel->orderBy('tanggal', 'desc')->findAll();

    $spreadsheet = new Spreadsheet();
    // tulis header/nama kolom 
    $spreadsheet->setActiveSheetIndex(0)
      ->setCellValue('A1', 'tanggal')
      ->setCellValue('B1', 'nama')
      ->setCellValue('C1', 'kelas')
      ->setCellValue('D1', 'saldo masuk')
      ->setCellValue('E1', 'keterangan')
      ->setCellValue('F1', 'rekening')
      ->setCellValue('G1', 'program');

    $column = 2;
    // tulis data
    foreach ($keterangan as $k) {
      $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A' . $column, $k['tanggal'])
        ->setCellValue('B' . $column, $k['nama'])
        ->setCellValue('C' . $column, $k['kelas'])
        ->setCellValue('D' . $column, $k['saldomasuk'])
        ->setCellValue('E' . $column, $k['keterangan'])
        ->setCellValue('F' . $column, $k['rekening'])
        ->setCellValue('G' . $column, $k['program']);
      $column++;
    }
    // tulis dalam format .xlsx
    $writer = new Xlsx($spreadsheet);
    $fileName = 'Keterangan Transaksi';

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
    $santri = new SantriModel();
    $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
    $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
    $data['detail'] = $detailMod->where('id', $idtrans)->find();
    $data['santri'] = $santri->where('nama', $nama)->find();
    return view('pages/edit_transaksi', $data);
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

  public function kurangiSPPMasal()
  {
    $ids = $this->request->getPost('ids'); // array NISN
    $model = new SantriModel();

    if (!$ids || !is_array($ids)) {
      return $this->response->setJSON(['status' => false, 'msg' => 'Data kosong']);
    }

    foreach ($ids as $id) {
      $santri = $model->where('nisn', $id)->first();
      if ($santri) {
        $spp = (int) $santri['spp'];
        $tunggakanSpp = (int) $santri['tunggakanspp'];

        $tunggakanBaru = max(0, $tunggakanSpp - $spp); // Kurangi tunggakan dengan spp

        $model->update($id, ['tunggakanspp' => $tunggakanBaru]);
      }
    }

    return $this->response->setJSON(['status' => true, 'msg' => 'Berhasil mengurangi tunggakan SPP']);
  }

  public function migrasiMasal()
  {
    $ids = $this->request->getPost('ids');
    $kelasBaru = $this->request->getPost('kelas_baru');
    $model = new SantriModel();

    if (!$ids || !$kelasBaru) {
      return $this->response->setJSON(['status' => false, 'msg' => 'Data tidak lengkap']);
    }

    foreach ($ids as $id) {
      $model->update($id, ['kelas' => $kelasBaru]);
    }

    return $this->response->setJSON(['status' => true, 'msg' => 'Migrasi berhasil']);
  }

  public function updateSantri()
  {
    $model = new SantriModel();
    $data = $this->request->getPost();

    if (!isset($data['nisn'])) {
      return $this->response->setJSON(['status' => false, 'msg' => 'NISN wajib diisi']);
    }

    $model->update($data['nisn'], [
      'nama' => $data['nama'],
      'jenjang' => $data['jenjang'],
      'kelas' => $data['kelas'],
      'program' => $data['program'],
      'tempatlahir' => $data['tempatlahir'],
      'tanggallahir' => $data['tanggallahir'],
      'asalsekolah' => $data['asalsekolah'],
      'tahunmasuk' => $data['tahunmasuk'],
      'ayah' => $data['ayah'],
      'pekerjaanayah' => $data['pekerjaanayah'],
      'alamatayah' => $data['alamatayah'],
      'ibu' => $data['ibu'],
      'pekerjaanibu' => $data['pekerjaanibu'],
      'alamatibu' => $data['alamatibu'],
      'kontak1' => $data['kontak1'],
      'kontak2' => $data['kontak2'],
      'du' => $data['du'],
      'spp' => $data['spp'],
    ]);

    return $this->response->setJSON(['status' => true, 'msg' => 'Data santri berhasil diperbarui']);
  }
  public function updatePsb()
  {
    $model = new PsbModel();
    $data = $this->request->getPost();

    if (!isset($data['id'])) {
      return $this->response->setJSON(['status' => false, 'msg' => 'Id wajib diisi']);
    }

     $model->update($data['id'], [
      'nisn' => $data['nisn'] ?? null,
      'nama' => isset($data['nama'])
        ? strtoupper($data['nama']) : null,
      'jenjang'       => $data['jenjang'] ?? null,
      'program'       => $data['program'] ?? null,
      'daftarulang'   => $data['tunggakandu'] ?? null,
      'tunggakandu'   => $data['tunggakandu'] ?? null,
      'spp'           => $data['spp'] ?? null,
      'status'        => $data['status'] ?? null,
      'kelas'         => $data['kelas'] ?? null,
      'tanggallahir'  => $data['tanggallahir'] ?? null,
      'tempatlahir'   => $data['tempatlahir'] ?? null,
      'asalsekolah'   => $data['asalsekolah'] ?? null,
      'tahunmasuk'    => $data['tahunmasuk'] ?? null,
      'ayah'          => $data['ayah'] ?? null,
      'pekerjaanayah' => $data['pekerjaanayah'] ?? null,
      'alamatayah'    => $data['alamatayah'] ?? null,
      'ibu'           => $data['ibu'] ?? null,
      'pekerjaanibu'  => $data['pekerjaanibu'] ?? null,
      'alamatibu'     => $data['alamatibu'] ?? null,
      'kontak1'       => $data['kontak1'] ?? null,
      'kontak2'       => $data['kontak2'] ?? null,
    ]);
    return redirect()->to(base_url('data-psb'))
    ->with('success', 'Data santri berhasil diperbarui');
    }

  public function getSantriByNISN($nisn)
  {
    $model = new SantriModel();
    $data = $model->find($nisn);

    if ($data) {
      return $this->response->setJSON(['status' => true, 'data' => $data]);
    } else {
      return $this->response->setJSON(['status' => false, 'msg' => 'Santri tidak ditemukan']);
    }
  }
  public function getPsbById($id)
  {
    $model = new PsbModel();
    $data = $model->find($id);

    if ($data) {
      return $this->response->setJSON(['status' => true, 'data' => $data]);
    } else {
      return $this->response->setJSON(['status' => false, 'msg' => 'Santri tidak ditemukan']);
    }
  }
  
  public function arsipMasal()
{
    $ids = $this->request->getPost('ids');
    
    if (!is_array($ids) || empty($ids)) {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'Tidak ada santri yang dipilih.'
        ]);
    }

    $santriModel = new SantriModel();
    $alumniModel = new AlumniModel();

    $santriList = $santriModel->whereIn('nisn', $ids)->findAll();

    if (empty($santriList)) {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'Data santri tidak ditemukan.'
        ]);
    }

    foreach ($santriList as $s) {
        $alumniModel->insert([
            'nisn'    => $s['nisn'],
            'nama'    => $s['nama'],
            'kelas'   => $s['kelas'],
            'jenjang' => $s['jenjang'],
            'program' => $s['program'],
            'tunggakandu' => $s['tunggakandu'],
            'tunggakantl' => $s['tunggakantl'],
            'tunggakanspp' => $s['tunggakanspp'],
            'du' => $s['du'],
            'spp' => $s['spp'],
            'tahunajaran' => $s['tahunmasuk'],
            'tanggalkeluar' => date('Y-m-d')
        ]);
    }

    // Hapus dari tabel santri
    $santriModel->whereIn('nisn', $ids)->delete();

    return $this->response->setJSON([
        'status' => true,
        'msg' => count($santriList) . ' santri berhasil diarsipkan.'
    ]);
}

public function gantiTahunMasuk()
{
    $ids = $this->request->getPost('ids');         // Array NISN
    $tahun = $this->request->getPost('tahun');     // Tahun masuk baru

    if (!is_array($ids) || empty($ids)) {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'Tidak ada santri yang dipilih.'
        ]);
    }

    if (!is_numeric($tahun) || strlen($tahun) != 4) {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'Tahun masuk tidak valid.'
        ]);
    }

    $santriModel = new SantriModel();

    // Update field 'angkatan' atau 'tahun_masuk' — sesuaikan dengan nama kolom di database
    $santriModel->whereIn('nisn', $ids)->set(['tahunmasuk' => $tahun])->update();

    return $this->response->setJSON([
        'status' => true,
        'msg' => 'Tahun masuk berhasil diperbarui untuk ' . count($ids) . ' santri.'
    ]);
}

public function tandaiKeluar()
{
    $nisn = $this->request->getPost('nisn');

    if (!$nisn) {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'NISN tidak ditemukan.'
        ]);
    }

    $santriModel = new SantriModel();

    $updated = $santriModel->update($nisn, ['kelas' => 'keluar']);

    if ($updated) {
        return $this->response->setJSON([
            'status' => true,
            'msg' => 'Santri berhasil ditandai sebagai keluar.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => false,
            'msg' => 'Gagal mengupdate data.'
        ]);
    }
}

}
