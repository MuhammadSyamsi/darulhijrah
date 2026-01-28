<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KewajibanModel;
use App\Models\SantriModel;
use CodeIgniter\Database\Config;

class KewajibanController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Config::connect();
    }

    // VIEW
    public function index()
    {
        return view('kewajiban/index');
    }

    // API JSON
    public function list()
    {
        $data = $this->db->table('kewajiban k')
            ->select('k.id, k.tag, k.status, s.nisn, s.nama, s.kelas')
            ->join('santri s', 's.nisn = k.nisn')
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    public function store()
    {
        $model = new KewajibanModel();
        $model->insert($this->request->getJSON(true));
        return $this->response->setJSON(['status' => 'success']);
    }

    public function update($id)
    {
        $model = new KewajibanModel();
        $model->update($id, $this->request->getJSON(true));
        return $this->response->setJSON(['status' => 'updated']);
    }

    public function delete($id)
    {
        $model = new KewajibanModel();
        $model->delete($id);
        return $this->response->setJSON(['status' => 'deleted']);
    }

    // DETAIL RIWAYAT PEMBAYARAN
    public function riwayat($nisn)
    {
        $data = $this->db->table('santri s')
            ->select('s.nama, s.kelas, t.idtrans, t.tanggal, t.saldomasuk, t.keterangan, d.*')
            ->join('transfer t', 't.nisn = s.nisn', 'left')
            ->join('detail d', 'd.id = t.idtrans', 'left')
            ->where('s.nisn', $nisn)
            ->get()->getResultArray();

        return $this->response->setJSON($data);
    }

    // toombol add tag kewajiban
    public function kelas()
    {
        $kelas = $this->db->table('santri')
            ->select('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($kelas);
    }

    public function listByKelas($kelas)
    {
        $rows = $this->db->table('kewajiban k')
            ->select('k.id, k.tag, k.status, s.nisn, s.nama, s.kelas, s.spp')
            ->join('santri s', 's.nisn = k.nisn')
            ->where('s.kelas', $kelas)
            ->orderBy('s.nama', 'ASC')
            ->get()->getResultArray();

        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r['nisn']]['nisn']  = $r['nisn'];
            $grouped[$r['nisn']]['nama']  = $r['nama'];
            $grouped[$r['nisn']]['kelas'] = $r['kelas'];
            $grouped[$r['nisn']]['spp']   = $r['spp'];
            $grouped[$r['nisn']]['items'][] = [
                'id'     => $r['id'],
                'tag'    => $r['tag'],
                'status' => $r['status'],
            ];
        }

        return $this->response->setJSON(array_values($grouped));
    }

    public function storeMassal()
    {
        $input = $this->request->getJSON(true);

        $santri = $this->db->table('santri')
            ->select('nisn')
            ->where('kelas', $input['kelas'])
            ->get()->getResultArray();

        $data = [];
        foreach ($santri as $s) {
            $data[] = [
                'nisn'   => $s['nisn'],
                'tag'    => $input['tag'],
                'status' => 'tunggakan',
            ];
        }

        if ($data) {
            $this->db->table('kewajiban')->insertBatch($data);
        }

        return $this->response->setJSON(['status' => 'success', 'total' => count($data)]);
    }

    public function updateStatus($id)
    {
        $data = $this->request->getJSON(true);

        $this->db->table('kewajiban')
            ->where('id', $id)
            ->update(['status' => $data['status']]);

        return $this->response->setJSON(['status' => 'updated']);
    }

    public function updateStatusMassal()
    {
        $items = $this->request->getJSON(true);

        if (!$items || !is_array($items)) {
            return $this->response->setJSON(['status' => 'invalid'])->setStatusCode(400);
        }

        foreach ($items as $item) {
            $this->db->table('kewajiban')
                ->where('id', $item['id'])
                ->update([
                    'status' => $item['status']
                ]);
        }

        return $this->response->setJSON(['status' => 'updated']);
    }

    public function downloadCsv()
    {
        $kelas = $this->request->getGet('kelas');

        // Ambil semua TAG yang dipakai (misal: spp juli, spp agustus)
        $tags = $this->db->table('kewajiban')
            ->select('tag')
            ->groupBy('tag')
            ->orderBy('tag')
            ->get()
            ->getResultArray();

        $tagList = array_column($tags, 'tag');

        // Ambil data santri + kewajiban
        $builder = $this->db->table('santri s')
            ->select('s.nisn, s.nama, s.kelas, s.spp, k.tag, k.status')
            ->join('kewajiban k', 'k.nisn = s.nisn', 'left');

        if ($kelas) {
            $builder->where('s.kelas', $kelas);
        }

        $rows = $builder->get()->getResultArray();

        // Group data per santri
        $data = [];
        foreach ($rows as $r) {
            if (!isset($data[$r['nisn']])) {
                $data[$r['nisn']] = [
                    'nama'  => $r['nama'],
                    'kelas' => $r['kelas'],
                    'spp'   => $r['spp'],
                    'tags'  => []
                ];
            }

            if ($r['tag']) {
                $data[$r['nisn']]['tags'][$r['tag']] = $r['status'];
            }
        }

        // Header CSV
        $filename = 'kewajiban_santri.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header kolom
        fputcsv(
            $output,
            array_merge(['nama', 'kelas', 'spp'], $tagList)
        );

        // Isi data
        foreach ($data as $row) {
            $csvRow = [
                $row['nama'],
                $row['kelas'],
                $row['spp']
            ];

            foreach ($tagList as $tag) {
                $csvRow[] = $row['tags'][$tag] ?? '';
            }

            fputcsv($output, $csvRow);
        }

        fclose($output);
        exit;
    }

}