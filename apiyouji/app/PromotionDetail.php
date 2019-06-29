<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PromotionDetail extends Model
{
    use SoftDeletes;
    protected $table = 'mst_promotion_detail';
    protected $appends = ['product'];    
    protected $fillable = ['promotion_id','product_id','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function Promotion()
    {
        return $this->belongsTo('App\Promotion');
    }
    public function getpromotionDetailAttribute()
    {
        $detail_promotion = Promotion::where('id', $this->promotion_id)->get();
        return $detail_promotion;
    }

    public function getProductAttribute()
    {
       $product = Product::where('id', $this->product_id)->get(['id','code','name','desc','category_id','unit_id','price','discount']);
       return $product;
    }    
}
