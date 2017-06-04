<?php

namespace Stmik\Http\Controllers\Dosen;

use Stmik\Factories\JadwalFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\JadwalRequest;

class JadwalController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(JadwalFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:dosen');
    }
    public function index()
    {
		return view('dosen.jadwal.index')
			->with('layout', $this->getLayout());
    }
    public function edit($id)
    {
        return view('dosen.jadwal.form')
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
            return $this->create()->with('success', "Data jadwal {$this->factory->getLastInsertId()} telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
}
