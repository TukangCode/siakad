<?php
/**
 * Ini untuk master mahasiswa
 * User: toni
 * Date: 11/04/16
 * Time: 12:55
 */

namespace Stmik\Factories;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stmik\Jadwal;
use Stmik\PengampuKelas;

class JadwalFactory extends AbstractFactory
{

    /**
     * Kembalikan nilai untuk di load di bootstrap table
     * @param $pagination
     * @param Request $request
     * @return string
     */
    public function getBTTable($pagination, Request $request)
    {
        // proses filter
        $filter = isset($pagination['otherQuery']['filter'])? $pagination['otherQuery']['filter']: [];
        $pengampu_kelas = isset($filter['pengampu_kelas'][0]) ? $filter['pengampu_kelas']: null;
        $status  = isset($filter['hari'][0]) ? $filter['hari']: null;
        $builder = \DB::table('jadwal as j')
			->join('ruangans as r', function ($join) {
				$join->on('j.ruangan_id', '=', 'r.id');
				})	
            ->join('pengampu_kelas as p', function($join) use($pengampu_kelas){
                $join->on('j.pengampu_id', '=', 'p.id');
                if($pengampu_kelas!==null) {
                    $join->where('p.id', '=', $pengampu_kelas);
                }
            })
			->join('mata_kuliah as m', function ($join) {
				$join->on('p.mata_kuliah_id', '=', 'm.id');
				})	
            ->select(['j.id','j.hari', 'j.jam_masuk', 'j.jam_keluar','j.pengampu_id', 'p.dosen_id', 'p.mata_kuliah_id','m.nama','j.ruangan_id','r.ruang'])
			->where('p.dosen_id', '=', \Auth::user()->owner_id);

        return $this->getBTData($pagination,
            $builder,
            ['id','hari', 'jam_masuk', 'jam_keluar', 'dosen_id','pengampu_id', 'mata_kuliah_id','ruangan_id'],
            ['nama'=>'m.nama']  // karena ada yang double untuk nama maka mapping ke m.nama (matakuliah)
        );
    }
    public function getDataJadwal($id = null)
    {
        if($id===null) {
            // kembalikan langsung saja link polymorphic nya yang pasti merupakan mahasiswa
            return \Auth::user()->owner;
        }
        // kalau di sini cari manual
        // karena id sudah diset sebagai nomor induk mahasiswa maka ...
        return Jadwal::findOrFail($id);
    }
    /**
     * Update data
     * @param $nim
     * @param $input
     * @return bool
     */
    public function update($id, $input)
    {
        return $this->realSave(
            Jadwal::findOrFail($id),
            $input
        );
    }

    /**
     * Penyimpanan realnya di sini
     * @param MahasiswaUtkAkma $model
     * @param $input
     * @return bool
     */
    protected function realSave(Jadwal $model, $input)
    {
        try {
            \DB::transaction(function () use ($model, $input) {
                $model->fill($input);
                $model->save();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['input'=>Arr::flatten($input)]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

    /**
     * Buat data baru
     * @param $input
     * @return bool
     */
    public function store($input)
    {
        return $this->realSave(new MahasiswaUtkAkma(), $input);
    }

    /**
     * Hapuskan mahasiswa ini
     * TODO: WARNING test terhadap data berelasi dengan master ini belum dilakukan :D Tambahkan fungsi utk check atau tambahkan foreign key
     * @param $nim
     * @return bool
     */
    public function delete($nim)
    {
        try {
            \DB::transaction(function () use ($nim) {
                Mahasiswa::findOrFail($nim)->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

}