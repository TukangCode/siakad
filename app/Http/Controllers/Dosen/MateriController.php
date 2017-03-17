<?php

namespace Stmik\Http\Controllers\Dosen;

use Stmik\Factories\MateriFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;

class MateriController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(MateriFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:dosen');
    }
    public function index()
    {
		return view('dosen.materi.index')
			->with('layout', $this->getLayout());
    }
    public function edit($id)
    {
        return view('dosen.materi.form')
            ->with('data', $this->factory->getDataMateri($id))
            ->with('action', route('dosen.materi.update', ['id'=>$id]));
    }
}
