<?php

namespace Stmik\Http\Controllers;

use Illuminate\Http\Request;

//use Stmik\Http\Requests;
use Stmik\Http\Requests\PengumumanRequest;
use Stmik\Mahasiswa;
use Stmik\Jadwal;
use Stmik\RincianStudi;
use Stmik\RencanaStudi;
use Stmik\Tugas;
use Stmik\Pengumuman;
use Stmik\Materi;

class MahasiswaApiController extends Controller
{	
	public function login(PengumumanRequest $request)
	{
        $input = $request->all();
        return Response()->json($input, 200);
	}
	public function jadwal($nim)
	{
        $jadwal = RincianStudi::join('pengampu_kelas','rincian_studi.kelas_diambil_id','=','pengampu_kelas.id')
			->join('mata_kuliah','pengampu_kelas.mata_kuliah_id','=','mata_kuliah.id')
			->join('dosen','pengampu_kelas.dosen_id','=','dosen.nomor_induk')		
			->join('jadwal','rincian_studi.kelas_diambil_id','=','jadwal.pengampu_id')
			->join('ruangans','jadwal.ruangan_id','=','ruangans.id')
			->join('rencana_studi','rincian_studi.rencana_studi_id','=','rencana_studi.id')
			->select(['mata_kuliah.nama as matakuliah','jadwal.hari','jadwal.jam_masuk','jadwal.jam_keluar','ruangans.ruang','dosen.nama as dosen'])
			->where('mahasiswa_id','=',$nim)
			->paginate(20);
        return Response()->json($jadwal, 200);
	}
	public function tugas($nim)
	{
        $tugas = Tugas::join('pengampu_kelas','tugas.pengampu_id','=','pengampu_kelas.id')
			->join('mata_kuliah','pengampu_kelas.mata_kuliah_id','=','mata_kuliah.id')
			->join('dosen','pengampu_kelas.dosen_id','=','dosen.nomor_induk')			
			->join('rincian_studi','pengampu_kelas.id','=','rincian_studi.kelas_diambil_id')
			->join('rencana_studi','rincian_studi.rencana_studi_id','=','rencana_studi.id')
			->select(['tugas.nama_tugas','tugas.deadline','tugas.keterangan','mata_kuliah.nama as matakuliah','dosen.nama as dosen'])
			->where('mahasiswa_id','=',$nim)
		->paginate(20);
        if($tugas){
            return Response()->json($tugas, 200);
        } else {
			return response()->json(array("message" => "nim tidak ditemukan"), 403);	
		}
	}
	public function materi($nim)
	{
        $materi = Materi::join('pengampu_kelas','materis.pengampu_id','=','pengampu_kelas.id')
			->join('mata_kuliah','pengampu_kelas.mata_kuliah_id','=','mata_kuliah.id')
			->join('dosen','pengampu_kelas.dosen_id','=','dosen.nomor_induk')			
			->join('rincian_studi','pengampu_kelas.id','=','rincian_studi.kelas_diambil_id')
			->join('rencana_studi','rincian_studi.rencana_studi_id','=','rencana_studi.id')
			->select(['materis.id','materis.nama_materi','materis.filename','mata_kuliah.nama as matakuliah','dosen.nama as dosen'])
			->where('mahasiswa_id','=',$nim)
		->paginate(20);
        return Response()->json($materi, 200);
	}
	public function nilai($nim)
	{
        $nilai = RencanaStudi::join('rincian_studi as r','rencana_studi.id','=','r.rencana_studi_id')
			->join('pengampu_kelas as p','r.kelas_diambil_id','=','p.id')
			->join('mata_kuliah','p.mata_kuliah_id','=','mata_kuliah.id')
			->join('dosen','p.dosen_id','=','dosen.nomor_induk')	
			->join('mata_kuliah as m','p.mata_kuliah_id','=','m.id')
			->select(['m.nama as matakuliah','r.nilai_tugas','r.nilai_praktikum','r.nilai_uts','r.nilai_uas','r.nilai_akhir','r.status_lulus','r.nilai_huruf','dosen.nama as dosen'])
			->where('mahasiswa_id','=',$nim)
			->paginate(20);
        return Response()->json($nilai, 200);
	}
	public function pengumuman($nim)
	{
        $info = RencanaStudi::join('rincian_studi as r','rencana_studi.id','=','r.rencana_studi_id')
			->join('pengampu_kelas as p','r.kelas_diambil_id','=','p.id')
			->join('pengumuman','p.dosen_id','=','pengumuman.user_id')
			->join('dosen as d','pengumuman.user_id','=','d.nomor_induk')
			->select(['d.nama as dosen','pengumuman.perihal','pengumuman.keterangan'])
			->where('mahasiswa_id','=',$nim)
			->paginate(20);
        return Response()->json($info, 200);
	}
}
