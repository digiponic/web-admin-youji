<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\StockCards;

class StockCardsController extends Controller
{
    public function all()
    {
        $data = StockCards::All();
        return $data;
    }
    public function show($id)
    {
        $data = StockCards::find($id);
        return $data;
    }
    public function store(Request $request)
    {
        $data = StockCards::FirstOrCreate([
            "product_id"    => $request->json()->get('product_id'),
            "quantity"      => $request->json()->get('quantity'),
            "type"          => $request->json()->get('type'),
            "datetime"      => $request->json()->get('datetime'),
            "created_user"  => null,
            "updated_user"  => null,
            "deleted_user"  => null, 
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
        $exec = StockCards::insert($data);

        $status = ($data) ? true : false;
        
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update(Request $request, Response $response, $id)
    {
        $data = StockCards::FindOrFail($id)->update([
            "product_id"    => $request->json()->get('product_id'),
            "quantity"      => $request->json()->get('quantity'),
            "type"          => $request->json()->get('type'),
            "datetime"      => $request->json()->get('datetime'),
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
        $data = StockCards::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
