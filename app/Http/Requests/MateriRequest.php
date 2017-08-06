<?php

namespace Stmik\Http\Requests;

use Stmik\Http\Requests\Request;

class MateriRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
			'pengampu_id' => 'required',
			'nama_materi' => 'required',
			'filename' => 'required|mimes:zip,rar,txt,jpeg,jpg,png',
        ];
    }
}
