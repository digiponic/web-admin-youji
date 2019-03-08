<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class TipeController extends Controller
{
    public function data()
    {
        $data = DB::table('tb_tipe')->whereNull('deleted_at')->get();
        return $data;
    }    

    public function detil($keterangan)
    {

        $tipe = DB::table('tb_tipe')
                    ->where('keterangan', $keterangan)
                    ->whereNull('deleted_at')
                    ->first();

        $data = DB::table('tb_general')
                ->where('kode_tipe', $tipe->id)
                ->whereNull('deleted_at')
                ->get();
        
        $count = count($data);
        for ($i=0; $i < $count; $i++) { 
            $data[$i]->gambar = "http://app.digiponic.co.id/youji/storage/app/".$data[$i]->gambar;
        }
        
        return $data;
    }
}
