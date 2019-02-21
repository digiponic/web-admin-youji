<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Generals extends Model
{
    use SoftDeletes;
    protected $table = 'mst_generals';
    protected $fillable = ['code','name','desc','type','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
}
