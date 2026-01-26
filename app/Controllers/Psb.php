<?php

namespace App\Controllers;

use App\Models\PsbModel;
use App\Models\DetailModel;
use App\Models\SantriModel;
use App\Models\TransferModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Psb extends BaseController
{
    public function index()
    {
        $psbmodel = new PsbModel();
        $data = [
            'total' => $psbmodel->select('count(nama) as total')->findAll(),
            'mts' => $psbmodel->select('count(nama) as total')->where('jenjang', 'mts')->findAll(),
            'ma' => $psbmodel->select('count(nama) as total')->where('jenjang', 'ma')->findAll(),
            'mundur' => $psbmodel->select('count(nama) as total')->where('status', 'mengundurkan diri')->findAll(),
            'formulir' => $psbmodel->select('count(nama) as total')->where('status', 'formulir')->findAll(),
            'komitmen' => $psbmodel->select('count(nama) as total')->where('status', 'sudah test')->findAll(),
            'observasi' => $psbmodel->select('count(nama) as total')->where('status', 'baru')->findAll(),
            'fix' => $psbmodel->select('count(nama) as total')->where('status', 'diterima')->findAll(),
            'list' => $psbmodel->where('status', 'mengundurkan diri')->findAll(),
            'stformulir' => $psbmodel->where('status', 'formulir')->findAll(),
            'status' => $psbmodel->where('status', 'baru')->findAll(),
            'hasil' => $psbmodel->where('status', 'sudah test')->findAll(),
            'diterima' => $psbmodel->where('status', 'diterima')->findAll(),
            'general' => $psbmodel->findAll()
        ];
        return view('psb/home', $data);
    }

    public function tambah()
    {
        $detailmodel = new DetailModel();
        $data = [
            'id' => $detailmodel->orderBy('id', 'desc')->limit(1)->findColumn('id')
        ];

        return view('psb/insert', $data);
    }

public function save()
{
    $psbmodel = new PsbModel();
    $detailmodel = new DetailModel();

    $santriData = $this->request->getPost('santri');

    foreach ($santriData as $row) {
        list($jenjang, $kelas) = explode('|', $row['jenjang']);

        $psbmodel->insert([
            'id' => $row['id'],
            'nisn' => $row['nisn'],
            'nama' => $row['nama'],
            'jenjang' => $jenjang,
            'kelas' => $kelas,
            'program' => $row['program'],
            'tanggal' => $row['tanggal'],
            'tunggakandu' => 0,
            'daftarulang' => 0,
            'spp' => 0,
            'status' => $row['status'],
            'formulir' => $row['formulir'],
            'rekening' => $row['rekening']
        ]);

        $detailmodel->insert([
            'id' => $row['id'],
            'program' => $row['program'],
            'tanggal' => $row['tanggal'],
            'rekening' => $row['rekening'],
            'daftarulang' => 0,
            'tunggakan' => 0,
            'spp' => 0,
            'uangsaku' => 0,
            'infaq' => 0,
            'formulir' => $row['formulir']
        ]);
    }

    return redirect()->to(base_url());
}

public function filter()
{
    if ($this->request->isAJAX()) {
        $status = $this->request->getJSON()->status ?? null;

        $psbmodel = new PsbModel();
        $builder = $psbmodel->orderBy('tanggal', 'DESC');

        if ($status) $builder->where('status', $status);

        $data['result'] = $builder->findAll();

        return view('psb/table', $data);
    }
}

    public function daftarbaru_psb()
    {
        $psbmodel = new PsbModel();
        $selectedItems = $this->request->getPost('cek');
        $psbmodel->whereIn('id', $selectedItems)->set(['status' => 'sudah test'])->update();

        return redirect()->to(base_url('/pendaftaran-observasi'));
    }

    public function editformulir($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('psb/editform', $data);
    }

    public function formulir($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('psb/form', $data);
    }

    public function fullform()
    {
        session();
        $psbmodel = new PsbModel();
        $id = $this->request->getPost('id');
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->findAll(),
            'validation' => \Config\Services::validation()
        ];
        if (!$this->validate([
            'nisn' => [
                'rules' => 'required|is_unique[psb.nisn]',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                    'is_unique' => '{field} tidak boleh sama'
                ]
            ]
        ])) {
            $validation = \Config\Services::validation();
            return view('psb/form', $data);
        } else {
            $psbmodel->where('id', $id)->set([
                'status' => 'baru',
                'nisn' => $this->request->getPost('nisn'),
                'nama' => $this->request->getPost('nama'),
                'tanggallahir' => $this->request->getPost('ttl'),
                'tempatlahir' => $this->request->getPost('tl'),
                'asalsekolah' => $this->request->getPost('asalsekolah'),
                'tahunmasuk' => $this->request->getPost('tahunmasuk'),
                'ayah' => $this->request->getPost('ayah'),
                'pekerjaanayah' => $this->request->getPost('pekerjaanayah'),
                'alamatayah' => $this->request->getPost('alamatayah'),
                'ibu' => $this->request->getPost('ibu'),
                'pekerjaanibu' => $this->request->getPost('pekerjaanibu'),
                'alamatibu' => $this->request->getPost('alamatibu'),
                'kontak1' => $this->request->getPost('kontak1'),
                'kontak2' => $this->request->getPost('kontak2'),
                'berkas' => $this->request->getPost('berkas')
            ])->update();
            return redirect()->to(base_url());
        }
    }

    public function fulleditform()
    {
        $psbmodel = new PsbModel();
        $id = $this->request->getPost('id');
        $psbmodel->where('id', $id)->set([
            'nisn' => $this->request->getPost('nisn'),
            'nama' => $this->request->getPost('nama'),
            'jenjang' => $this->request->getPost('jenjang'),
            'kelas' => $this->request->getPost('kelas'),
            'tanggallahir' => $this->request->getPost('ttl'),
            'tempatlahir' => $this->request->getPost('tl'),
            'asalsekolah' => $this->request->getPost('asalsekolah'),
            'tahunmasuk' => $this->request->getPost('tahunmasuk'),
            'ayah' => $this->request->getPost('ayah'),
            'pekerjaanayah' => $this->request->getPost('pekerjaanayah'),
            'alamatayah' => $this->request->getPost('alamatayah'),
            'ibu' => $this->request->getPost('ibu'),
            'pekerjaanibu' => $this->request->getPost('pekerjaanibu'),
            'alamatibu' => $this->request->getPost('alamatibu'),
            'kontak1' => $this->request->getPost('kontak1'),
            'kontak2' => $this->request->getPost('kontak2'),
            'berkas' => $this->request->getPost('berkas')
        ])->update();
        return redirect()->to(base_url());
    }

    public function mundur($id)
    {
        $psbmodel = new PsbModel();
        $psbmodel->where('id', $id)->set([
            'status' => 'mengundurkan diri',
        ])->update();
        return redirect()->to(base_url('/riwayat-alumni'));
    }

    public function komitmen($id)
    {
        $psbmodel = new PsbModel();
        $data = [
            'datadiri' => $psbmodel->where('id', $id)->first()
        ];

        return view('psb/komitmen', $data);
    }

    public function closing($id)
    {
        $psbmodel = new PsbModel();
        $psbmodel->where('id', $id)->set([
            'status' => 'diterima',
            'tdu' => $this->request->getPost('du'),
            'daftarulang' => $this->request->getPost('du'),
            'spp' => $this->request->getPost('spp')
        ])->update();

        return redirect()->to(base_url());
    }

    public function pembayaran()
    {
        $cariNama = new PsbModel();
        $data['cari'] = $cariNama->where('status', 'diterima')->findAll();
        return view('psb/pembayaran', $data);
    }

public function bayar()
{
    if (!$this->validate([
        'nisn' => 'required',
        'tdu'  => 'required|numeric'
    ])) {
        return redirect()->to('/pembayaran-psb')
            ->withInput()
            ->with('error', 'Data tidak lengkap');
    }

    $transfer = new TransferModel();
    $detail   = new DetailModel();
    $psb      = new PsbModel();

    $id    = $this->request->getPost('id');
    $nisn  = $this->request->getPost('nisn');
    $tdu   = (int) $this->request->getPost('tdu');
    $infaq = (int) $this->request->getPost('infaq');

    /* ================= SIMPAN TRANSFER ================= */
    $transfer->insert([
        'idtrans'     => $id,
        'nisn'        => $nisn,
        'nama'        => $this->request->getPost('nama'),
        'program'     => 'PSB',
        'kelas'       => $this->request->getPost('kelas'),
        'saldomasuk'  => $this->request->getPost('saldomasuk'),
        'tanggal'     => $this->request->getPost('tanggal'),
        'keterangan'  => $this->request->getPost('keterangan'),
        'rekening'    => $this->request->getPost('rekening'),
    ]);

    /* ================= SIMPAN DETAIL ================= */
    $detail->insert([
        'id'          => $id,
        'program'     => 'PSB',
        'tanggal'     => $this->request->getPost('tanggal'),
        'rekening'    => $this->request->getPost('rekening'),
        'daftarulang' => $tdu,
        'spp'         => 0,
        'uangsaku'    => 0,
        'infaq'       => $infaq,
        'formulir'    => 0
    ]);

    /* ================= UPDATE TUNGGAKAN ================= */
    $psbData = $psb->where('nisn', $nisn)->first();

    if ($psbData) {
        $sisa = max(0, $psbData['tunggakandu'] - $tdu);
        $psb->update($psbData['id'], ['tunggakandu' => $sisa]);
    }

    /* ================= REDIRECT KE KWITANSI ================= */
    return redirect()->to('kwitansi-psb/' . $id);
}

    public function dtransaksi($idtrans)
    {
        $transfer = new TransferModel();
        $detailMod = new DetailModel();
        $psb = new PsbModel();
        $nama = $transfer->where('idtrans', $idtrans)->findColumn('nama');
        $data['edit'] = $transfer->where('idtrans', $idtrans)->find();
        $data['detail'] = $detailMod->where('id', $idtrans)->find();
        $data['santri'] = $psb->where('nama', $nama)->find();
        return view('psb/edit_transaksi', $data);
    }

    public function edittung()
    {
        $postModel = new TransferModel();
        $postDetail = new DetailModel();
        $santri = new PsbModel();
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
            'id' => $this->request->getPost('id'),
            'nama' => $this->request->getPost('nama'),
            'tunggakandu' => $this->request->getPost('santridu'),
        ]);

        $data = [
            'id' => $this->request->getPost('idtrans'),
        ];

        return view('psb/kwitansi', $data);
    }

    public function cetak()
    {

        $data = explode(",", $_POST["img"]);
        $data = base64_decode($data[1]);

        $file = fopen("kwitansi/kwitansi.png", "w");
        fwrite($file, $data);
        fclose($file);
    }

    public function migrasiKeSantri()
    {
        $ids = $this->request->getPost('ids');
        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON(['status' => false, 'message' => 'Tidak ada data yang dipilih.']);
        }

        $psbModel = new PsbModel();
        $santriModel = new SantriModel();

        $dataPsb = $psbModel->whereIn('id', $ids)->findAll();
        $migrated = 0;

        foreach ($dataPsb as $psb) {
            // Cek dulu apakah sudah ada di tabel santri berdasarkan NISN atau field lainnya
            $cek = $santriModel->where('nisn', $psb['nisn'])->first();
            if ($cek) {
                continue; // skip jika sudah ada
            }

            // Buat data baru untuk santri
            $dataBaru = [
                'nisn' => $psb['nisn'],
                'nama' => $psb['nama'],
                'program' => $psb['program'],
                'kelas' => $psb['kelas'],
                'jenjang' => $psb['jenjang'],
                'tahunmasuk' => $psb['tahunmasuk'],
                'alamatayah' => $psb['alamatayah'],
                'kontak1' => $psb['kontak1'],
                'kontak2' => $psb['kontak2'],
                'tunggakandu' => $psb['tunggakandu'],
                'du' => $psb['daftarulang'],
                'spp' => $psb['spp'],
                'tempatlahir' => $psb['tempatlahir'],
                'tanggallahir' => $psb['tanggallahir'],
                'asalsekolah' => $psb['asalsekolah'],
                'ayah' => $psb['ayah'],
                'pekerjaanayah' => $psb['pekerjaanayah'],
                'ibu' => $psb['ibu'],
                'pekerjaanibu' => $psb['pekerjaanibu'],
                'alamatibu' => $psb['alamatibu'],
                'berkas' => $psb['berkas'],
            ];

            $santriModel->insert($dataBaru);
            $psbModel->delete($psb['id']);
            $migrated++;
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => "Berhasil migrasi $migrated data santri.",
        ]);
    }
    
    public function laporanpsb()
    {
        $psbmodel = new PsbModel();
        $datpsb = $psbmodel->findAll();

        $spreadsheet = new Spreadsheet();
        // tulis header/nama kolom 
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'nisn')
            ->setCellValue('B1', 'nama')
            ->setCellValue('C1', 'tempat lahir')
            ->setCellValue('D1', 'tanggal lahir')
            ->setCellValue('E1', 'asal sekolah')
            ->setCellValue('F1', 'program')
            ->setCellValue('G1', 'jenjang')
            ->setCellValue('H1', 'kelas')
            ->setCellValue('I1', 'status')
            ->setCellValue('J1', 'kewajiban du')
            ->setCellValue('K1', 'tunggakan daftar ulang')
            ->setCellValue('L1', 'tahun masuk')
            ->setCellValue('M1', 'nama ayah')
            ->setCellValue('N1', 'pekerjaan ayah')
            ->setCellValue('O1', 'nama ibu')
            ->setCellValue('P1', 'pekerjaan ibu')
            ->setCellValue('Q1', 'alamat orang tua')
            ->setCellValue('R1', 'kontak orang tua');

        $column = 2;
        // tulis data
        foreach ($datpsb as $d) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $column, $d['nisn'])
                ->setCellValue('B' . $column, $d['nama'])
                ->setCellValue('C' . $column, $d['tempatlahir'])
                ->setCellValue('D' . $column, $d['tanggallahir'])
                ->setCellValue('E' . $column, $d['asalsekolah'])
                ->setCellValue('F' . $column, $d['program'])
                ->setCellValue('G' . $column, $d['jenjang'])
                ->setCellValue('H' . $column, $d['kelas'])
                ->setCellValue('I' . $column, $d['status'])
                ->setCellValue('J' . $column, $d['daftarulang'])
                ->setCellValue('K' . $column, $d['tdu'])
                ->setCellValue('L' . $column, $d['tahunmasuk'])
                ->setCellValue('M' . $column, $d['ayah'])
                ->setCellValue('N' . $column, $d['pekerjaanayah'])
                ->setCellValue('O' . $column, $d['ibu'])
                ->setCellValue('P' . $column, $d['pekerjaanibu'])
                ->setCellValue('Q' . $column, $d['alamatayah'])
                ->setCellValue('R' . $column, $d['kontak1']);
            $column++;
        }
        // tulis dalam format .xlsx
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan PSB';

        // Redirect hasil generate xlsx ke web client
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
