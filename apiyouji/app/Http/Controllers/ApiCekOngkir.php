<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class ApiCekOngkir extends Controller
{
    public function data($id)
    {
        $default = DB::table('cms_settings')->where('name','ongkos_kirim')->value('content');
        $kecamatan = DB::table('tb_customer')->where('id', $id)->value('kode_kecamatan');
        $ongkir = DB::table('tb_ongkoskirim')->where('kode_kecamatan', $kecamatan)->whereNull('deleted_at')->value('harga_ongkos');

        if(empty($ongkir)){
            $ongkir = $default;
        }
        return $ongkir;
    }

}
