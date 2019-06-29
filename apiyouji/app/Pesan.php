<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesan extends Model
{
    use SoftDeletes;
    protected $table = 'tb_pesan';
    protected $fillable = ['subjek','pesan','created_at'];
    protected $hidden = ['created_user','updated_user','deleted_user','updated_at','deleted_at'];    
}
