<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    protected $table = 'ruangans';
	protected $fillable = ['ruang', 'keterangan'];
}
