<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Pesan;

class PesanController extends Controller
{
    public function all()
    {
        $data = Pesan::All();
        return $data;
    }
    public function show($id)
    {
        $data = Pesan::find($id);
        return $data;
    }
    public function store(Request $request)
    {   
        $data = Pesan::FirstOrCreate([
            "pesan"      => $request->json()->get('pesan'),
            "created_at"      => $request->json()->get('created_at')
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
        $data = Pesan::FindOrFail($id)->update([
            "pesan"      => $request->json()->get('pesan'),
            "created_at"      => $request->json()->get('created_at')
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
        $data = Pesan::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
