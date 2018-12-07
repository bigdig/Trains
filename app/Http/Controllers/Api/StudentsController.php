<?php

namespace App\Http\Controllers\Api;

use Exception;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NurseryStudents;
use App\Models\ApplyStudents;
use App\Models\Students;
use App\Models\Entry;
use App\Models\TrainCharge;
use App\Repositories\Eloquent\NurseryStudentsRepositoryEloquent;
use App\Repositories\Eloquent\StudentsRepositoryEloquent;

class StudentsController extends Controller
{
    //
    protected $nurseryStudentsRepositoryEloquent;
	protected $studentsRepositoryEloquent;

    public function __construct(NurseryStudentsRepositoryEloquent $nurseryStudentsRepositoryEloquent,StudentsRepositoryEloquent $studentsRepositoryEloquent)
    {
        $this->nurseryStudentsRepositoryEloquent=$nurseryStudentsRepositoryEloquent;
        $this->studentsRepositoryEloquent=$studentsRepositoryEloquent;
    }
    public function nursery_students(Request $request){
        $validator = Validator::make($request->all(), [
            'contract_no'   => 'required',
            'apply_user'    =>'required',
            'train_id'      =>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code'=>'1000',
                'msg'=>'参数错误',
                'data'=>''
            ]);
        }
        $contract_no = $request->get('contract_no','');
        $train_id    = $request->get('train_id','');
        $apply_user    = $request->get('apply_user','');
        $lists       = NurseryStudents::where('contract_no',$contract_no)
            ->where('apply_user',$apply_user)
            ->get()->toArray();
        $apply_students = ApplyStudents::where('contract_no',$contract_no)
            ->where('apply_user',$apply_user)
            ->where('train_id',$train_id)
            ->get()->toArray();
        $apply_students_ids = array_column($apply_students,'student_id');
        $train_charge = TrainCharge::where('train_id',$train_id)->first();
        foreach($lists as $key=>$val){
            if( in_array($val['id'],$apply_students_ids) ){
                $lists[$key]['is_apply']=1;
            }else{
                $lists[$key]['is_apply']=0;
            }
			$lists[$key]['idcard_yn'] = $train_charge->is_idcard ? ($val['idcard'] ? "0":"1") : 0;
            $lists[$key]['card_yn']   = $train_charge->is_card ? ($val['card_z'] ? "0":"1") : 0;
            $lists[$key]['health_yn'] = $train_charge->is_health ? ($val['health_1'] ? "0":"1") : 0;
            $lists[$key]['learnership_yn'] = $train_charge->is_learnership ? ($val['learnership'] ? "0":"1") : 0;

            $lists[$key]['school_yn']    = $train_charge->is_school ? ($val['school'] ? "0":"1") : 0;
            $lists[$key]['education_yn'] = $train_charge->is_education ? ($val['education'] ? "0":"1") : 0;
            $lists[$key]['profession_yn']= $train_charge->is_profession ? ($val['profession'] ? "0":"1") : 0;
        }
        return response()->json([
            'code'=>'200',
            'msg'=>'msg',
            'data'=>$lists
        ]);
    }
    public function save_nursery_students(Request $request){
        $validator = Validator::make($request->all(), [
            'apply_user'   => 'required',
            'contract_no'   => 'required',
            'student_name'  => 'required',
            'student_phone' => 'required',
            'student_sex'   => 'required',
            'student_position'=> 'required',
            //'profession'    => 'required',
            //'school'        => 'required',
			'client'        => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code'=>'1000',
                'msg'=>'信息不完整',
                'data'=>''
            ]);
        }
		$count = NurseryStudents::where('apply_user',$request->get('apply_user'))
            ->where('contract_no',$request->get('contract_no'))
            ->where('student_phone',$request->get('student_phone'))
            ->count();
        if($count){
            return response()->json([
                'code'=>'1000',
                'msg'=>'重复添加',
                'data'=>''
            ]);
        }
        //$id = $this->nurseryStudentsRepositoryEloquent->saveStudents($request);
		$id = NurseryStudents::create([
            'apply_user'     =>$request->get('apply_user'),
            'contract_no'    =>$request->get('contract_no'),
            'student_type'   =>$request->get('client',1),
            'student_name'   =>$request->get('student_name'),
            'student_sex'    =>$request->get('student_sex'),
            'student_phone'  =>$request->get('student_phone'),
            'student_position' =>$request->get('student_position'),
            'school'         =>$request->get('school'),
            'education'      =>$request->get('education'),
            'profession'     =>$request->get('profession'),
            'idcard'         =>$request->get('idcard'),
            'card_z'         =>$request->get('card_z'),
            'card_f'         =>$request->get('card_f'),
            'health_1'       =>$request->get('health_1'),
            'health_2'       =>$request->get('health_2'),
            'health_3'       =>$request->get('health_3'),
            'labor_1'        =>$request->get('labor_1'),
            'labor_2'        =>$request->get('labor_2'),
            'learnership'    =>$request->get('learnership'),
        ])->id;
        if($id){
            return response()->json([
                'code'=>'200',
                'msg'=>'ok',
                'data'=>[
                    'id'=>$id
                ]
            ]);
        }else{
            return response()->json([
                'code'=>'1009',
                'msg'=>'添加失败',
                'data'=>[]
            ]);
        }
    }
    public function nursery_students_edit($id){
        $info = $this->nurseryStudentsRepositoryEloquent->find($id);
        if($info){
            return response()->json([
                'code'=>'200',
                'msg'=>'ok',
                'data'=>$info
            ]);
        }else{
            return response()->json([
                'code'=>'1004',
                'msg'=>'未找到',
                'data'=>[]
            ]);
        }
    }
    public function nursery_students_update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'            => 'required',
//            'contract_no'   => 'required',
            'student_name'  => 'required',
            'student_phone' => 'required',
            'student_sex'   => 'required',
            'student_position'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code'=>'1000',
                'msg'=>'参数错误',
                'data'=>''
            ]);
        }
        $result = $this->nurseryStudentsRepositoryEloquent->update($request->all(),$request->input('id'));
        if($result){
            return response()->json([
                'code'=>'200',
                'msg'=>'ok',
                'data'=>[]
            ]);
        }else{
            return response()->json([
                'code'=>'1009',
                'msg'=>'更新失败',
                'data'=>[]
            ]);
        }
    }
    public function save_apply_students(Request $request){
        $students = $request->get('student_id');
        $contract_no = $request->get('contract_no','');
        $train_id    = $request->get('train_id','');
        $apply_user  = $request->get('apply_user');

        ApplyStudents::where('contract_no',$contract_no)
            ->where('apply_user',$apply_user)
            ->where('train_id',$train_id)
            ->delete();
        if($students){
            $students = explode(',',$students);
            foreach($students as $student){
                ApplyStudents::create([
                    'student_id' =>$student,
                    'apply_user' =>$apply_user,
                    'contract_no'=>$contract_no,
                    'train_id'   =>$train_id
                ]);
            }
        }

        return response()->json([
            'code'=>'200',
            'msg'=>'ok',
            'data'=>[]
        ]);
    }
    public function apply_students_del($id){
        if( ApplyStudents::where('id',$id)->delete() ){
            return response([
                'code'=>'200',
                'msg'=>'ok',
                'data'=>[]
            ]);
        }else{
            return response([
                'code'=>'1010',
                'msg'=>'删除失败',
                'data'=>[]
            ]);
        }
    }
	
	/**
     * 获取订单学员
     */
    public function get_order_students($id){
        $order_students =Students::where('order_id',$id)
            ->with('get_nursery_user')
            ->get();
        if(!empty($order_students)){
            return response()->json([
                'code'=>'200',
                'msg'=>'msg',
                'data'=>$order_students
            ]);
        }else{
            return response()->json([
                'code'=>'200',
                'msg'=>'msg',
                'data'=>[]
            ]);
        }
    }
    /**
     * 获取名下未报名学员
     */
    public function get_not_order_students(Request $request){
        $order_id = $request->get('order_id',0);
        $apply_user = $request->get('apply_user',0);
        $contract_no = $request->get('contract_no',0);
        if(!$order_id || !$apply_user || !$contract_no){
            return response()->json([
                'code'=>1000,
                'msg' =>'缺少参数，参数错误'
            ]);
        }
        $order_students = Students::where('order_id',$order_id)->select('student_id')->get();
        $order_student_ids = array_column($order_students->toArray(),'student_id');
        $not_order_student = NurseryStudents::whereNotIn('id',$order_student_ids)
            ->where('apply_user',$apply_user)
            ->where('contract_no',$contract_no)
            ->select('id','student_name','student_phone')
            ->get();
        return response()->json([
            'code'=>200,
            'msg' =>'ok',
            'data'=>$not_order_student
        ]);
    }
	/**
     * 替换学员
     */
    public function update_order_students(Request $request){
        $order_id = $request->get('order_id',0);
        $old_student_id = $request->get('old_student_id',0);
        $new_student_id = $request->get('new_student_id',0);
        if(!$order_id || !$old_student_id || !$new_student_id){
            return response()->json([
                'code'=>1000,
                'msg' =>'缺少参数，参数错误'
            ]);
        }
        $order_student = Students::where('order_id',$order_id)
            ->where('student_id',$old_student_id)
            ->first();
        if(!empty($order_student)){
            if($order_student->is_paid !=1){
                return response()->json([
                    'code'=>0,
                    'msg' =>'未支付',
                ]);
            }
            //if($order_student->status !=1){
                //return response()->json([
                    //'code'=>0,
                    //'msg' =>'审核状态异常',
                //]);
            //}
            $order_student->student_id = $new_student_id;
            $order_student->status =0;
            if( $order_student->save() ){
				Entry::where('id',$order_id)->update(['status'=>3]);
                return response()->json([
                    'code'=>200,
                    'msg' =>'ok',
                    'data'=>$order_student
                ]);
            }
        }
        return response()->json([
            'code'=>0,
            'msg' =>'系统错误',
        ]);
    }
}
