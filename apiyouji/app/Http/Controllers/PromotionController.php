<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Promotion;

class PromotionController extends Controller
{
    public function all()
    {
        $data = Promotion::All();
        return $data;
    }
    public function show($id)
    {
        $data = Promotion::find($id);
        return $data;
    }
    public function store(Request $request)
    {
        $data = Promotion::FirstOrCreate([
            "code"          => $request->json()->get('code'),
            "name"          => $request->json()->get('name'),
            "desc"          => $request->json()->get('desc'),
            "discount"      => $request->json()->get('discount'),
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
        $exec = Promotion::insert($data);

        $status = ($exec) ? true : false;
        
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update($id, Request $request)
    {
        $data = Promotion::FindOrFail($id)->update([
            "code"          => $request->json()->get('code'),
            "name"          => $request->json()->get('name'),
            "desc"          => $request->json()->get('desc'),
            "discount"      => $request->json()->get('type'),
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
        $data = Promotion::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
