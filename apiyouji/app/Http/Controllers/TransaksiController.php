<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;

class TransaksiController extends Controller
{
    public function data(Request $request)
    {
        $param = $request->json()->all();
        
        $data = DB::table('tb_penjualan as pj')
                    ->join('tb_general as st', 'pj.status','=','st.id')
                    ->select('pj.*','st.keterangan as status')
                    ->where('pj.customer_id', $param['customer_id'])->get();
        return $data;
    }

    public function detil(Request $request)
    {
        $param = $request->json()->all();
        
        $data = DB::table('tb_penjualan_detail as pd')
                    ->join('tb_general as st', 'pd.satuan','=','st.id')
                    ->select('pd.*','st.keterangan as satuan')
                    // ->where('pd.id_penjualan', $param['id'])
                    ->where('pd.kode_penjualan',$param['kode'])
                    ->get();
        return $data;        
    }

    public function simpan(Request $request)
    {
        $param = $request->json()->all();
        // return $param;

        $penjualan = $param;

        $kode = DB::table('tb_penjualan')->max('id') + 1;
        $kode = 'PNJ/'.date('dmy').'/'.str_pad($kode, 5, 0, STR_PAD_LEFT);

        $penjualan['kode'] = $kode;
        $penjualan['status'] = 25;
        $penjualan['created_at'] = date('Y-m-d H:i:s');

        unset($penjualan['penjualan_detil']);
        // $transaksi = 15;
        $transaksi = DB::table('tb_penjualan')->insertGetId($penjualan);

        $penjualan_detil = $param['penjualan_detil'];
        
        $count = count($penjualan_detil);
        for ($i=0; $i < $count; $i++) { 
            $penjualan_detil[$i]['id_penjualan'] = $transaksi;
            $penjualan_detil[$i]['kode_penjualan'] = $kode;
            $penjualan_detil[$i]['satuan'] = DB::table('tb_general')->where('keterangan',$penjualan_detil[$i]['satuan'])->value('id');
            $penjualan_detil[$i]['subtotal'] = $penjualan_detil[$i]['kuantitas'] * $penjualan_detil[$i]['harga'];
            $penjualan_detil[$i]['grand_total'] = $penjualan_detil[$i]['subtotal'] - $penjualan_detil[$i]['diskon'];
        }

        $transaksi_detail = DB::table('tb_penjualan_detail')->insert($penjualan_detil);

        foreach($penjualan_detil as $pd) {
            $array = array(
                'kode_penjualan'	=> $kode,
                'kode_produk'		=> $pd['kode_produk'],
                'nama_produk'		=> $pd['nama_produk'],
                'satuan'			=> $pd['satuan']
            );
            $produk_stok = array(
                'tanggal'		=> $penjualan['created_at'],
                'kode_produk'	=> $pd['id_produk'],
                'stok_masuk'	=> 0,
                'stok_keluar'	=> $pd['kuantitas'],
                'keterangan'	=> 'Pengurangan stok dari penjualan '.$kode
            );
            $stok = DB::table('tb_produk')->where('id',$pd['id_produk'])->value('stok');

            DB::table('tb_produk_stok')->insert($produk_stok);
            DB::table('tb_produk')->where('id',$pd['id_produk'])->update(['stok'=> abs($stok - $pd['kuantitas'])]);
        }

        if($transaksi_detail){
            $msg = array(
                'status'    => 'success'
            );
        }else{
            $msg = array(
                'status'    => 'failed'
            );            
        }

        return $msg;
    }
}
