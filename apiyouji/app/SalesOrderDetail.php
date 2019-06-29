<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SalesOrder;
use App\Product;

class SalesOrderDetail extends Model
{
    use SoftDeletes;
    protected $table = 'mst_sales_order_detail';
    protected $appends = ['product'];
    protected $fillable = ['sales_order_id','product_id','quantity','price','subtotal','total','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function SalesOrder()
    {
        return $this->belongsTo('App\SalesOrder');
    }
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function getProductAttribute()
    {
       $product = Product::where('id', $this->product_id)->get();
       return $product;
    }
}

