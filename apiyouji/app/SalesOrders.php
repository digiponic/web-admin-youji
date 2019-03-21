<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Customer;
use App\SalesOrderDetail;

class SalesOrders extends Model
{
    use SoftDeletes;
    protected $table = 'tb_penjualan';
    protected $appends = ['customer_id'];
    protected $fillable = ['kode','keterangan','tanggal','sub_total','pajak','diskon_tipe','diskon','grand_total','users_id','customer_id','status','id_cabang','created_user','update_user','deleted_user'];
    protected $hidden = ['created_user','updated_user','deleted_user','created_at','updated_at','deleted_at'];    
    
    public function SalesOrderDetail()
    {
        return $this->hasOne(SalesOrderDetail::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function getCustomerAttribute()
    {
        $customer = Customer::where('id', $this->customer_id)->get(['id','code','name','desc','email','phone']);
        return $customer;
    }
    public function getDetailAttribute()
    {
        $detail = SalesOrderDetail::where('sales_order_id', $this->id)->get();
        return $detail;
    }
}