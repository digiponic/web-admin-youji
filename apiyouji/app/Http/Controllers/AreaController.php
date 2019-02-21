<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class AreaController extends Controller
{
    public function data(Request $request)
    {
        $param = $request->json()->all();

        // cek apakah ada parameter
        if(empty($param)){
            $data = DB::table('tb_provinsi')->whereNull('deleted_at')->get();
        }else{
            $area = DB::table('tb_provinsi')->where('kode',$param['kode'])->first();
            // cek kode di area provinsi
            if(empty($area)){
                $area = DB::table('tb_kota')->where('kode',$param['kode'])->first();
                // cek kode di area kota
                if(empty($area)){
                    // jika kosong maka kode di cari di area kecamatan
                    $data = DB::table('tb_kecamatan')->where('kode',$param['kode'])->get();
                }else{
                    $data = DB::table('tb_kecamatan')->where('kode_kota',$area->id)->whereNull('deleted_at')->get();
                }
            }else{
                $data = DB::table('tb_kota')->where('kode_provinsi',$area->id)->whereNull('deleted_at')->get();
                if(empty($data)){
                    $area = DB::table('tb_kota')->where('kode',$param['kode'])->first();
                    $data = DB::table('tb_kecamatan')->where('kode_kota',$area->id)->whereNull('deleted_at')->get();
                }
            }
        }
        
        return $data;
    }

}
