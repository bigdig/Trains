<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeachProfess;

class CommonController extends Controller
{
    public function profess(Request $request){
		$client = $request->get('client','1');
		if(!$client){
			return response()->json([
				'code'=>0,
				'msg' =>'缺少参数'
			]);
		}
        $lists = TeachProfess::where('status',1)->where('profess_type',$client)->get();
        return response()->json([
            'code'=>200,
            'msg' =>'ok',
            'data'=>$lists
        ]);
    }
}
