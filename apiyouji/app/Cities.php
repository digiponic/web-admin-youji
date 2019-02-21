<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Districs;
use App\States;

class Cities extends Model
{
    use SoftDeletes;
    protected $table        = 'mst_cities';
    protected $fillable     = ['state_id', 'code', 'name','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    

    public function districs()
    {
        return $this->belongsTo('App\Districs');
    }
    public function states()
    {
        return $this->belongsTo('App\States');
    }
}

