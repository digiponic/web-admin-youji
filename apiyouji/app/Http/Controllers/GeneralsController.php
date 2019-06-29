<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Generals;

class GeneralsController extends Controller
{
    public function all()
    {
        $data = Generals::All();
        return $data;
    }
    public function show($id)
    {
        $data = Generals::find($id);
        return $data;
    }
    public function store(Request $request)
    {   
        $data = Generals::FirstOrCreate([
            "code"      => $request->json()->get('code'),
            "name"      => $request->json()->get('name'),
            "desc"      => $request->json()->get('desc'),
            "type"      => $request->json()->get('type'),
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
        $data = Generals::FindOrFail($id)->update([
            "code"      => $request->json()->get('code'),
            "name"      => $request->json()->get('name'),
            "desc"      => $request->json()->get('desc'),
            "type"      => $request->json()->get('type'),
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
        $data = Generals::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
