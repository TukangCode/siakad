<?php

namespace Stmik\Http\Controllers\Master;

use Stmik\Factories\PengumumanFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\PengumumanRequest;

class PengumumanController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(PengumumanFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:master');
    }
    public function index()
    {
		return view('aktivitas.pengumuman.index')
			->with('layout', $this->getLayout());
    }
    public function create()
    {
        return view('aktivitas.pengumuman.form')
            ->with('data', null)
            ->with('action', route('aktivitas.pengumuman.store'));
    }
    public function edit($id)
    {
        return view('aktivitas.pengumuman.form')
            ->with('data', $this->factory->getDataPengumuman($id))
            ->with('action', route('aktivitas.pengumuman.update', ['id'=>$id]));
    }
    public function update($id, PengumumanRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($id, $input)) {
            return $this->edit($id)->with('success', "Data Jadwal telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function store(PengumumanRequest $request)
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
