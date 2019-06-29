<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SalesOrderDetail;
use App\Product;
use App\StockCards;

class SalesOrderDetailController extends Controller
{
    public function all()
    {
        $arr = array();
        $data = Product::all();   
        $count = count($data);
        for($i=0;$i<$count;$i++){
            $stock_in = StockCards::where('product_id', $data[$i]['id'])->where('type','IN')->sum('quantity');
            $stock_out = StockCards::where('product_id', $data[$i]['id'])->where('type','OUT')->sum('quantity');
            $stock = $stock_in - $stock_out;
            $data[$i]['stock'] = $stock;
        }
        return $data;
    }
    public function show($id)
    {
        $arr = array();
        $data = SalesOrderDetail::where('trans_sales_order_id', $id)->get();
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
        $data = SalesOrderDetail::FirstOrCreate([
            "trans_sales_order_id"      => $request->json()->get('code'),
            "product_id"                => $request->json()->get('product_id'),
            "quantity"                  => $request->json()->get('quantity'),
            "price"                     => $request->json()->get('price'),
            "subtotal"                  => $request->json()->get('subtotal'),
            "total"                     => $request->json()->get('total'),
            "created_user"              => null,
            "updated_user"              => null,
            "deleted_user"              => null, 
        ] );
        $status = ($data) ? true : false;
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function batch(Request $request)
    {
        $data = $request->json()->all();
        $exec = SalesOrderDetail::insert($data);

        $status = ($exec) ? true : false;
        
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }       
    public function update(Request $request, Response $response, $id)
    {
        $data = SalesOrderDetail::FindOrFail($id)->update([
            "trans_sales_order_id"      => $request->json()->get('code'),
            "product_id"         => $request->json()->get('product_id'),
            "quantity"      => $request->json()->get('quantity'),
            "price"         => $request->json()->get('price'),
            "subtotal"      => $request->json()->get('subtotal'),
            "total"         => $request->json()->get('total'),
            "created_user"  => null,
            "updated_user"  => null,
            "deleted_user"  => null, 
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
        $data = SalesOrderDetail::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
