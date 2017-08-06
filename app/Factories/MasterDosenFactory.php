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
use Stmik\Dosen;

class MasterDosenFactory extends DosenFactory
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
        $jurusan = isset($filter['jurusan'][0]) ? $filter['jurusan']: null;
        $builder = \DB::table('dosen as d')
            ->join('jurusan as j', function($join) use($jurusan){
                $join->on('d.jurusan_id', '=', 'j.id');
                if($jurusan!==null) {
                    $join->where('j.id', '=', $jurusan);
                }
            })
            ->select(['d.nama','d.nomor_induk','d.jenis_kelamin','d.agama','d.nidn','d.alamat','d.status_aktif','d.hp','d.status']);
        // proses status
        return $this->getBTData($pagination,
            $builder,
            ['nomor_induk','nama', 'nidn', 'jenis_kelamin','hp','status','alamat']
        );
    }

    /**
     * Update data
     * @param $nomor_induk
     * @param $input
     * @return bool
     */
    public function update($nomor_induk, $input)
    {
        return $this->realSave(
            Dosen::findOrFail($nomor_induk),
            $input
        );
    }

    /**
     * Penyimpanan realnya di sini
     * @param Dosen $model
     * @param $input
     * @return bool
     */
    protected function realSave(Dosen $model, $input)
    {
        try {
            \DB::transaction(function () use ($model, $input) {
                $model->fill($input);
                $model->save();
                $this->last_insert_id = $model->nomor_induk;
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
        return $this->realSave(new Dosen(), $input);
    }

    /**
     * Hapuskan mahasiswa ini
     * TODO: WARNING test terhadap data berelasi dengan master ini belum dilakukan :D Tambahkan fungsi utk check atau tambahkan foreign key
     * @param $nomor_induk
     * @return bool
     */
    public function delete($nomor_induk)
    {
        try {
            \DB::transaction(function () use ($nomor_induk) {
                Dosen::findOrFail($nomor_induk)->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

}