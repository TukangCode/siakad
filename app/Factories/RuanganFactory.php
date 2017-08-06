<?php
/**
 * Atur jurusan
 * User: toni
 * Date: 10/04/16
 * Time: 15:42
 */

namespace Stmik\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stmik\Ruangan;

class RuanganFactory extends AbstractFactory
{
    /**
     * Kembalikan nilai untuk di load di bootstrap table
     * @param $pagination
     * @param Request $request
     * @return string
     */
    public function getBTTable($pagination, Request $request)
    {
        $builder = \DB::table('ruangans')->select('id','ruang','keterangan');

        return $this->getBTData($pagination,
            $builder,
            ['id','ruang','keterangan'] 
			// karena ada yang double untuk nama maka mapping ke m.nama (matakuliah)
        );
    }
    public function getDataRuangan($id = null)
    {
        if($id===null) {
            // kembalikan langsung saja link polymorphic nya yang pasti merupakan mahasiswa
            return \Auth::user()->owner;
        }
        // kalau di sini cari manual
        // karena id sudah diset sebagai nomor induk mahasiswa maka ...
        return Ruangan::findOrFail($id);
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
            Ruangan::findOrFail($id),
            $input
        );
    }

    /**
     * Penyimpanan realnya di sini
     * @param MahasiswaUtkAkma $model
     * @param $input
     * @return bool
     */
    protected function realSave(Ruangan $model, $input)
    {
        try {
            \DB::transaction(function () use ($model, $input) {
                $model->fill($input);
                $model->save();
                $this->ruang = $model->ruang;
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
        return $this->realSave(new Ruangan(), $input);
    }

    /**
     * Hapuskan mahasiswa ini
     * TODO: WARNING test terhadap data berelasi dengan master ini belum dilakukan :D Tambahkan fungsi utk check atau tambahkan foreign key
     * @param $nim
     * @return bool
     */
    public function delete($id)
    {
        try {
            \DB::transaction(function () use ($id) {
                Ruangan::findOrFail($id)->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

    /**
     * Dapatkan daftar jurusan, mengembalikan berupa array dengan key adalah id jurusan dan nilainya adalah nama jurusan
     * lengkap
     * @return array
     */
    public static function getRuanganLists()
    {
        $s = Ruangan::all();
        $a = [];
        foreach ($s as $r) {
            $a[$r->id] = $r->ruang;
        }
        return $a;
    }

}