<?php
/**
 * Atur untuk penampilan dan perhitungan hasil study milik mahasiswa
 * User: toni
 * Date: 12/05/16
 * Time: 16:50
 */

namespace Stmik\Factories;


use Illuminate\Http\Request;
use Stmik\RencanaStudi;

class HasilStudyMahasiswaFactory extends MahasiswaFactory
{
    /**
     * Dapatkan data history pengambilan kuliah yang nantinya akan dijadikan sebagai dasar perhitungan untuk menghitung
     * IPK dan IPS
     * @param string $nim Nomor Induk Mahasiswa
     * @param array $pagination
     * @param Request $request
     */
    public function getBTTable($pagination, Request $request)
    {
        $filter = isset($pagination['otherQuery']['filter'])? $pagination['otherQuery']['filter']: [];
        // tahun ajaran
        $ta = isset($filter['ta'][0]) ? $filter['ta']: null;
        // semester
        $sem = isset($filter['sem'][0]) ? $filter['sem']: null;
        // agar dapat digunakan oleh akma, maka tambahkan customisasi terhadap Nomor Induk Mahasiswa, sehingga tidak lagi
        // default lewat session
        $nim = isset($filter['nim'][0]) ? $filter['nim']: null;
        if($nim===null) {
            $nim = \Session::get('username', 'NOTHING'); // bila nilai tidak ada maka ini mahasiswa yang login
        }
        $builder = \DB::table('rencana_studi as rs')
            ->join('rincian_studi as ris', 'ris.rencana_studi_id', '=', 'rs.id')
            ->join('pengampu_kelas as pk', 'pk.id', '=', 'ris.kelas_diambil_id')
            ->join('mata_kuliah as mk', 'mk.id', '=', 'pk.mata_kuliah_id')
            // user ini login sebagai mahasiswa, dan username mahasiswa adalah NIM nya
            ->where('rs.mahasiswa_id', '=', $nim);
//            ->orderBy('rs.tahun_ajaran')
//            ->orderBy('mk.semester');

        if($ta!==null) {
            $builder = $builder->where('rs.tahun_ajaran', '=', $ta);
        }
        if($sem!==null) {
            $builder = $builder->where('ris.semester', '=', $sem);
        }
        // sekarang selectnya ... harus explisit
        $builder = $builder->select(['rs.tahun_ajaran', 'mk.nama as mata_kuliah', 'mk.kode as kode_mata_kuliah',
            'ris.semester', 'mk.sks', 'ris.nilai_huruf', 'ris.nilai_angka', 'ris.status_lulus',
            'ris.tampil_di_transkrip', 'ris.id as ris_id']);

//        \Log::debug($builder->toSql(), ['ta'=>$ta, 'nim' => $nim]);

        // kembalikan datanya!
        return $this->getBTData($pagination,
            $builder,
            ['tahun_ajaran', 'mata_kuliah', 'kode_mata_kuliah', 'semester', 'sks', 'nilai_huruf', 'nilai_angka', 'status_lulus'],
            ['tahun_ajaran'=>'rs.tahun_ajaran', 'mata_kuliah'=>'mk.nama', 'kode_mata_kuliah'=>'mk.kode',
                'semester'=>'ris.semester', 'sks'=>'mk.sks']);
    }

    /**
     * Lakukan proses perhitungan IPS di sini. Nilai yang dikembalikan adalah array dari hasil study yang dibagi per
     * semester, di mana masing-masing array memiliki index semester, jumBobotSKS, jumSKS. Diharapkan agar pada tampilan
     * nanti baru dibuatkan perhitungan untuk nilai IPS nya!
     * @param string $nim Nomor Induk Mahasiswa
     * @return array|bool
     */
    public function loadDataPerhitunganIPS($nim)
    {
        try {
            // render dulu SQL untuk pengambilan semester dan jumBobot*SKS serta jumSKS yang di group berdasarkan semester
            $sqlSelect = <<<SQL
ris.semester,
sum(ris.nilai_angka * mk.sks) as jumBobotSKS,
sum(mk.sks) as jumSKS,
rs.tahun_ajaran
SQL;
            $builder = \DB::table('rencana_studi as rs')
                ->selectRaw($sqlSelect)
                ->join('rincian_studi as ris', 'ris.rencana_studi_id', '=', 'rs.id')
                ->join('pengampu_kelas as pk', 'pk.id', '=', 'ris.kelas_diambil_id')
                ->join('mata_kuliah as mk', 'mk.id', '=', 'pk.mata_kuliah_id')
                ->groupBy('ris.semester')
                ->where('rs.status', '=', RencanaStudi::STATUS_DISETUJUI) // hanya tampilkan yang sudah disetujui
                ->where('rs.mahasiswa_id', '=', $nim)
                ->orderBy('ris.semester')
                ->orderBy('rs.tahun_ajaran');

            // sekarang eksekusi builder!
            return pakai_cache("perhitunganIPS-$nim", function() use($builder) {
                return $builder->get();
            });

        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['nim'=>$nim]);
            $this->errors->add('sys', $e->getMessage());
        }

        return $this->errors->count() <= 0;
    }

    /**
     * Lakukan loading data hasil study yang datanya nanti akan digunakan untuk mencetak transkrip nilai dan melakukan
     * perhitungan IPK. Pada proses ini akan dilakukan grouping dan dicarikan nilai tertinggi berdasarkan huruf serta
     * nilai tertinggi berdasarkan nilai_angka lalu pada view yang melakukan proses tampilan akan melakukan render
     * serta perhitungan IPK.
     * Hasil eksekusi adalah array dengan item yang memiliki indeks mata_kuliah_id, nama, sks, nilai_huruf, nilai_angka
     * yan @ 29 juli 2016: KHS tidak menampilkan yang nilainya E!
     * @param string $nim mahasiswa yang ingin ditampilkan
     * @return array|bool|static[]
     */
    public function loadDataHasilStudy($nim)
    {
        try {
            // render dulu SQL untuk pengambilan semester dan jumBobot*SKS serta jumSKS yang di group berdasarkan semester
            $sqlSelect = <<<SQL
  mk.kode, mk.nama,
  mk.sks,
  min(ris.nilai_huruf) as nilai_huruf,
  max(ris.nilai_angka) as nilai_angka
SQL;
            $builder = \DB::table('rencana_studi as rs')
                ->selectRaw($sqlSelect)
                // tidak menampilkan yang nilainya E
                ->join('rincian_studi as ris', function($join) {
                    $join->on('ris.rencana_studi_id', '=', 'rs.id');
                    $join->where('ris.nilai_angka', '>', 0);
                    // jangan tampilkan yang tidak boleh tampil di transkrip #29
                    $join->where('ris.tampil_di_transkrip', '<>', 0);
                })
//                ->join('rincian_studi as ris', 'ris.rencana_studi_id', '=', 'rs.id')
                ->join('pengampu_kelas as pk', 'pk.id', '=', 'ris.kelas_diambil_id')
                ->join('mata_kuliah as mk', 'mk.id', '=', 'pk.mata_kuliah_id')
                ->groupBy('rs.mahasiswa_id', 'pk.mata_kuliah_id', 'mk.kode', 'mk.nama', 'mk.sks')
                ->orderBy('mk.nama')
                ->where('rs.mahasiswa_id', '=', $nim);

            // sekarang eksekusi builder!
            // TODO: tambahkan CACHE?
            return $builder->get();

        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['nim'=>$nim]);
            $this->errors->add('sys', $e->getMessage());
        }

        return $this->errors->count() <= 0;
    }

    /**
     * banghaji 20160622 untuk cetakKHS
     * @param $semester
     * @param $nim
     * @return array|bool|static[]
     */
	public function loadDataHasilStudySemesteran($semester, $nim)
    {
        try {
            // render dulu SQL untuk pengambilan semester dan jumBobot*SKS serta jumSKS yang di group berdasarkan semester
            $sqlSelect = <<<SQL
  mk.kode, mk.nama,
  mk.sks,
  min(ris.nilai_huruf) as nilai_huruf,
  max(ris.nilai_angka) as nilai_angka,
  ris.status_lulus
SQL;
            $builder = \DB::table('rencana_studi as rs')
                ->selectRaw($sqlSelect)
                ->join('rincian_studi as ris', 'ris.rencana_studi_id', '=', 'rs.id')
                ->join('pengampu_kelas as pk', 'pk.id', '=', 'ris.kelas_diambil_id')
                ->join('mata_kuliah as mk', 'mk.id', '=', 'pk.mata_kuliah_id')
                ->groupBy('rs.mahasiswa_id', 'pk.mata_kuliah_id', 'mk.kode', 'mk.nama', 'mk.sks')
                ->orderBy('mk.nama')
                ->where('rs.mahasiswa_id', '=', $nim)
                ->where('ris.semester', '=', $semester);

            // sekarang eksekusi builder!
            // TODO: tambahkan CACHE?
            return $builder->get();

        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['nim'=>$nim]);
            $this->errors->add('sys', $e->getMessage());
        }

        return $this->errors->count() <= 0;
    }

    /**
    // banghaji 20160622 baca tahun ajaran berdasarkan semester
     * YAN : karena ini kembaliannya mestinya nilai bukan collection maka gunakan value, jadi tidak perlu lagi melakukan
     * looping pada bagian view.
     * @param $semester
     * @param $nim
     * @return array|bool|static[]
     */
	public function tahunAjaranKapan($semester, $nim)
    {
        try {
            // render dulu SQL untuk pengambilan tahun ajaran
            $sqlSelect = <<<SQL
DISTINCT(rs.tahun_ajaran) as tahun_ajaran_d
SQL;
            $builder = \DB::table('rencana_studi as rs')
                ->selectRaw($sqlSelect)
                ->join('rincian_studi as ris', 'ris.rencana_studi_id', '=', 'rs.id')
                ->where('rs.mahasiswa_id', '=', $nim)
                ->where('ris.semester', '=', $semester);

            // kembalikan nilai
            return $builder->value('tahun_ajaran_d');

        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['nim'=>$nim]);
            $this->errors->add('sys', $e->getMessage());
        }

        return $this->errors->count() <= 0;
    }
}
