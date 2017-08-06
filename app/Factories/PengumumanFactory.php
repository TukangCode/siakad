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
use Stmik\PengampuKelas;
use Stmik\Pengumuman;

class PengumumanFactory extends AbstractFactory
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
        $builder = \DB::table('pengumuman as p')
            ->select(['p.id','p.perihal','p.keterangan'])
			->where('p.user_id', '=', \Auth::user()->owner_id);

        return $this->getBTData($pagination,
            $builder,
            ['id','perihal','keterangan'] 
			// karena ada yang double untuk nama maka mapping ke m.nama (matakuliah)
        );
    }
    public function getDataPengumuman($id = null)
    {
        if($id===null) {
            // kembalikan langsung saja link polymorphic nya yang pasti merupakan mahasiswa
            return \Auth::user()->owner;
        }
        // kalau di sini cari manual
        // karena id sudah diset sebagai nomor induk mahasiswa maka ...
        return Pengumuman::findOrFail($id);
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
            Pengumuman::findOrFail($id),
            $input
        );
    }

    /**
     * Penyimpanan realnya di sini
     * @param MahasiswaUtkAkma $model
     * @param $input
     * @return bool
     */
    protected function realSave(Pengumuman $model, $input)
    {
        try {
            \DB::transaction(function () use ($model, $input) {
                $model->fill($input);
                $model->save();
                $this->last_insert_id = $model->id;
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
        return $this->realSave(new Pengumuman(), $input);
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
                Pengumuman::findOrFail($id)->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

}