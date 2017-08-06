<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
	protected $fillable = ['hari', 'jam_masuk', 'jam_keluar','ruangan_id','pengampu_id']; 
}
