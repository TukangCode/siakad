<?php
/**
 * Mengatur bagaimana proses penyimpanan dosen dan matakuliah serta kelas
 * User: toni
 * Date: 21/04/16
 * Time: 9:57
 */

namespace Stmik\Factories;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Stmik\PengampuKelas;

class DosenKelasMKFactory extends AbstractFactory
{

    /**
     * Kembalikan data dosen
     * @param $pagination
     * @param Request $request
     * @return string
     */
    public function getBTTable($pagination, Request $request)
    {
        $filter = isset($pagination['otherQuery']['filter'])? $pagination['otherQuery']['filter']: [];
        $jurusan = isset($filter['jurusan'][0]) ? $filter['jurusan']: null;
        $ta = isset($filter['ta'][0]) ? $filter['ta']: null;

        $builder = \DB::table('pengampu_kelas as pk')
            ->join('mata_kuliah as mk', 'mk.id', '=', 'pk.mata_kuliah_id')
            ->join('jurusan as j', function($join) use($jurusan) {
                $join->on('j.id', '=', 'mk.jurusan_id');
                if($jurusan!==null) {
                    $join->where('j.id', '=', $jurusan);
                }
            })
            ->join('dosen as d', 'd.nomor_induk', '=', 'pk.dosen_id');

        if($ta!==null) {
            $builder = $builder->whereTahunAjaran($ta);
        }
        // harus di set untuk select nya ...
        $builder = $builder->select(['pk.id', 'pk.tahun_ajaran', 'pk.kelas', 'pk.quota', 'mk.nama as nama_mk',
            'd.nama as nama_dosen', 'j.nama as nama_jurusan']);

        return $this->getBTData($pagination,
            $builder,
            ['id', 'tahun_ajaran', 'kelas', 'quota', 'nama_dosen', 'nama_jurusan', 'nama_mk'], // yang bisa dicari
            ['id'=>'pk.id', 'tahun_ajaran', 'kelas', 'quota', 'nama_dosen'=>'d.nama', 'nama_jurusan'=>'j.nama', 'nama_mk'=>'mk.nama'] // mapping
        );

    }

    /**
     * Catat yang baru!
     * @param $input
     * @return bool
     */
    public function store($input)
    {
        return $this->realSave(new PengampuKelas(), $input);
    }

    /**
     * Bagian yang menyimpan
     * @param PengampuKelas $pengampuKelas
     * @param $input
     * @return bool
     */
    protected function realSave(PengampuKelas $pengampuKelas, $input)
    {
        try {
            \DB::transaction(function () use ($pengampuKelas, $input) {
                $pengampuKelas->fill($input);
                $pengampuKelas->id = $pengampuKelas->kalkulasiPK($input['mata_kuliah_id'], $input['dosen_id'], $input['tahun_ajaran'], $input['kelas']);
                $pengampuKelas->save();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['input'=>Arr::flatten($input)]);
        }
        return $this->errors->count() <= 0;
    }

    /**
     * Kembalikan data untuk kelas pengampu!
     * @param string $id kode kelas pengampu
     * @return mixed
     */
    public function getDataDosenKelasMKBerdasarkan($id)
    {
        return PengampuKelas::findOrFail($id);
    }

    /**
     * Update data ini
     * @param PengampuKelas $pengampuKelas
     * @param $input
     * @return bool
     */
    public function update(PengampuKelas $pengampuKelas, $input)
    {
        return $this->realSave($pengampuKelas, $input);
    }

    /**
     * Hapus pengampu kelas
     * TODO: PERHATIKAN INI SEHARUSNYA SEBELUM MENGHAPUS, di test apakah ada data mereferensi ke data ini? NOTE: gunakan foreign key saja lebih praktis!
     * @param PengampuKelas $pk
     * @return bool
     */
    public function delete(PengampuKelas $pk)
    {
        try {
            \DB::transaction(function () use ($pk) {
                $pk->delete();
            });
        } catch (\Exception $e) {
            \Log::alert("Bad Happen:" . $e->getMessage() . "\n" . $e->getTraceAsString(), ['id'=>$pk->id]);
            $this->errors->add('sys', $e->getMessage());
        }
        return $this->errors->count() <= 0;
    }

    /**
     * Lakukan proses penambahan jumlah peminat kelas sesuai dengan $kodeIdKelas
     * @param string $kodeIdKelas
     */
    public static function tambahPeminatKelasIni($kodeIdKelas)
    {
        PengampuKelas::where('id', '=', $kodeIdKelas)->increment('jumlah_peminat');
    }

    /**
     * Kurangi peminatnya!
     * @param $kodeIdKelas
     */
    public static function kurangiPeminatKelasIni($kodeIdKelas)
    {
        PengampuKelas::where('id', '=', $kodeIdKelas)->decrement('jumlah_peminat');
    }

    /**
     * Dapatkan jumlah peminat dan pengambil pada suatu kelas
     * @param $kodeKelas
     * @param string &$peminat
     * @param string &$pengambil
     */
    public static function dapatkanJumlahPeminatPengambilKelasIni($kodeKelas, &$peminat, &$pengambil) {
        $c = PengampuKelas::whereId($kodeKelas)->first();
        $peminat = $c->jumlah_peminat;
        $pengambil = $c->jumlah_pengambil;
    }

    /**
     * Check bila MK yang sama dengan $kodeKelas yang dipilih oleh mahasiswa ini ternyata telah terambil pada Tahun
     * Ajaran yang aktif.
     * @param string $nim
     * @param string $kodeKelas
     * @param string|null $ta bila null maka diambil dari Tahun Ajaran yang aktif
     * @return  bool
     */
    public function mataKuliahSamaTelahTerambil($nim, $kodeKelas, $ta = null)
    {
        $ta = ($ta === null ? ReferensiAkademikFactory::getTAAktif()->tahun_ajaran : $ta);
        // fakta bahwa kita harus melakukan query terhadap semua MK yang telah diambil oleh mahasiswa ini dan melakukan
        // join terhadap kelas yang ingin diambilnya dan bila jumlah record adalah 0 maka belum ada yang diambil, dan
        // bila jumlah > 0 maka telah ada yang diambil
        $jumlah = \DB::table('rincian_studi as ris')
            ->join('rencana_studi as rs', function($join) use($nim, $ta) {
                $join->on('rs.id', '=', 'ris.rencana_studi_id');
                $join->where('rs.tahun_ajaran', '=', $ta);
                $join->where('rs.mahasiswa_id', '=', $nim);
            })
            ->join('pengampu_kelas as pk', 'pk.id', '=', 'ris.kelas_diambil_id')
            ->join('pengampu_kelas as pk2', function($join) use($kodeKelas) {
                $join->on('pk2.mata_kuliah_id', '=', 'pk.mata_kuliah_id');
                $join->where('pk2.id', '=', $kodeKelas);
            })
            ->selectRaw("COUNT(pk2.mata_kuliah_id) AS jumlahMK")
            ->value("jumlahMK");

        return (int) $jumlah > 0;
    }

    /**
     * Tambahkan jumlah yang mengambil kelas
     * @param $kodeIdKelas
     */
    public static function tambahPengambilKelasIni($kodeIdKelas)
    {
        PengampuKelas::where('id', '=', $kodeIdKelas)->increment('jumlah_pengambil');
    }

    /**
     * Kurangkan jumlah yang mengambil kelas
     * @param $kodeIdKelas
     */
    public static function kurangiPengambilKelasIni($kodeIdKelas)
    {
        PengampuKelas::where('id', '=', $kodeIdKelas)->decrement('jumlah_pengambil');
    }
}