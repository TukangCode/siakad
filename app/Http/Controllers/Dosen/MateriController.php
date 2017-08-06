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
        $input = $request->all();
		if($request->hasFile('filename')) {
            $file = $request->file('filename');
            //getting timestamp
            $timestamp = date('Ymd_His', strtotime('now', time()));

            $input['filename'] = 'materi_'.$timestamp. '.' .$request->filename->extension();

            $file->move(public_path().'/materi/', $input['filename']);
        }

        if($this->factory->store($input)) {
            return $this->create()->with('success', "Data Materi telah ditambahkan, silahkan lakukan proses penambahan lainnya!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function edit($id)
    {
        return view('dosen.materi.form')
            ->with('data', $this->factory->getDataMateri($id))
            ->with('action', route('dosen.materi.update', ['id'=>$id]));
    }
	
    public function update($id, MateriRequest $request)
    {
        $input = $request->all();
        if($this->factory->update($id, $input)) {
            return $this->edit($id)->with('success', "Data Materi telah terupdate!");
        }
        return response(json_encode($this->factory->getErrors()), 500);
    }
    public function delete($id)
    {
        if($this->factory->delete($id)) {
            return response("", 200,['X-IC-Remove'=>true]);
        }
        return response(json_encode($this->factory->getErrors()), 500,['X-IC-Remove'=>false]);
    }

}
