<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCards extends Model
{
    use SoftDeletes;
    protected $table = 'mst_stock_cards';
    protected $fillable = ['product_id','quantity','type','datetime','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function product()
    {
        return $this->hasOne('App\Product');
    }
    public function salesorderdetail()
    {
        return $this->hasOne('App\SalesOrderDetail');
    }
   
}
