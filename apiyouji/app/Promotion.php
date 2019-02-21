<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\PromotionDetail;

class Promotion extends Model
{
    use SoftDeletes;
    protected $table = 'mst_promotions';
    protected $appends = ['detail'];
    protected $fillable = ['code','name','desc','discount','created_user','updated_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function PromotionDetail()
    {
        return $this->hasOne('App\PromotionDetail');
    }

    public function getDetailAttribute()
    {
        $detail = PromotionDetail::where('promotion_id', $this->id)->get(['id','promotion_id','product_id']);
        return $detail;
    }
}
