<?php
/**
 * Ini untuk Master mengatur jadwal
 * User: Yudi Hartono
 * Date: 02/03/17
 * Time: 12:55
 */

namespace Stmik\Factories;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stmik\Jadwal;
use Stmik\PengampuKelas;
use Stmik\Http\Controllers\input;

class MasterJadwalFactory extends AbstractFactory
{

    /**
     * Kembalikan nilai untuk di load di bootstrap table
     * @param $pagination
     * @param Request $request
     * @return string
     */
    public function getBTTable($pagination, Request $request)
    {
        $builder = \DB::table('jadwal as j')
			->join('ruangans as r', function ($join) {
				$join->on('j.ruangan_id', '=', 'r.id');
				})	
            ->join('pengampu_kelas as p', function($join){
                $join->on('j.pengampu_id', '=', 'p.id');
            })
			->join('mata_kuliah as m', function ($join) {
				$join->on('p.mata_kuliah_id', '=', 'm.id');
				})	
			->join('dosen as d', function ($join) {
				$join->on('p.dosen_id', '=', 'd.nomor_induk');
				})	
            ->select(['j.id','j.hari', 'j.jam_masuk', 'j.jam_keluar','j.pengampu_id','d.nama as dosen','m.nama','r.ruang']);

        return $this->getBTData($pagination,
            $builder,
            ['id','hari', 'jam_masuk', 'jam_keluar', 'dosen', 'pengampu_id', 'matakuliah','ruang']
		);
    }
    public function getDataJadwal($id = null)
    {
        if($id===null) {
            // kembalikan langsung saja link polymorphic nya
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
     * @param Jadwal $model
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
        return $this->realSave(new Jadwal(), $input);
    }

    /**
     * Hapuskan Jadwal ini
     * TODO: WARNING test terhadap data berelasi dengan master ini belum dilakukan :D Tambahkan fungsi utk check atau tambahkan foreign key
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            \DB::transaction(function () use ($id) {
                Jadwal::findOrFail($id)->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }
	
	public function checkValidJadwal($id,$request)
	{			
		if(
			Jadwal::where('hari', '=', $request->input('hari'))
			->where('ruangan_id', '=', $request->input('ruangan_id'))
			->where('jam_masuk', '=', $request->input('jam_masuk'))->first()
			){
			return true;
		}
		else if(
			Jadwal::where('hari', '=', $request->input('hari'))
			->where('ruangan_id', '=', $request->input('ruangan_id'))
			->where('jam_keluar', '<=', $request->input('jam_masuk'))->first()
			){
			return true;
		}else{
			return false;
		}
		
		return $id;
	}

}