<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// memanggil product app

use DB;
use App\Product;
use App\StockCards;

class ProductController extends Controller
{
    // select all
    public function all()
    {
        $arr = array();
        $data = Product::All();
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
        $data = Product::find($id);   
        return $data;   
    }

    public function store(Request $request)
    {
        $data = Product::FirstOrCreate([
            'code' => $request->json()->get('code'),
            'name' => $request->json()->get('name'),
            'desc' => $request->json()->get('desc'),
            'category_id' => $request->json()->get('category_id'),
            'unit_id' => $request->json()->get('unit_id'),
            'price' => $request->json()->get('price'),
            'discount' => $request->json()->get('discount'),
            "created_user"  => null,
        ]);

        if($data){
            $stockCard = StockCards::create([
                'product_id'    => $data['id'],
                'quantity'      => 0,
                'type'          => 'IN',
                'datetime'      => date('Y-m-d H:i:s'),
                "created_user"  => null,        
            ]);
            $status = ($stockCard) ? true : false;
        }else{
            $status = false;
        }

        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update(Request $request, Response $response, $id)
    {
        $data = Product::FindOrFail($id)->update([
            'code' => $request->json()->get('code'),
            'name' => $request->json()->get('name'),
            'desc' => $request->json()->get('desc'),
            'category_id' => $request->json()->get('category_id'),
            'unit_id' => $request->json()->get('unit_id'),
            'price' => $request->json()->get('price'),
            'discount' => $request->json()->get('discount'),
            "updated_user"  => null,
        ]);
        $status = ($data) ? true : false;
        $msg = array(
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
    public function delete($id)
    {
        $data = Product::findOrFail($id)->update(['deleted_user' => null]);
        $data = Product::findOrFail($id)->delete();
        $status = ($data) ? true : false;
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }

    public function stockcard($id)
	{
        $data = Product::select('id','code', 'name', 'desc','category_id','unit_id','price','discount')->where('id', $id)->get(); 
        $data['stockcard'] = StockCards::select('id','quantity','type','datetime')->where('product_id', $id)->get();

        return $data;
    }
}
