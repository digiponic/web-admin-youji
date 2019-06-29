<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\States;

class StatesController extends Controller
{
    public function all()
    {
        $data = States::All();
        return $data;
    }
    public function show($id)
    {
        $data = States::find($id);
        return $data;
    }
    public function store(Request $request)
    {   
        $data = States::FirstOrCreate([
            "id"            => $request->json()->get('code'),
            "code"          => $request->json()->get('name'),
            "city_id"       => $request->json()->get('name'),
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
        $data = States::FindOrFail($id)->update([
            "id"            => $request->json()->get('code'),
            "code"          => $request->json()->get('name'),
            "city_id"       => $request->json()->get('name'),
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
        $data = States::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
