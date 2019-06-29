<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Customer;
use App\CustomerAddress;

class CustomerAddressController extends Controller
{
    public function all()
    {
        // $data = CustomerAddress::all();
        $data = CustomerAddress::All();
        return $data;
    }
    public function show($id)
    {   
        $arr = array ();
        $data = CustomerAddress::where('customer_id', $id)->get();
        
        $i = 0;
        foreach ($data as $key => $value) {
            array_push($arr, array(
                $key    => $value,
            ));
            $i++;
        }
        return $arr;
    }
    public function store(Request $request)
    {
        $data = CustomerAddress::FirstOrCreate([
            "customer_id"      => $request->json()->get('customer_id'),
            "city"             => $request->json()->get('city'),
            "district"         => $request->json()->get('district'),
            "created_user"     => null,
            "updated_user"     => null,
            "deleted_user"     => null, 
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
        $exec = CustomerAddress::insert($data);
        
        $status = ($exec) ? true : false;

        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update(Request $request, Response $response, $id)
    {
       $data = CustomerAddress::FindOrFail($id)->update([
        "customer_id"      => $request->json()->get('customer_id'),
        "province"         => $request->json()->get('province'),
        "city"             => $request->json()->get('city'),
        "distric"          => $request->json()->get('distric'),
        "postcode"         => $request->json()->get('postcode'),
        "created_user"     => null,
        "updated_user"     => null,
        "deleted_user"     => null, 
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
        $data = CustomerAddress::findOrFail($id)->delete();
        $status = ($data)?true : false;
        $msg = array(
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;

    }
}
