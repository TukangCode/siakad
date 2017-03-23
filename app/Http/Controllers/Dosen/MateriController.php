<?php

namespace Stmik\Http\Controllers\Dosen;

use Stmik\Factories\MateriFactory;
use Stmik\Http\Controllers\Controller;
use Stmik\Http\Controllers\GetDataBTTableTrait;
use Stmik\Http\Requests\MateriRequest;
use Illuminate\Http\UploadedFile;

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
    public function create()
    {
        return view('dosen.materi.form')
            ->with('data', null)
            ->with('action', route('dosen.materi.store'));
    }
    public function store(MateriRequest $request)
    {
		if($request->hasFile('filename')) {
            $file = $request->file('filename');
            //getting timestamp
            $timestamp = date('Ymd_His', strtotime('now', time()));

            $nama_file = $timestamp. '-' .$request->filename->extension();
            
            $request->filename = $nama_file;

            $file->move(public_path().'/materi/', $nama_file);
        }
        $input = $request->all();

        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data NIM {$this->factory->getLastInsertId()} telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function edit($id)
    {
        return view('dosen.materi.form')
            ->with('data', $this->factory->getDataMateri($id))
            ->with('action', route('dosen.materi.update', ['id'=>$id]));
    }

}
