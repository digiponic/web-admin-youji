<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\ProductImages;

class ProductImagesController extends Controller
{
    public function all()
    {
        $data = ProductImages::All();
        return $data;
    }
    public function show($id)
    {
        $data = ProductImages::find($id);
        return $data;
        // return $data->product;
    }
    public function store(Request $request)
    {
        $data = ProductImages::FirstOrCreate([
            "product_id"    => $request->json()->get('product_id'),
            "name"          => $request->json()->get('name'),
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
    public function update(Request $request, Response $response, $id)
    {
        $data = ProductImages::FindOrFail($id)->update([
            "product_id"    => $request->json()->get('product_id'),
            "name"          => $request->json()->get('name'),
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
        $data = ProductImages::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
