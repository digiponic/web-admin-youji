<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Cities;

class Districs extends Model
{
    use SoftDeletes;
    protected $table        =   'mst_districs';
    protected $appends      =   ['cities'];
    protected $fillable     =   ['city_id','code','name','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function cities()
    {
        $this->hasOne('App\Cities');
    }

    public function getCitiesAttribute()
    {
        $cities = Cities::where('state_id', $this->id)->get(['city_id','code','name']);
        return $cities;
    }
}
  
    
