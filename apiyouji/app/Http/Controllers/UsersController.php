<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Users;


class UsersController extends Controller
{
    public function all()
    {
        $data = Users::All();
        return $data;
    }
    public function show($id)
    {
        $data = Users::find($id);
        return $data;
    }
    public function store(Request $request)
    {
        $data = Users::FirstOrCreate([
            "email"                 => $request->json()->get('email'),
            "password"              => Hash::make($request->json()->get('password')),
            "name"                  => $request->json()->get('name'),
            "nama_lengkap"          => $request->json()->get('nama_lengkap'),
            "photo"                 => $request->json()->get('photo'),
            "telp"                  => $request->json()->get('telp'),
            "alamat"                => $request->json()->get('alamat'),
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
        $data = Users::FindOrFail($id)->update([
            "email"                 => $request->json()->get('email'),
            "password"              => Hash::make($request->json()->get('password')),
            "name"                  => $request->json()->get('name'),
            "nama_lengkap"          => $request->json()->get('nama_lengkap'),
            "photo"                 => $request->json()->get('photo'),
            "telp"                  => $request->json()->get('telp'),
            "alamat"                => $request->json()->get('alamat'),
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
        $data = Users::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
