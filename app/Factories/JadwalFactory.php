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

}