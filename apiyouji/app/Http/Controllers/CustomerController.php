<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    public function all()
    {
        // $data = Customer::all();
        $data = Customer::All();
        return $data;
    }
    public function show($id)
    {
        $data = Customer::find($id);
        return $data;
    }
    public function store(Request $request)
    {
        $data = Customer::FirstOrCreate([
            "code"      => $request->json()->get('code'),
            "name"      => $request->json()->get('name'),
            "desc"      => $request->json()->get('desc'),
            "email"     => $request->json()->get('email'),
            "phone"     => $request->json()->get('phone'),
            "created_user"  => null,
            "updated_user"  => null,
            "deleted_user"  => null, 
        ]);
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
        $exec = Customer::insert($data);

        $status = ($exec) ? true : false;
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update(Request $request, Response $response, $id)
    {
        $data = Customer::FindOrFail($id)->update([
            "code"      => $request->json()->get('code'),
            "name"      => $request->json()->get('name'),
            "desc"      => $request->json()->get('desc'),
            "email"     => $request->json()->get('email'),
            "phone"     => $request->json()->get('phone'),
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
        $data = Customer::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
