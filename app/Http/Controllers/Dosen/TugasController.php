<?php

namespace Stmik\Http\Controllers\Dosen;

use Stmik\Factories\TugasFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\TugasRequest;

class TugasController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(TugasFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:dosen');
    }
    public function index()
    {
		return view('dosen.tugas.index')
			->with('layout', $this->getLayout());
    }
    public function create()
    {
        return view('dosen.tugas.form')
            ->with('data', null)
            ->with('action', route('dosen.tugas.store'));
    }
    public function edit($id)
    {
        return view('dosen.tugas.form')
            ->with('data', $this->factory->getDataTugas($id))
            ->with('action', route('dosen.tugas.update', ['id'=>$id]));
    }
    public function update($id, TugasRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($id, $input)) {
            return $this->edit($id)->with('success', "Data Jadwal telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function store(TugasRequest $request)
    {
        $input = $request->all();
        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data Tugas telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
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
