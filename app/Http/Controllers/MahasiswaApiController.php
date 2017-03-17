<?php

namespace Stmik\Http\Controllers;

use Illuminate\Http\Request;

use Stmik\Http\Requests;
use Stmik\Mahasiswa;
use Stmik\Jadwal;
use Stmik\RincianStudi;
use Stmik\RencanaStudi;

class MahasiswaApiController extends Controller
{	
	public function index()
	{
		//$mhs=dosen::all();
		//return Response::json($mhs,200);
        $mhs = Mahasiswa::paginate(10);
        return Response()->json($mhs, 200)
		->header('Access-Control-Allow-Origin','*')
		->header('Access-Control-Allow-methods','GET, POST');
	}
	public function jadwal()
	{
        $jadwal = RincianStudi::join('pengampu_kelas','rincian_studi.kelas_diambil_id','=','pengampu_kelas.id')
			->join('jadwal','rincian_studi.kelas_diambil_id','=','jadwal.pengampu_id')
			->join('rencana_studi','rincian_studi.rencana_studi_id','=','rencana_studi.id')
			->paginate(10);
        return Response()->json($jadwal, 200)
		->header('Access-Control-Allow-Origin','*')
		->header('Access-Control-Allow-methods','GET, POST');
	}
}
