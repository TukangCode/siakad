<?php

namespace Stmik\Http\Requests;

use Stmik\Http\Requests\Request;

class JadwalRequest extends Request
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
            //'hari' => 'required',
			//'pengampu_id' => 'required|exists:pengampu_kelas,id',
			//'jam_masuk' => 'required|time',
			//'jam_keluar' => 'required|time',
			//'ruangan_id' => 'required|exists:ruangans,id'
        ];
    }
}
