<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\CustomerAddress;

class Customer extends Model
{
    use SoftDeletes;
    protected $table = 'mst_customers';
    protected $appends = ['address'];
    protected $fillable = ['code','name', 'desc','email','phone','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    

    public function CustomerAddress()
    {
        return $this->hasMany('App\CustomerAddress');
    }
    public function SalesOrders()
    {
        return $this->hasMany('App\SalesOrders');
    }
    public function getAddressAttribute()
    {
        $address = CustomerAddress::where('customer_id', $this->id)->get(['id','customer_id','type','state','city','district','postcode','desc']);
        return $address;
    }

}