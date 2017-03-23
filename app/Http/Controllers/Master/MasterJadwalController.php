<?php

namespace Stmik\Http\Controllers\Master;

use Stmik\Factories\MasterJadwalFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\JadwalRequest;

class MasterJadwalController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(MasterJadwalFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:master');
    }
    public function index()
    {
		return view('aktivitas.jadwal.index')
			->with('layout', $this->getLayout());
    }
    public function create()
    {
        return view('aktivitas.jadwal.form')
            ->with('data', null)
            ->with('action', route('aktivitas.jadwal.store'));
    }
    public function edit($id)
    {
        return view('aktivitas.jadwal.form')
            ->with('data', $this->factory->getDataJadwal($id))
            ->with('action', route('dosen.jadwal.update', ['id'=>$id]));
    }
    public function update($id, JadwalRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($id, $input)) {
            return $this->edit($id)->with('success', "Data Jadwal telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function store(JadwalRequest $request)
    {
        $input = $request->all();
        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data NIM {$this->factory->getLastInsertId()} telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function delete($id)
    {
        if($this->factory->delete($id)) {
            return response("", 200,['X-IC-Remove'=>true]);
        }
        return response(json_encode($this->factory->getErrors()), 500,['X-IC-Remove'=>false]);
    }
}
