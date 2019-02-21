<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Customer;

class CustomerAddress extends Model
{
    use SoftDeletes;
    protected $table = 'mst_customer_address';
    
    protected $fillable = ['customer_id','type','state','district','postcode','desc','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function Customer()
    {
        return $this->belongsTo('App\Customer');
    }
    
    
}

