<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use CRUDBooster;

class ApiAreaController extends Controller
{
    public function getProvinsi()
    {
        $data = DB::table('tb_provinsi')                    
                    ->where('is_visible',1)
                    ->whereNull('deleted_at')
                    ->select('id','kode','keterangan')
                    ->get();
        
        return $data;
    }

    public function getKota(Request $request)
    {
        $param = $request->all();

        if(!empty($param['kode_provinsi'])){
            $data = DB::table('tb_kota')
                        ->where('kode_provinsi', $param['kode_provinsi'])
                        ->where('is_visible',1)                        
                        ->whereNull('deleted_at')
                        ->select('id','kode','keterangan')
                        ->get();
        }else{
            $data = 'Parameter kode_provinsi dibutuhkan';
        }
        
        return $data;
    }

    public function getKecamatan(Request $request)
    {
        $param = $request->all();

        if(!empty($param['kode_kota'])){
            $data = DB::table('tb_kecamatan')
                        ->where('kode_kota', $param['kode_kota'])
                        ->where('is_visible',1)                        
                        ->whereNull('deleted_at')
                        ->select('id','keterangan')
                        ->get();
        }else{
            $data = 'Parameter kode_kota dibutuhkan';
        }
        
        return $data;
    }

    public function getKelurahan(Request $request)
    {
        $param = $request->all();

        if(!empty($param['kode_kecamatan'])){
            $data = DB::table('tb_kelurahan')
                        ->where('kode_kecamatan', $param['kode_kecamatan'])
                        ->where('is_visible', 1)                        
                        ->whereNull('deleted_at')
                        ->select('id','keterangan')
                        ->get();
        }else{
            $data = 'Parameter kode_kecamatan dibutuhkan';
        }
        
        return $data;
    }
}
