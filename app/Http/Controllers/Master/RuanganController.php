<?php

namespace Stmik\Http\Controllers\Master;

use Stmik\Factories\RuanganFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\RuanganRequest;

class RuanganController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(RuanganFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:master');
    }
    public function index()
    {
		return view('aktivitas.ruangan.index')
			->with('layout', $this->getLayout());
    }
    public function create()
    {
        return view('aktivitas.ruangan.form')
            ->with('data', null)
            ->with('action', route('aktivitas.ruangan.store'));
    }
    public function edit($id)
    {
        return view('aktivitas.ruangan.form')
            ->with('data', $this->factory->getDataRuangan($id))
            ->with('action', route('aktivitas.ruangan.update', ['id'=>$id]));
    }
    public function update($id, RuanganRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($id, $input)) {
            return $this->edit($id)->with('success', "Data Ruangan telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function store(RuanganRequest $request)
    {
        $input = $request->all();
        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data Ruangan {$this->factory->ruang()} telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
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
