<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Cities;

class CitiesController extends Controller
{
    public function all()
    {
        $data = Cities::All();
        return $data;
    }
    public function show($id)
    {
        // $data = Cities::find($id);
        // return $data;
        
    }
    public function store(Request $request)
    {   
        $data = Cities::FirstOrCreate([
            "state_id"      => $request->json()->get('code'),
            "code"          => $request->json()->get('name'),
            "name"          => $request->json()->get('desc'),
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
        $data = Cities::FindOrFail($id)->update([
            "state_id"      => $request->json()->get('code'),
            "code"          => $request->json()->get('name'),
            "name"          => $request->json()->get('desc'),
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
        $data = Cities::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
