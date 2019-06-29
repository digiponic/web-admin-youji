<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\PromotionDetail;
use App\Promotion;

class PromotionDetailController extends Controller
{
    public function all()
    {
        $data = PromotionDetail::All();
        return $data;
    }
    public function show($id)
    {
        $arr = array ();
        $data = PromotionDetail::where('promotion_id', $id)->get();
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
        $data = PromotionDetail::FirstOrCreate([
            "promotion_id"      => $request->json()->get('promotion_id'),
            "product_id"         => $request->json()->get('product_id'),
            "created_user"      => null,
            "updated_user"      => null,
            "deleted_user"      => null, 
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
        $exec = PromotionDetail::insert($data);

        $status = ($exec) ? true : false;
        
        $msg = array(
            'status'    => $status,
            'message'   => ($status) ? 'Success' : 'Failed'
        );
        return $msg;
    }
    public function update(Request $request, Response $response, $id)
    {
        $data = PromotionDetail::FindOrFail($id)->update([
            "promotion_id"     => $request->json()->get('promotion_id'),
            "product_id"       => $request->json()->get('product_id'),
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
        $data = PromotionDetail::FindOrFail($id)->delete();
        $status = ($data)? true : false;
        $msg = array (
            'status' => $status,
            'message' => ($status) ? 'Succes' : 'Failed'
        );
        return $msg;
    }
}
