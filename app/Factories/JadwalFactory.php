<?php
/**
 * Ini untuk Dosen mengatur jadwal
 * User: Yudi Hartono
 * Date: 02/03/17
 * Time: 12:55
 */

namespace Stmik\Factories;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stmik\Jadwal;
use Stmik\PengampuKelas;

class JadwalFactory extends MasterJadwalFactory
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
            ->select(['j.id','j.hari', 'j.jam_masuk', 'j.jam_keluar','j.pengampu_id', 'p.dosen_id','p.kelas', 'p.mata_kuliah_id','m.nama','j.ruangan_id','r.ruang'])
			->where('p.dosen_id', '=', \Auth::user()->owner_id);

        return $this->getBTData($pagination,
            $builder,
            ['id','hari', 'jam_masuk', 'jam_keluar', 'dosen_id','pengampu_id', 'mata_kuliah_id','ruangan_id','kelas'],
            ['nama'=>'m.nama']  // karena ada yang double untuk nama maka mapping ke m.nama (matakuliah)
        );
    }

}