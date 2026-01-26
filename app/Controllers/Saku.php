<?php

namespace App\Controllers;

use App\Models\SakuModel;
use CodeIgniter\Controller;

class Saku extends BaseController
{
    protected $sakuModel;

    public function __construct()
    {
        $this->sakuModel = new SakuModel();
    }

    // Tampilkan semua data
    public function index()
    {
        $saku = $this->sakuModel->findAll();
        
        foreach ($saku as &$row) {
            $row['total_in'] = $row['saldo_1'] + $row['saldo_2'] + $row['saldo_3'] + $row['saldo_4'] + $row['saldo_5'] +
                               $row['saldo_6'] + $row['saldo_7'] + $row['saldo_8'] + $row['saldo_9'] + $row['saldo_10'];
        
            $row['total_out'] = $row['debit_1'] + $row['debit_2'] + $row['debit_3'] + $row['debit_4'] + $row['debit_5'] +
                                $row['debit_6'] + $row['debit_7'] + $row['debit_8'] + $row['debit_9'] + $row['debit_10'] +
                                $row['debit_11'] + $row['debit_12'] + $row['debit_13'] + $row['debit_14'] + $row['debit_15'];
        }
        
        $db = \Config\Database::connect();

        $rekapJenjang = $db->table('saku')
            ->select([
                'jenjang',
                'SUM(saldo_1 + saldo_2 + saldo_3 + saldo_4 + saldo_5 + saldo_6 + saldo_7 + saldo_8 + saldo_9 + saldo_10) AS total_saldo',
                'SUM(debit_1 + debit_2 + debit_3 + debit_4 + debit_5 + debit_6 + debit_7 + debit_8 + debit_9 + debit_10 + debit_11 + debit_12 + debit_13 + debit_14 + debit_15) AS total_debit'
            ])
            ->groupBy('jenjang')
            ->get()
            ->getResultArray();

        
        $data = [
            'saku' => $saku,
            'rekapJenjang' => $rekapJenjang
        ];

        return view('saku/index', $data); // Buatkan view 'saku/index.php'
    }
    
    public function landing()
    {
        $saku = $this->sakuModel->findAll();
        foreach ($saku as &$row) {
            $row['total_in'] = $row['saldo_1'] + $row['saldo_2'] + $row['saldo_3'] + $row['saldo_4'] + $row['saldo_5'] +
                               $row['saldo_6'] + $row['saldo_7'] + $row['saldo_8'] + $row['saldo_9'] + $row['saldo_10'];
        
            $row['total_out'] = $row['debit_1'] + $row['debit_2'] + $row['debit_3'] + $row['debit_4'] + $row['debit_5'] +
                                $row['debit_6'] + $row['debit_7'] + $row['debit_8'] + $row['debit_9'] + $row['debit_10'] +
                                $row['debit_11'] + $row['debit_12'] + $row['debit_13'] + $row['debit_14'] + $row['debit_15'];
        }
        return view('saku_landing', [
          'saku' => $saku
        ]);

    }

    public function kantin()
    {
        $saku = $this->sakuModel->findAll();
        foreach ($saku as &$row) {
            $row['total_in'] = $row['saldo_1'] + $row['saldo_2'] + $row['saldo_3'] + $row['saldo_4'] + $row['saldo_5'] +
                               $row['saldo_6'] + $row['saldo_7'] + $row['saldo_8'] + $row['saldo_9'] + $row['saldo_10'];
        
            $row['total_out'] = $row['debit_1'] + $row['debit_2'] + $row['debit_3'] + $row['debit_4'] + $row['debit_5'] +
                                $row['debit_6'] + $row['debit_7'] + $row['debit_8'] + $row['debit_9'] + $row['debit_10'] +
                                $row['debit_11'] + $row['debit_12'] + $row['debit_13'] + $row['debit_14'] + $row['debit_15'];
        }
        return view('kantin/index', [
          'santri' => $saku
        ]);

    }
    
public function pembelian()
{
    $id = $this->request->getPost('santri_id');
    $itemList = $this->request->getPost('items'); // array of selected item keys

    if (!$id || !$itemList || !is_array($itemList)) {
        return redirect()->back()->with('error', 'Data tidak valid');
    }

    $items = [
        'bantal'         => ['nominal' => 35000, 'ket' => 'beli bantal'],
        'guling'         => ['nominal' => 35000, 'ket' => 'beli guling'],
        'sarung_bantal'  => ['nominal' => 15000, 'ket' => 'beli sarung bantal'],
        'sarung_guling'  => ['nominal' => 15000, 'ket' => 'beli sarung guling'],
        'sprei'          => ['nominal' => 80000, 'ket' => 'beli sprei'],
        'handuk'         => ['nominal' => 25000, 'ket' => 'beli handuk'],
        'ember'          => ['nominal' => 20000, 'ket' => 'beli ember'],
        'gayung'         => ['nominal' => 8000,  'ket' => 'beli gayung'],
        'ember_sabun'    => ['nominal' => 10000, 'ket' => 'beli ember sabun'],
    ];

    $model = new \App\Models\SakuModel();
    $saku = $model->find($id);

    if (!$saku) {
        return redirect()->back()->with('error', 'Santri tidak ditemukan.');
    }

    // Cek kolom debit_7 sampai debit_11 yang masih kosong
    $debet = [];
    $ket   = [];

    $start = 6;
    $end   = 15;
    $index = $start;

    foreach ($itemList as $item) {
        if (!isset($items[$item]) || $index > $end) continue;

        if ($saku["debit_$index"] == 0 || $saku["debit_$index"] === null) {
            $debet["debit_$index"] = $items[$item]['nominal'];
            $debet["ket_$index"]   = $items[$item]['ket'];
            $index++;
        }
    }

    if (empty($debet)) {
        return redirect()->back()->with('warning', 'Kolom pembelian sudah penuh.');
    }

    $model->update($id, $debet);
    return redirect()->to('/kantin')->with('success', 'Pembelian berhasil disimpan.');
}

    // Form tambah
    public function create()
    {
        return view('saku/create');
    }

    // Simpan data baru
    public function store()
    {
        $this->sakuModel->save($this->request->getPost());

        return redirect()->to('/saku')->with('success', 'Data berhasil ditambahkan.');
    }

    // Form edit
    public function edit($id)
    {
        $data['saku'] = $this->sakuModel->find($id);

        if (!$data['saku']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan: $id");
        }

        return view('saku/edit', $data);
    }

    // Update data
    public function update($id)
    {
        $this->sakuModel->update($id, $this->request->getPost());

        return redirect()->to('/saku')->with('success', 'Data berhasil diupdate.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->sakuModel->delete($id);

        return redirect()->to('/saku')->with('success', 'Data berhasil dihapus.');
    }

    // Detail per santri
    public function show($id)
    {
        $data['saku'] = $this->sakuModel->find($id);

        if (!$data['saku']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data tidak ditemukan: $id");
        }

        return view('saku/show', $data);
    }
    
    public function aksiMassal()
    {
        // ambil data yang jenjang = MTs
        $data = $this->sakuModel
            ->where('jenjang', 'MTs')
            ->findAll();
    
        // siapkan array untuk update batch
        $updateData = array_map(function ($item) {
            return [
                'id'      => $item['id'],
                'debit_5' => 50000,
                'ket_5'   => 'iuran dan saku camping'
            ];
        }, $data);
    
        // eksekusi update batch hanya untuk data yang sesuai filter
        if (! empty($updateData)) {
            $this->sakuModel->updateBatch($updateData, 'id');
        }
    
        return $this->response->setJSON(['status' => 'success']);
    }
    
public function laundry()
{
    $builder = $this->sakuModel->findAll();

    // Buat kondisi OR untuk ket_1 s/d ket_20
    $conditions = [];
    for ($i = 1; $i <= 15; $i++) {
        $conditions[] = "ket_$i LIKE '%laundry%'";
    }

    // Gabungkan dengan OR
    $whereClause = implode(' OR ', $conditions);

    $result = $this->sakuModel
        ->select('nama, jenjang')
        ->where("($whereClause)")
        ->get()
        ->getResultArray();

    return view('saku/laundry', ['laundryList' => $result]);
}    
}
