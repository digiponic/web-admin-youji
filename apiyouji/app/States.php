<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Cities;

class States extends Model
{
    use SoftDeletes;
    protected $table = 'mst_states';
    protected $appends = ['cities'];
    protected $fillable = ['code','name','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function cities()
    {
        $this->hasMany(Cities::class, 'state_id');
    }

    public function getCitiesAttribute()
    {
        $cities = Cities::where('state_id', $this->id)->get(['code','name']);
        return $cities;
    }
}
