<?php
/**
 * Punya Dosen
 * User: toni
 * Date: 21/04/16
 * Time: 12:06
 */

namespace Stmik\Factories;


use Stmik\Dosen;

class DosenFactory extends AbstractFactory
{

    /**
     * Kembalikan lists untuk dosen
     * @return mixed
     */
    public static function getDosenLists()
    {
        return Dosen::pluck('nama', 'nomor_induk')->all();
    }
	
    public function getDataDosen($nomor_induk = null)
    {
        if($nomor_induk===null) {
            // kembalikan langsung saja link polymorphic nya yang pasti merupakan mahasiswa
            return \Auth::user()->owner;
        }
        // kalau di sini cari manual
        // karena id sudah diset sebagai nomor induk mahasiswa maka ...
        return Dosen::findOrFail($nomor_induk);
    }
}