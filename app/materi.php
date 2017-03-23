<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materis';
	protected $fillable = ['nama_materi', 'filename', 'pengampu_id']; 
}
