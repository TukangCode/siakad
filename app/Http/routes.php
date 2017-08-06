<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
//Route::auth();
Route::group(['middleware' => ['web']], function () {

    // Authentication Routes...
    $this->get('login', 'Auth\AuthSelfController@showLoginForm');
    $this->post('login', 'Auth\AuthSelfController@login');
    $this->get('logout', 'Auth\AuthSelfController@logout');

    Route::group(['middleware' =>['auth']], function() {

        Route::get('/', ['as' => 'home', 'uses' => 'SiteController@index']);

        // USER
        Route::get('/user/setUserUntuk/{idOrangIni}/{typeOrangNya}', ['as' => 'user.setUserUntuk', 'uses' => 'UserController@setUserUntuk']);
        Route::post('/user/setUserUntuk/{idOrangIni}/{typeOrangNya}', ['as' => 'user.postSetUserUntuk', 'uses' => 'UserController@postSetUserUntuk']);
        Route::get('/user/profile', ['as' => 'user.profile', 'uses' => 'UserController@profile']);
        Route::post('/user/profile', ['as' => 'user.postProfile', 'uses' => 'UserController@postProfile']);

        // SPP
        Route::get('/akma/spp/', ['as' => 'akma.spp', 'uses' => 'Akma\StatusSPPController@index']);
        Route::post('/akma/spp/setStatus/{nim}/{ta}', ['as' => 'akma.spp.setStatus', 'uses' => 'Akma\StatusSPPController@setStatus']);
        Route::get('/akma/spp/getDT', ['as' => 'akma.spp.getDT', 'uses' => 'Akma\StatusSPPController@getDataBtTable']);

        // AKMA set Dosen - MK - Kelas
        Route::get('/akma/dkmk/', ['as' => 'akma.dkmk', 'uses' => 'Akma\DosenKelasMKController@index']);
        Route::get('/akma/dkmk/create', ['as' => 'akma.dkmk.create', 'uses' => 'Akma\DosenKelasMKController@create']);
        Route::post('/akma/dkmk/store', ['as' => 'akma.dkmk.store', 'uses' => 'Akma\DosenKelasMKController@store']);
        Route::get('/akma/dkmk/edit/{id}', ['as' => 'akma.dkmk.edit', 'uses' => 'Akma\DosenKelasMKController@edit']);
        Route::post('/akma/dkmk/update/{id}', ['as' => 'akma.dkmk.update', 'uses' => 'Akma\DosenKelasMKController@update']);
        Route::get('/akma/dkmk/getDT', ['as' => 'akma.dkmk.getDT', 'uses' => 'Akma\DosenKelasMKController@getDataBtTable']);
        Route::delete('/akma/dkmk/delete/{id}', ['as' => 'akma.dkmk.delete', 'uses' => 'Akma\DosenKelasMKController@delete']);
        Route::get('/akma/dkmk/absensi/{idKelas}', ['as' => 'akma.dkmk.absensi', 'uses' => 'Akma\DosenKelasMKController@formAbsensi']);

        // master mahasiswa
        Route::get('/master/mahasiswa/', ['as' => 'master.mahasiswa', 'uses' => 'Master\MahasiswaController@index']);
        Route::get('/master/mahasiswa/getDT', ['as' => 'master.mahasiswa.getDT', 'uses' => 'Master\MahasiswaController@getDataBtTable']);
        Route::get('/master/mahasiswa/create', ['as' => 'master.mahasiswa.create', 'uses' => 'Master\MahasiswaController@create']);
        Route::post('/master/mahasiswa/store', ['as' => 'master.mahasiswa.store', 'uses' => 'Master\MahasiswaController@store']);
        Route::get('/master/mahasiswa/edit/{nim}', ['as' => 'master.mahasiswa.edit', 'uses' => 'Master\MahasiswaController@edit']);
        Route::post('/master/mahasiswa/update/{nim}', ['as' => 'master.mahasiswa.update', 'uses' => 'Master\MahasiswaController@update']);
        Route::delete('/master/mahasiswa/delete/{nim}', ['as' => 'master.mahasiswa.delete', 'uses' => 'Master\MahasiswaController@delete']);

        // master mahasiswa
        Route::get('/master/dosen/', ['as' => 'master.dosen', 'uses' => 'Master\DosenController@index']);
        Route::get('/master/dosen/getDT', ['as' => 'master.dosen.getDT', 'uses' => 'Master\DosenController@getDataBtTable']);
        Route::get('/master/dosen/create', ['as' => 'master.dosen.create', 'uses' => 'Master\DosenController@create']);
        Route::post('/master/dosen/store', ['as' => 'master.dosen.store', 'uses' => 'Master\DosenController@store']);
        Route::get('/master/dosen/edit/{nomor_induk}}', ['as' => 'master.dosen.edit', 'uses' => 'Master\DosenController@edit']);
        Route::post('/master/dosen/update/{nomor_induk}', ['as' => 'master.dosen.update', 'uses' => 'Master\DosenController@update']);
        Route::delete('/master/dosen/delete/{nomor_induk}', ['as' => 'master.dosen.delete', 'uses' => 'Master\DosenController@delete']);
		
        // master Grade
        Route::get('/master/grade/', ['as' => 'master.grade', 'uses' => 'Master\GradeController@index']);
        Route::get('/master/grade/getDT', ['as' => 'master.grade.getDT', 'uses' => 'Master\GradeController@getDataBtTable']);
        Route::get('/master/grade/create', ['as' => 'master.grade.create', 'uses' => 'Master\GradeController@create']);
        Route::post('/master/grade/store', ['as' => 'master.grade.store', 'uses' => 'Master\GradeController@store']);
        Route::get('/master/grade/edit/{id}', ['as' => 'master.grade.edit', 'uses' => 'Master\GradeController@edit']);
        Route::post('/master/grade/update/{id}', ['as' => 'master.grade.update', 'uses' => 'Master\GradeController@update']);
        Route::delete('/master/grade/delete/{id}', ['as' => 'master.grade.delete', 'uses' => 'Master\GradeController@delete']);

        // master mata kuliah
        Route::get('/master/mk/', ['as' => 'master.mk', 'uses' => 'Master\MataKuliahController@index']);
        Route::get('/master/mk/create', ['as' => 'master.mk.create', 'uses' => 'Master\MataKuliahController@create']);
        Route::post('/master/mk/store', ['as' => 'master.mk.store', 'uses' => 'Master\MataKuliahController@store']);
        Route::get('/master/mk/edit/{id}', ['as' => 'master.mk.edit', 'uses' => 'Master\MataKuliahController@edit']);
        Route::post('/master/mk/update/{id}', ['as' => 'master.mk.update', 'uses' => 'Master\MataKuliahController@update']);
        Route::post('/master/mk/setStatus/{id}', ['as' => 'master.mk.setStatus', 'uses' => 'Master\MataKuliahController@setStatus']);
        Route::get('/master/mk/getDT', ['as' => 'master.mk.getDT', 'uses' => 'Master\MataKuliahController@getDataBtTable']);
        Route::post('/master/mk/loadBasedOnJurusan', ['as' => 'master.mk.loadBasedOnJurusan', 'uses' => 'Master\MataKuliahController@loadBasedOnJurusan']);

        // DATA DIRI Mahasiswa
        Route::get('/mhs/dataDiri/', ['as' => 'mhs.dataDiri', 'uses' => 'Mahasiswa\DataDiriController@index']);
        Route::post('/mhs/dataDiri/', ['as' => 'mhs.postDataDiri', 'uses' => 'Mahasiswa\DataDiriController@postDataDiri']);

        // Hasil Study
        Route::get('/mhs/hasilStudy/', ['as' => 'mhs.hasilStudy', 'uses' => 'Mahasiswa\HasilStudyController@index']);
        Route::get('/mhs/hasilStudy/ips/{nim?}', ['as' => 'mhs.hasilStudy.ips', 'uses' => 'Mahasiswa\HasilStudyController@ips']);
        Route::get('/mhs/hasilStudy/ipk/{nim?}', ['as' => 'mhs.hasilStudy.ipk', 'uses' => 'Mahasiswa\HasilStudyController@ipk']);
        Route::get('/mhs/hasilStudy/getDT', ['as' => 'mhs.hasilStudy.getDT', 'uses' => 'Mahasiswa\HasilStudyController@getDataBtTable']);
        /* banghaji 20160622 untuk cetak KHS */
        Route::get('/mhs/hasilStudy/cetakKHS/{semester?}/{nim?}', ['as' => 'mhs.hasilStudy.cetakKHS', 'uses' => 'Mahasiswa\HasilStudyController@cetakKHS']);

        // isi FRS
        Route::get('/mhs/frs/', ['as' => 'mhs.frs', 'uses' => 'Mahasiswa\IsiFRSController@index']);
        Route::post('/mhs/frs/mulai', ['as' => 'mhs.frs.mulai', 'uses' => 'Mahasiswa\IsiFRSController@mulaiPengisianFRS']);
        Route::post('/mhs/frs/pilihKelasIni/{kodeKelas}', ['as' => 'mhs.frs.pilihKelasIni', 'uses' => 'Mahasiswa\IsiFRSController@pilihKelasIni']);
        Route::post('/mhs/frs/batalkanPemilihanKelasIni/{kodeKelas}', ['as' => 'mhs.frs.batalkanPemilihanKelasIni', 'uses' => 'Mahasiswa\IsiFRSController@batalkanPemilihanKelasIni']);
        Route::get('/mhs/frs/getDT', ['as' => 'mhs.frs.getDT', 'uses' => 'Mahasiswa\IsiFRSController@getDataBtTable']);
        Route::get('/mhs/frs/cetakKRS/{nim?}/{ta?}', ['as' => 'mhs.frs.cetakKRS', 'uses' => 'Mahasiswa\IsiFRSController@cetakKRS']);

        // persetujuan FRS
        Route::get('/akma/persetujuanFRS/', ['as' => 'akma.persetujuanFRS', 'uses' => 'Akma\PersetujuanFRSController@index']);
        Route::get('/akma/persetujuanFRS/getDT', ['as' => 'akma.persetujuanFRS.getDT', 'uses' => 'Akma\PersetujuanFRSController@getDataBtTable']);
        Route::post('/akma/persetujuanFRS/status/{idFRS}', ['as' => 'akma.persetujuanFRS.status', 'uses' => 'Akma\PersetujuanFRSController@postStatus']);

        // MK Double
        Route::get('/akma/mkdouble/', ['as' => 'akma.mkdouble', 'uses' => 'Akma\FilterMKDoubleController@index']);
        Route::post('/akma/mkdouble/status/{idRincianStudi}', ['as' => 'akma.mkdouble.status', 'uses' => 'Akma\FilterMKDoubleController@postStatus']);

        Route::get('/akma/editmkmhs/', ['as' => 'akma.editmkmhs', 'uses' => 'Akma\EditMKMahasiswaController@index']);

        // Input Absensi
        Route::get('/akma/absen/', ['as' => 'akma.absen', 'uses' => 'Akma\InputAbsenController@index']);
        Route::post('/akma/absen/loadKelas/', ['as' => 'akma.absen.loadKelas', 'uses' => 'Akma\InputAbsenController@loadKelasBerdasarkan']);
        Route::post('/akma/absen/loadDaftarMahasiswa/', ['as' => 'akma.absen.loadDaftarMahasiswa', 'uses' => 'Akma\InputAbsenController@loadDaftarMahasiswa']);
        Route::post('/akma/absen/simpan/{kelas}', ['as' => 'akma.absen.simpan', 'uses' => 'Akma\InputAbsenController@simpan']);

        // input nilai mahasiswa
        Route::get('/akma/nilai-mahasiswa/', ['as' => 'akma.nilai-mahasiswa', 'uses' => 'Akma\NilaiMahasiswaController@index']);
        Route::post('/akma/nilai-mahasiswa/loadKelas', ['as' => 'akma.nilai-mahasiswa.loadKelas', 'uses' => 'Akma\NilaiMahasiswaController@loadKelas']);
        Route::post('/akma/nilai-mahasiswa/loadDaftarMahasiswa/', ['as' => 'akma.nilai-mahasiswa.loadDaftarMahasiswa', 'uses' => 'Akma\NilaiMahasiswaController@loadDaftarMahasiswa']);
        Route::post('/akma/nilai-mahasiswa/simpan/{kelas}', ['as' => 'akma.nilai-mahasiswa.simpan', 'uses' => 'Akma\NilaiMahasiswaController@simpan']);

		//Jadwal Untuk Master
		Route::get('/aktivitas/jadwal/', ['as' => 'aktivitas.jadwal', 'uses' => 'Master\MasterJadwalController@index']);
		Route::get('/aktivitas/jadwal/getDT', ['as' => 'aktivitas.jadwal.getDT', 'uses' => 'Master\MasterJadwalController@getDataBtTable']);
		Route::get('/aktivitas/jadwal/create', ['as' => 'aktivitas.jadwal.create', 'uses' => 'Master\MasterJadwalController@create']);
		Route::post('/aktivitas/jadwal/store', ['as' => 'aktivitas.jadwal.store', 'uses' => 'Master\MasterJadwalController@store']);
		Route::get('/aktivitas/jadwal/edit/{id}', ['as' => 'aktivitas.jadwal.edit', 'uses' => 'Master\MasterJadwalController@edit']);		
        Route::post('/akativitas/jadwal/update/{id}', ['as' => 'aktivitas.jadwal.update', 'uses' => 'Master\MasterJadwalController@update']);
		Route::delete('/aktivitas/jadwal/delete/{id}', ['as' => 'aktivitas.jadwal.delete', 'uses' => 'Master\MasterJadwalController@delete']);	
		//Pengumuman untuk Master
		Route::get('/aktivitas/pengumuman/', ['as' => 'aktivitas.pengumuman', 'uses' => 'Master\PengumumanController@index']);
		Route::get('/aktivitas/pengumuman/getDT', ['as' => 'aktivitas.pengumuman.getDT', 'uses' => 'Master\PengumumanController@getDataBtTable']);
		Route::get('/aktivitas/pengumuman/create', ['as' => 'aktivitas.pengumuman.create', 'uses' => 'Master\PengumumanController@create']);
		Route::post('/aktivitas/pengumuman/store', ['as' => 'aktivitas.pengumuman.store', 'uses' => 'Master\PengumumanController@store']);
		Route::get('/aktivitas/pengumuman/edit/{id}', ['as' => 'aktivitas.pengumuman.edit', 'uses' => 'Master\PengumumanController@edit']);
		Route::post('/aktivitas/pengumuman/update/{id}', ['as' => 'aktivitas.pengumuman.update', 'uses' => 'Master\PengumumanController@update']);		
		Route::delete('/aktivitas/pengumuman/delete/{id}', ['as' => 'aktivitas.tugas.delete', 'uses' => 'Master\PengumumanController@delete']);
		//ruangan
		Route::get('/aktivitas/ruangan/', ['as' => 'aktivitas.ruangan', 'uses' => 'Master\RuanganController@index']);
		Route::get('/aktivitas/ruangan/getDT', ['as' => 'aktivitas.ruangan.getDT', 'uses' => 'Master\RuanganController@getDataBtTable']);
		Route::get('/aktivitas/ruangan/create', ['as' => 'aktivitas.ruangan.create', 'uses' => 'Master\RuanganController@create']);
		Route::post('/aktivitas/ruangan/store', ['as' => 'aktivitas.ruangan.store', 'uses' => 'Master\RuanganController@store']);
		Route::get('/aktivitas/ruangan/edit/{id}', ['as' => 'aktivitas.ruangan.edit', 'uses' => 'Master\RuanganController@edit']);		
        Route::post('/akativitas/ruangan/update/{id}', ['as' => 'aktivitas.ruangan.update', 'uses' => 'Master\RuanganController@update']);
		Route::delete('/aktivitas/ruangan/delete/{id}', ['as' => 'aktivitas.ruangan.delete', 'uses' => 'Master\RuanganController@delete']);			
		
		//Jadwal Untuk dosen
		Route::get('/dosen/jadwal/', ['as' => 'dosen.jadwal', 'uses' => 'Dosen\JadwalController@index']);
		Route::get('/dosen/jadwal/getDT', ['as' => 'dosen.jadwal.getDT', 'uses' => 'Dosen\JadwalController@getDataBtTable']);
		Route::post('/dosen/jadwal/store', ['as' => 'dosen.jadwal.store', 'uses' => 'Dosen\JadwalController@store']);
		Route::get('/dosen/jadwal/edit/{id}', ['as' => 'dosen.jadwal.edit', 'uses' => 'Dosen\JadwalController@edit']);		
        Route::post('/dosen/jadwal/update/{id}', ['as' => 'dosen.jadwal.update', 'uses' => 'Dosen\JadwalController@update']);		

		//Tugas
		Route::get('/dosen/tugas/', ['as' => 'dosen.tugas', 'uses' => 'Dosen\TugasController@index']);
		Route::get('/dosen/tugas/getDT', ['as' => 'dosen.tugas.getDT', 'uses' => 'Dosen\TugasController@getDataBtTable']);
		Route::get('/dosen/tugas/create', ['as' => 'dosen.tugas.create', 'uses' => 'Dosen\TugasController@create']);
		Route::post('/dosen/tugas/store', ['as' => 'dosen.tugas.store', 'uses' => 'Dosen\TugasController@store']);
		Route::get('/dosen/tugas/edit/{id}', ['as' => 'dosen.tugas.edit', 'uses' => 'Dosen\TugasController@edit']);
		Route::post('/dosen/tugas/update/{id}', ['as' => 'dosen.tugas.update', 'uses' => 'Dosen\TugasController@update']);
		Route::delete('/dosen/tugas/delete/{id}', ['as' => 'dosen.tugas.delete', 'uses' => 'Dosen\TugasController@delete']);
		//Materi
		Route::get('/dosen/materi/', ['as' => 'dosen.materi', 'uses' => 'Dosen\MateriController@index']);
		Route::get('/dosen/materi/getDT', ['as' => 'dosen.materi.getDT', 'uses' => 'Dosen\MateriController@getDataBtTable']);
		Route::get('/dosen/materi/create', ['as' => 'dosen.materi.create', 'uses' => 'Dosen\MateriController@create']);
		Route::post('/dosen/materi/store', ['as' => 'dosen.materi.store', 'uses' => 'Dosen\MateriController@store']);
		Route::get('/dosen/materi/edit/{id}', ['as' => 'dosen.materi.edit', 'uses' => 'Dosen\MateriController@edit']);
		Route::post('/dosen/materi/update/{id}', ['as' => 'dosen.materi.update', 'uses' => 'Dosen\MateriController@update']);
		Route::delete('/dosen/materi/delete/{id}', ['as' => 'dosen.tugas.delete', 'uses' => 'Dosen\MateriController@delete']);
		//Pengumuman untuk dosen
		Route::get('/dosen/pengumuman/', ['as' => 'dosen.pengumuman', 'uses' => 'Dosen\PengumumanController@index']);
		Route::get('/dosen/pengumuman/getDT', ['as' => 'dosen.pengumuman.getDT', 'uses' => 'Dosen\PengumumanController@getDataBtTable']);
		Route::get('/dosen/pengumuman/create', ['as' => 'dosen.pengumuman.create', 'uses' => 'Dosen\PengumumanController@create']);
		Route::post('/dosen/pengumuman/store', ['as' => 'dosen.pengumuman.store', 'uses' => 'Dosen\PengumumanController@store']);
		Route::get('/dosen/pengumuman/edit/{id}', ['as' => 'dosen.pengumuman.edit', 'uses' => 'Dosen\PengumumanController@edit']);
		Route::post('/dosen/pengumuman/update/{id}', ['as' => 'dosen.pengumuman.update', 'uses' => 'Dosen\PengumumanController@update']);		
		Route::delete('/dosen/pengumuman/delete/{id}', ['as' => 'dosen.tugas.delete', 'uses' => 'Dosen\PengumumanController@delete']);
    });
});

Route::group(array('prefix'=>'api'),function(){
	Route::group(['middleware' => 'cors'], function() { 	
		Route::get('/login/', ['as' => 'api.login', 'uses' => 'ApiAuthController@user_login']);
		Route::group(['middleware' => 'token.check'], function() { 	
			Route::get('/jadwal/{nim}/{token}', ['as' => 'api.jadwal', 'uses' => 'MahasiswaApiController@jadwal']);
			Route::get('/tugas/{nim}/{token}', ['as' => 'api.tugas', 'uses' => 'MahasiswaApiController@tugas']);
			Route::get('/nilai/{nim}/{token}', ['as' => 'api.nilai', 'uses' => 'MahasiswaApiController@nilai']);
			Route::get('/materi/{nim}/{token}', ['as' => 'api.materi', 'uses' => 'MahasiswaApiController@materi']);	
			Route::get('/pengumuman/{nim}/{token}', ['as' => 'api.pengumuman', 'uses' => 'MahasiswaApiController@pengumuman']);

		});
	});
	Route::get('/download/{filename}', ['as' => 'api.download', 'uses' => 'MahasiswaApiController@download']);
});
