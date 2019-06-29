<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Districs;
use App\Cities;

class DistricsController extends Controller
{
    public function all()
    {
        $data = Districs::All();
        return $data;
    }
    public function show($id)
    {
        $data = Districs::find($id);
        return $data;
    }
    public function store(Request $request)
    {   
        $data = Districs::FirstOrCreate([
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
        $data = Districs::FindOrFail($id)->update([
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
        $data = Cities::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
