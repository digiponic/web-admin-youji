<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use CRUDBooster;

class ApiProdukController extends Controller
{
    public function getData(Request $request)
    {
        $param = $request->all();
        $query = DB::table('tb_produk as pd')
                        ->join('tb_general as kt', 'pd.kategori','=','kt.id')
                        ->join('tb_general as jn', 'pd.jenis','=','jn.id')
                        ->join('tb_general as st', 'pd.satuan_jual','=','st.id')
                        ->select('pd.*','kt.keterangan as kategori','jn.keterangan as jenis','st.keterangan as satuan')
                        ->whereNull('pd.deleted_at');
        
        if(!empty($param)){
            $filter_ = [];
            foreach ($param as $key => $value) {
                $filter_['pd.'.$key] = $value;
            }            
            $data = $query->where($filter_);  
        }

        $data = $query->get();

        return $data;
    }

}
