<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Students;
use App\Models\Entry;
use App\Models\NurseryStudents;
use App\Models\WxUser;
use App\Models\TrainCert;
use Log;

class CertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $apply_user = $request->get('apply_user');
        $lists = Entry::with([
			'get_train',
			'get_students'=>function($query){
				$query->with('get_nursery_user');
			}
			])
            ->whereHas('get_students',function($query){
                $query->where('status',4);
            })
            ->where('apply_user',$apply_user)
            ->where('is_paid',1)
            ->get();
        return response()->json(['code'=>200,'msg'=>'ok','data'=>$lists]);
    }
	/**
     * 根据手机号获取自己报名的和自己的证书
     */
    public function get_cert_by_phone(Request $request){
        $phone = $request->get('mobile','');
		//if(empty($phone)){
            //return response()->json(['code'=>0,'msg'=>'手机号为空']);
        //}
        $apply_lists =[];
        $student_lists =[];
        $apply_user = WxUser::where('mobile',$phone)->value('id');
        if($apply_user){
            $apply_lists = Entry::with([
                'get_train'=>function($query){
                    $query->with('get_charge');
                },
                'get_students'=>function($query){
                    $query->with('get_nursery_user');
                }
            ])
                ->whereHas('get_students',function($query){
                    $query->where('status',4);
                })
                ->where('apply_user',$apply_user)
                ->where('is_paid',1)
                ->get();


            $student_ids = NurseryStudents::where('student_phone',$phone)->get();
            $student_ids = array_column($student_ids->toArray(),'id');
            if( !empty($student_ids) ){
                $student_lists = Entry::with([
                    'get_train'=>function($query){
                        $query->with('get_charge');
                    },
                    'get_students'=>function($query){
                        $query->with('get_nursery_user');
                    }
                ])
                    ->whereHas('get_students',function ($query)use ($student_ids,$apply_user){
                        $query->whereIn('student_id',$student_ids);
                        $query->where('status',4);
                        $query->where('apply_user','!=',$apply_user);
                    })
                    ->where('is_paid',1)
                    ->get();
            }
        }else{
            $student_ids = NurseryStudents::where('student_phone',$phone)->get();
            $student_ids = array_column($student_ids->toArray(),'id');
            if( !empty($student_ids) ){
                $student_lists = Entry::with([
                    'get_train'=>function($query){
                        $query->with('get_charge');
                    },
                    'get_students'=>function($query){
                        $query->with('get_nursery_user');
                    }
                ])
                    ->whereHas('get_students',function ($query)use ($student_ids){
                        $query->whereIn('student_id',$student_ids);
                        $query->where('status',4);
                    })
                    ->where('is_paid',1)
                    ->get();
            }
        }

        return response()->json( ['code'=>200,'msg'=>'ok','data'=>['apply_lists'=>$apply_lists,'student_lists'=>$student_lists]] );
    }
	/**
     * 证书详情
     */
    public function cert_detail(Request $request){
        $train_id = $request->get('train_id','');
        $order_id = $request->get('order_id','');
        if( strpos($request->get('student_ids'),',') ){
            $student_ids = explode(',',$request->get('student_ids'));
        }else{
            $student_ids = [$request->get('student_ids')];
        }
        $info = TrainCert::whereIn('student_id',$student_ids)
            ->where('train_id',$train_id)
            ->where('order_id',$order_id)
            ->get();
        $info = $info->toArray();
        foreach($info as $key=>$val){
            $info[$key]['created_at'] = date("Y-m-d",strtotime($val['created_at']));
			$info[$key]['stucent_cert'] = Students::where('student_id',$val['student_id'])
                                                ->where('order_id',$order_id)
                                                ->get();
        }
        return response()->json(['code'=>200,'msg'=>'ok','data'=>$info]);
    }
	/**
     * 一个订单的证书
     */
    public function order_cert($order_id){
        $lists = Entry::with([
            'get_train',
            'get_students'=>function($query){
                $query->with('get_nursery_user');
            }
        ])->whereHas('get_students',function($query){
            $query->where('status',4);
        })->where('id',$order_id)
            ->where('is_paid',1)
        ->get();
        return response()->json(['code'=>200,'msg'=>'ok','data'=>$lists]);
    }
	/**
     * 学员证书
     */
    public function student_cert(Request $request){
        $phone = $request->get('mobile','');
        if($phone){
			$student_ids = NurseryStudents::where('student_phone',$phone)->get();
            $student_ids = array_column($student_ids->toArray(),'id');
            if( !empty($student_ids) ){
                $lists = Entry::with([
                    'get_train',
                    'get_students'=>function($query){
						$query->where('status',4);
                        $query->with('get_nursery_user');
                    }
                    ])
                    ->whereHas('get_students',function ($query)use ($student_ids){
                        $query->whereIn('student_id',$student_ids);
                    })
                    ->where('is_paid',1)
                    ->get();
            }
            return response()->json(['code'=>200,'msg'=>'ok','data'=>$lists]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
