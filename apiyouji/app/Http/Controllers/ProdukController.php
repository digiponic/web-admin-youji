<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use CRUDBooster;
use Storage;
use DB;

class ProdukController extends Controller
{
    public function data()
    {
        $path_ = DB::table('cms_settings')->where('name','lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $data = DB::table('tb_produk as pd')
                    ->join('tb_general as cb', 'pd.cabang','=','cb.id')
                    ->join('tb_general as kt', 'pd.kategori','=','kt.id')
                    ->join('tb_general as jn', 'pd.jenis','=','jn.id')
                    ->join('tb_general as st', 'pd.satuan','=','st.id')
                    ->select('pd.*','cb.keterangan as cabang','kt.keterangan as kategori','jn.keterangan as jenis','st.keterangan as satuan')
                    ->whereIn('pd.jenis',[8,22])
                    ->whereNull('pd.deleted_at')
                    ->get();

        $count = count($data);
        for ($i=0; $i < $count; $i++) { 
            if($data[$i]->gambar == null)
                $data[$i]->gambar = null;
            else
                $data[$i]->gambar = $path."/".$data[$i]->gambar;
        }

        return $data;
    }

    public function filter(Request $request)
    {
        $path_ = DB::table('cms_settings')->where('name','lokasi_penyimpanan')->get();
        $path = $path_[0]->content;

        $filter = $request->json()->all();
        foreach ($filter as $key => $value) {
            $filter_['pd.'.$key] = DB::table('tb_general')->where('keterangan',$value)->value('id');
        }
        
        $data = DB::table('tb_produk as pd')
                    ->join('tb_general as cb', 'pd.cabang','=','cb.id')
                    ->join('tb_general as kt', 'pd.kategori','=','kt.id')
                    ->join('tb_general as jn', 'pd.jenis','=','jn.id')
                    ->join('tb_general as st', 'pd.satuan','=','st.id')
                    ->select('pd.*','cb.keterangan as cabang','kt.keterangan as kategori','jn.keterangan as jenis','st.keterangan as satuan')
                    ->where($filter_)
                    ->whereNull('pd.deleted_at')
                    ->get();
        

        $count = count($data);
        for ($i=0; $i < $count; $i++) { 
            if($data[$i]->gambar == null)
                $data[$i]->gambar = null;
            else
                $data[$i]->gambar = $path."/".$data[$i]->gambar;
        }

        return $data;
    }
}
