<?php
/**
 * Master Mahasiswa
 * todo: implementasi filter berdasarkan status mahasiswa!
 * Date: 11/04/16
 * Time: 12:54
 */

namespace Stmik\Http\Controllers\Master;

use Stmik\Factories\MasterDosenFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\MasterDosenRequest;

class DosenController extends Controller
{
    use GetDataBTTableTrait;
    /** @var MasterMahasiswaFactory  */
    protected $factory;

    public function __construct(MasterDosenFactory $factory)
    {
        $this->factory = $factory;

        $this->middleware('auth.role:akma');
    }

    /**
     * Tampilkan index
     * @return $this
     */
    public function index()
    {
        return view('master.dosen.index')
            ->with('layout', $this->getLayout());
    }

    /**
     * Tampilkan form untuk proses update
     * @param $nim
     * @return mixed
     */
    public function edit($nomor_induk)
    {
        return view('master.dosen.form')
            ->with('data', $this->factory->getDataDosen($nomor_induk))
            ->with('action', route('master.dosen.update', ['nomor_induk'=>$nomor_induk]));
    }

    /**
     * Lakukan proses terhadap inputan yang di post oleh user
     * @param $nomor_induk
     * @param MasterDosenRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($nomor_induk, MasterDosenRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($nomor_induk, $input)) {
            return $this->edit($nomor_induk)->with('success', "Data dosen telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }

    /**
     * Tampilkan form untuk mencatat dosen baru
     * @return mixed
     */
    public function create()
    {
        return view('master.dosen.form')
            ->with('data', null)
            ->with('action', route('master.dosen.store'));
    }

    /**
     * Simpan data baru
     * @param MasterMahasiswaRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(MasterDosenRequest $request)
    {
        $input = $request->all();
        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data dosen telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }

    public function delete($nomor_induk)
    {
        if($this->factory->delete($nomor_induk)) {
            return response("", 200,['X-IC-Remove'=>true]);
        }
        return response(json_encode($this->factory->getErrors()), 500,['X-IC-Remove'=>false]);
    }
}