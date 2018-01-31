<?php

namespace Stmik\Http\Controllers\Dosen;

use Stmik\Factories\MasterJadwalFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\JadwalRequest;

class SemuaJadwalController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;
	
    public function __construct(MasterJadwalFactory $factory)
    {
        $this->factory = $factory;
        $this->middleware('auth.role:dosen');
    }
    public function index()
    {
		return view('dosen.semua-jadwal.index')
			->with('layout', $this->getLayout());
    }
}
