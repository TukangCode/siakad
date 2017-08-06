<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
	protected $fillable = ['nama_tugas', 'keterangan', 'deadline','pengampu_id'];
}
