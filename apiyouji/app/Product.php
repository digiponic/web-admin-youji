<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// menggunakan metode eloquent
// softdeletes data tidak akan hilang walau di hapus namun tetap tampil di database dan ada create all nya
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Generals;
use App\StockCards;

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'mst_product';
    protected $appends = ['category', 'unit'];
    protected $fillable = ['code','name','desc','category_id','unit_id','price','discount','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function stockCard()
    {
        return $this->hasOne('App\StokCards');
    }
    public function productImage()
    {
        return $this->hasMany('App\ProductImages');
    }
    public function salesorderdetail()
    {
        return $this->hasMany('App\SalesOrderDetail');
    }

    public function getCategoryAttribute()
    {
        $category = Generals::where('id', $this->category_id)->get(['id','code','name','desc','type']);
        return $category;
    }
    public function getUnitAttribute()
    {
        $unit = Generals::where('id', $this->unit_id)->get(['id','code','name','desc','type']);
        return $unit;
    }
    public function getstockcardAttribute()
    {
        $stockcard = StockCards::where('id', $this->unit_id)->get(['product_id','quantity','type','datetime']);
        return $stockcard;
    }

    
}