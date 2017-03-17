<?php
/**
 * Atur jurusan
 * User: toni
 * Date: 10/04/16
 * Time: 15:42
 */

namespace Stmik\Factories;


use Stmik\ruangan;

class RuanganFactory extends AbstractFactory
{

    /**
     * Dapatkan daftar jurusan, mengembalikan berupa array dengan key adalah id jurusan dan nilainya adalah nama jurusan
     * lengkap
     * @return array
     */
    public static function getRuanganLists()
    {
        $s = ruangan::all();
        $a = [];
        foreach ($s as $r) {
            $a[$r->id] = $r->ruang;
        }
        return $a;
    }

}