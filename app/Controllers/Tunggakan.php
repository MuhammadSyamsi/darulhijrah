<?php

namespace App\Controllers;

use App\Models\TunggakanModel;
use App\Models\SantriModel;

class Tunggakan extends BaseController
{
    protected $tunggakan;
    protected $santri;

    public function __construct()
    {
        $this->tunggakan = new TunggakanModel();
        $this->santri    = new SantriModel();
    }

    /* ================= CREATE ================= */

    public function create()
        {
            $nisn = $this->request->getGet('nisn');
        
            $santri = $this->santri->where('nisn', $nisn)->first();
        
            // Ambil semua tunggakan santri (tanpa filter bulan)
            $listTunggakan = $this->tunggakan
                ->where('nisn', $nisn)
                ->orderBy('tanggal', 'DESC')
                ->findAll();
        
            return view('tunggakan/create', [
                'nisn'          => $nisn,
                'nama'          => $santri['nama'] ?? '-',
                'spp'          => $santri['spp'] ?? '-',
                'listTunggakan' => $listTunggakan
            ]);
        }

    public function store()
        {
            $nisn       = $this->request->getPost('nisn');
            $bulan      = $this->request->getPost('bulan'); // array YYYY-MM
            $nominal    = $this->request->getPost('nominal');
            $keterangan = $this->request->getPost('keterangan');
        
            foreach ($bulan as $i => $b) {
    if ($nominal[$i] == '' || $nominal[$i] == 0) continue;
                $this->tunggakan->insert([
                    'nisn'       => $nisn,
                    'tanggal'    => $b . '-01',
                    'nominal'    => $nominal[$i],
                    'keterangan' => $keterangan[$i] ?? null
                ]);
            }
        
            return redirect()
                ->to('tunggakan/create?nisn='.$nisn)
                ->with('success', 'Tunggakan berhasil disimpan');
        }

    public function edit($id)
    {
        $data = $this->tunggakan->find($id);

        return view('tunggakan/edit', [
            'data' => $data
        ]);
    }

    public function update($id)
    {
        $this->tunggakan->update($id, [
            'nominal'    => $this->request->getPost('nominal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()
            ->to('laporan/spp')
            ->with('success', 'Tunggakan berhasil diupdate');
    }

    /* ================= DELETE ================= */

    public function delete($id)
    {
        $this->tunggakan->delete($id);

        return redirect()
            ->back()
            ->with('success', 'Tunggakan berhasil dihapus');
    }
    
    public function generate($bulan)
{
    $tanggal = $bulan . '-01';

    $santriList = $this->santri
        ->select('nisn, spp')
        ->findAll();

    foreach ($santriList as $s) {

        $cek = $this->tunggakan
            ->where('nisn', $s['nisn'])
            ->where('tanggal', $tanggal)
            ->first();

        if ($cek) {
            continue;
        }

        $this->tunggakan->insert([
            'nisn'       => $s['nisn'],
            'tanggal'    => $tanggal,
            'nominal'    => $s['spp'],
            'keterangan' => 'Tunggakan SPP '.$bulan
        ]);
    }

    return redirect()
        ->back()
        ->with('success','Tunggakan '.$bulan.' berhasil dibuat');
}

}