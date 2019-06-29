<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use CRUDBooster;

class ApiPelangganController extends Controller
{
    public function getAlamat(Request $request)
    {
        $param = $request->all();

        $data = DB::table('tb_alamat_pelanggan as ap')
                    ->join('tb_provinsi as p','p.id','=','ap.kode_provinsi')
                    ->join('tb_kota as k','k.id','=','ap.kode_kota')
                    ->join('tb_kecamatan as kc','kc.id','=','ap.kode_kecamatan')
                    ->where('ap.id',$param['id'])
                    ->select('ap.id','ap.alamat','ap.rt','ap.rw','ap.kodepos','p.keterangan as provinsi','k.keterangan as kota','kc.keterangan as kecamatan')
                    ->first();
        
        $alamat = $data->alamat.' RT '.(empty($data->rt) ? '000' : $data->rt).' / RW '.(empty($data->rw) ? '000' : $data->rw).', '.$data->kecamatan.', '.$data->kota.', '.$data->provinsi;
        return $alamat;
    }
}
