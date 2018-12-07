<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Trains;
use App\Models\TrainCharge;
use Cache;

class TrainController extends Controller
{
    //
    public function trains(Request $request){
        $perPage    = $request->input('perPage',5);
        $page       = $request->input('page',1);
		$client     = $request->get('client',1);
        $lists = Trains::where('status',2)
            ->with('get_charge')
			->where('train_category',$client)
            ->orderBy('sort','asc')
            ->orderBy('created_at','desc')
            ->paginate($perPage,['*'],'page',$page);
        foreach($lists as $key=>$val){
            $nowDate = date("Y-m-d");
            if($val['apply_start'] >$nowDate){
                $lists[$key]['state'] ='报名未开始';
            }elseif($val['apply_start'] <=$nowDate && $val['apply_end'] >=$nowDate){
                $lists[$key]['state'] ='报名中';
            }elseif ($val['apply_end'] <$nowDate && $val['train_start']>$nowDate){
                $lists[$key]['state'] ='报名已结束';
            }elseif($val['train_start']<=$nowDate && $val['train_end']>=$nowDate){
                $lists[$key]['state'] ='培训中';
            }elseif($val['train_end'] <$nowDate){
				$lists[$key]['state'] ='培训结束';
			}
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'ok',
            'data'=>$lists
        ]);
    }

    public function show($id){
        //if(Cache::has("Trains.info-'.$id.'")){
            //$info = Cache::get("Trains.info-'.$id.'");
        //}else{
            $info = Trains::where("status",2)
                ->with('get_charge')
                ->findOrFail($id);
			$nowDate = date("Y-m-d");
            if($info->apply_start >$nowDate){
                $info->state ='报名未开始';
            }elseif($info->apply_start <=$nowDate && $info->apply_end >=$nowDate){
                $info->state ='报名中';
            }elseif ($info->apply_end <$nowDate && $info->train_start>$nowDate){
                $info->state ='报名已结束';
            }elseif($info->train_start<=$nowDate && $info->train_end>=$nowDate){
                $info->state ='培训中';
            }elseif($info->train_end <$nowDate){
                $info->state ='培训结束';
            }
            //Cache::forever("Trains.info-'.$id.'",$info);
        //}
        return response()->json([
            'code'=>'200',
            'msg'=>'ok',
            'data'=>$info
        ]);
    }
	//资料上传设置
    public function train_setting($id){
        $info = TrainCharge::where('train_id',$id)->select('is_card','is_health','is_labor','is_learnership','is_idcard','is_school','is_education','is_profession')->first();
        if($info){
            return response()->json([
                'code'=>'200',
                'msg'=>'ok',
                'data'=>$info
            ]);
        }
    }
}
