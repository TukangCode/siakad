<?php

namespace Stmik;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
	protected $fillable = ['perihal', 'keterangan','user_id'];
}
