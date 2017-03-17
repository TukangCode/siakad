<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
	protected $fillable = ['perihal', 'keterangan','dosen_id'];
}
