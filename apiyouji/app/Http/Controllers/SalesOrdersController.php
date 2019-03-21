<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use DB;
use App\SalesOrders;
use App\SalesOrderDetail;
use App\StockCards;
use App\Customer;
use App\Product;


class SalesOrdersController extends Controller
{
    public function all()
    {

        $data = DB::table('tb_penjualan')->orderBy('kode', 'desc')->get();
        return $data;
    }
    public function show($customer_id)
    {
        $arr = array();
        $data = SalesOrders::where('customer_id', $customer_id)->get();

        $i = 0;
        foreach ($data as $key => $value) {
            array_push($arr, array(
                $key    => $value,
            ));
            $i++;
        }
        return $arr;
    }
    
    public function store(Request $request)
    {

        $stockcard = array();
        $data = SalesOrders::FirstOrCreate([
            "code"              => $request->json()->get('code'),
            "customer_id"       => $request->json()->get('customer_id'),
            "customer_address_id" => $request->json()->get('customer_address_id'),
            "datetime_order"    => $request->json()->get('datetime_order'),
            "datetime_shipping" => $request->json()->get('datetime_shipping'),            
            "note"              => $request->json()->get('note'),
            "postal_fee"        => $request->json()->get('postal_fee'),
            "discount"          => $request->json()->get('discount'),
            "sub_total"         => $request->json()->get('sub_total'),
            "total"             => $request->json()->get('total'),
            "status"            => $request->json()->get('status'),
        ]);
        $detail_product = $request->json()->get('detail_product');
        $count = count($detail_product);
        for ($i=0; $i < $count; $i++) {
            $detail_product[$i]['sales_order_id'] = $data['id'];
            $detail_product[$i]['created_user'] = $data['customer'][0]['name'];
            $detail_product[$i]['created_at']   = date('Y-m-d H:i:s');
            $detail_product[$i]['updated_at']   = date('Y-m-d H:i:s');           

            array_push($stockcard, array(
                'product_id'    => $detail_product[$i]['product_id'],
                'quantity'      => $detail_product[$i]['quantity'],
                'type'          => 'OUT',
                'note'          => 'Purchasing from '.$data['customer'][0]['name'],
                'datetime'      => date('Y-m-d H:i:s'),
                'created_user'  => $data['customer'][0]['name'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')           
            ));
        }

        $so_detail = SalesOrderDetail::insert($detail_product);
        $stockcard = StockCards::insert($stockcard);

        $status = ($stockcard) ? true : false;
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function store_(Request $request)
    {
        $stockcard = array();
        $data = SalesOrders::FirstOrCreate([
            "code"              => $request->json()->get('code'),
            "customer_id"       => $request->json()->get('customer_id'),
            "datetime_shipping" => $request->json()->get('datetime_shipping'),
            "datetime_order"    => $request->json()->get('datetime_order'),
            "note"              => $request->json()->get('note'),
            "postal_fee"        => $request->json()->get('postal_fee'),
            "discount"          => $request->json()->get('discount'),
            "sub_total"         => $request->json()->get('sub_total'),
            "total"             => $request->json()->get('total'),
            "status"            => $request->json()->get('status'),
        ]);
        $detail_product = $request->json()->get('detail_product');
        $count = count($detail_product);
        for ($i=0; $i < $count; $i++) {
            $detail_product[$i]['trans_sales_order_id'] = $data['id'];
            $detail_product[$i]['created_user'] = $data['customer'][0]['name'];
            $detail_product[$i]['created_at']   = date('Y-m-d H:i:s');
            $detail_product[$i]['updated_at']   = date('Y-m-d H:i:s');           

            array_push($stockcard, array(
                'product_id'    => $detail_product[$i]['product_id'],
                'quantity'      => $detail_product[$i]['quantity'],
                'type'          => 'OUT',
                'datetime'      => date('Y-m-d H:i:s'),
                'created_user'  => $data['customer'][0]['name'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')           
            ));
        }

        $so_detail = SalesOrderDetail::insert($detail_product);
        $stockcard = StockCards::insert($stockcard);

        $status = ($stockcard) ? true : false;
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function batch(Request $request)
    {
        $data = $request->json()->all();
        $exec = SalesOrders::insert($data);

        $status = ($exec) ? true : false;
        
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }    
    public function update(Request $request, Response $response, $id)
    {
        $data = SalesOrders::FindOrFail($id)->update([
            "code"              => $request->json()->get('code'),
            "customer_id"       => $request->json()->get('customer_id'),
            "datetime_shipping" => $request->json()->get('datetime_shipping'),
            "datetime_order"    => $request->json()->get('datetime_order'),
            "note"              => $request->json()->get('note'),
            "postal_fee"        => $request->json()->get('postal_fee'),
            "discount"          => $request->json()->get('discount'),
            "sub_total"         => $request->json()->get('sub_total'),
            "total"             => $request->json()->get('total'),
            "status"            => $request->json()->get('status'),
            "created_user"      => null,
            "updated_user"      => null,
            "deleted_user"      => null, 
        ]);
        $status = ($data)?true : false;
        $msg = array(
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
    public function delete($id)
    {
        $data = SalesOrders::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}