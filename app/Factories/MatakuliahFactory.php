<?php
/**
 * Atur jurusan
 * User: toni
 * Date: 10/04/16
 * Time: 15:42
 */

namespace Stmik\Factories;


use Stmik\MataKuliah;
use Stmik\PengampuKelas;

class MatakuliahFactory extends AbstractFactory
{

    /**
     * Dapatkan daftar jurusan, mengembalikan berupa array dengan key adalah id jurusan dan nilainya adalah nama jurusan
     * lengkap
     * @return array
     */
    public static function getMatakuliahLists()
    {
        $s = MataKuliah::all();
        $a = [];
        foreach ($s as $m) {
            $a[$m->id] =$m->kode." ".$m->nama;
        }
        return $a;
    }

    public static function getPengampuMatakuliahLists()
    {
        $p = PengampuKelas::join('mata_kuliah as m', function($join){
                $join->on('pengampu_kelas.mata_kuliah_id', '=', 'm.id');
		    })
			->select('pengampu_kelas.id','m.nama')
			->where('pengampu_kelas.dosen_id', '=', \Auth::user()->owner_id)->get();
        $a = [];
        foreach ($p as $m) {
            $a[$m->id] =$m->nama;
        }
        return $a;
    }
}