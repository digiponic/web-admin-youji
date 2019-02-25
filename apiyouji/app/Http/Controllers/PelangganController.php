<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class PelangganController extends Controller
{
    public function simpan(Request $request)
    {
        $email = $request->json()->get('email');
        $name = $request->json()->get('name');

        $data = DB::table('tb_customer')
                ->where('email', $email)
                ->where('name', $name)
                ->get();

        if(count($data) < 1){
            $id = DB::table('tb_customer')->insertGetId($request->json()->all());
            $data = DB::table('tb_customer')->where('id', $id)->get();
        }
        
        return $data;
    }
}
