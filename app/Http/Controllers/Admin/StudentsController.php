<?php

namespace App\Http\Controllers\Admin;

use function GuzzleHttp\default_ca_bundle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Students;
use App\Models\Entry;
use App\Models\Trains;
use App\Models\TrainCert;
use App\Repositories\Eloquent\StudentsRepositoryEloquent;
use Auth;
use App\Services\ImageUpload;
use App\Services\UmsApi;
use App\Repositories\Eloquent\ImageRepositoryEloquent;
use Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Config;

class StudentsController extends Controller
{
    protected $imageUpload;
    protected $umsApi;
    protected $studentsRepositoryEloquent;
    protected $imageRepositoryEloquent;

    public function __construct(StudentsRepositoryEloquent $studentsRepositoryEloquent,ImageUpload $imageUpload,ImageRepositoryEloquent $imageRepositoryEloquent,UmsApi $umsApi)
    {
        $this->imageUpload = $imageUpload;
		$this->umsApi = $umsApi;
        $this->imageRepositoryEloquent = $imageRepositoryEloquent;
        $this->studentsRepositoryEloquent = $studentsRepositoryEloquent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
		if(Auth::user()->hasRole('admin')){
			$request->client = 0;
		}elseif(Auth::user()->hasRole('qnursery')){
			$request->client = Config::get('category.qnursery');
		}elseif(Auth::user()->hasRole('ynursery')){
			$request->client = Config::get('category.ynursery');
		}
		if(!$request->client){
			$trains = Trains::where('status',2)->get();			
		}else{
			$trains = Trains::where('status',2)->where('train_category',$request->client)->get();	
		}
        $lists = Students::where(function($query) use($request){
            if($request->has('status')){
                $query->where('status',$request->get('status'));
            }
        })
            ->whereHas('get_order',function ($query) use ($request){
                if($request->has('contract_no')){
                        $query->where('contract_no',$request->get('contract_no'));
                }
                if($request->has('train_id')){
                    $query->where('train_id',$request->get('train_id'));
                }
                if($request->has('order_id')){
                    $query->where('id',$request->get('order_id'));
                }
				if($request->client){
					$query->where('order_source',$request->client );
				}
            })
            ->whereHas('get_nursery_user',function ($query) use ($request){
                if($request->has('student_phone')){
                        $query->where('student_phone',$request->get('student_phone'));
                }
            })
            ->with(['get_order'=>function($query){
                $query->with([
					'get_train'=>function($query){
						$query->with('get_charge');
					}
				]);
            },'get_nursery_user'])
            ->where('is_paid','1')
            ->orderBy('created_at','desc')
            ->paginate(10);
        //dd($lists->toArray());
        return view('admin.students.index',['trains'=>$trains,'lists'=>$lists,'search'=>$request->toArray()]);
    }
    /**
     * 审核
     */
    public function check(Request $request){
        $id = $request->get('order_students_id','');
        $status = $request->get('status','');
        $remark = $request->get('remark','');
        if($id){
            $order_students_info = Students::find($id);
            if($order_students_info){
                if($order_students_info->status !=0){
                    return response()->json(['code'=>'0','msg'=>'状态异常']);
                }
                if($order_students_info->is_paid !=1){
                    return response()->json(['code'=>'0','msg'=>'未支付']);
                }
                $order_students_info->status = $status;
                $order_students_info->remark = $remark;
                $order_students_info->check_recoder = Auth::user()->name;
                $order_students_info->check_time = date("Y-m-d H:i:s");
                $order_students_info->save();
				
				$entry = Entry::find($order_students_info->order_id);
                if($entry->apply_num ==1){
					if($status == '1'){
						$entry->status =6;
					}else{
						$entry->status =4;
					}
                }else{
                    //未通过人数
                    $w_count = Students::where('order_id',$entry->id)->where('status',2)->count();
					if($w_count){
						$entry->status =4;
					}else{
						$entry->status =6;
					}
                }
				$entry->remark = $remark;
				$entry->save();
				return ['code'=>200,'msg'=>'操作完成!'];
            }

        }
    }
    /**
     * 签到
     */
    public function sign(Request $request){
        $id = $request->get('order_students_id','');
        if($id){
            $order_students_info = Students::where('id',$id)
                ->where('status','1')
                ->where('is_paid',1)
                ->first();
            if($order_students_info){
                $order =Entry::where('id',$order_students_info->order_id)
                    ->where('status','>=',4)
                    ->first();
                if($order->is_paid !=1){
                    return ['code'=>0,'msg'=>'未支付'];
                }
				if($order->status ==4){
                    return ['code'=>0,'msg'=>'审核未通过'];
                }
                $order_students_info->status=3;
                $order_students_info->sign_time=date("Y-m-d H:i:s");
                $order_students_info->save();
                return ['code'=>200,'msg'=>'签到成功!'];
            }
            return ['code'=>0,'msg'=>'数据异常'];
        }
        return ['code'=>0,'msg'=>'数据异常'];
    }
	/**
     * 设置完成页面
     */
    public function go_done($order_students_id){
        $order_students_info = Students::where('id',$order_students_id)
            ->with('get_nursery_user')
            ->where('status',3)
            ->where('is_paid',1)
            ->first();
        if($order_students_info){
            $order_info =Entry::where('id',$order_students_info->order_id)
                ->with('get_train')
                ->where('status','>=',6)
                ->where('is_paid',1)
                ->first();
            return view('admin/students/go_done',['students_info'=>$order_students_info,'order_info'=>$order_info]);
        }
    }
	/**
     * 设置完成
	 * 发放证书
     */
    public function done(Request $request){
        $data = $request->toArray();
        if ($request->hasFile('cert_picture')) {
            $upload_status = $this->imageUpload->uploadImage($request->file('cert_picture'));
            $file_arr = $upload_status['filename'];
            // 保存到图片表
            $insert_id = $this->saveImageInfo($file_arr);
            $data['cert_picture'] = env('APP_URL','').'/'.$file_arr['small'];
        }else{
            $data['cert_picture'] = '';
		}
        if( TrainCert::create( $data ) ){
            $order_students_info = Students::where('id',$request->get('order_student_id') )
                ->where('status',3)
                ->where('is_paid',1)
                ->first();
            $order_students_info->status=4;
            $order_students_info->cert_time=date("Y-m-d");
            $order_students_info->save();

            Entry::where('id',$order_students_info->order_id)->update(['status'=>7]);
			/*
			$order_info = Entry::with('get_train')->find($order_students_info->order_id);
            //ums推送
            $this->umsApi->postTrain([
                'contractNum'=>$order_info->contract_no,
                'paramJson' =>[
                    'firstTrainBeginTime'=>$order_info->get_train->train_start,
                    'firstTrainEndTime'=>$order_info->get_train->train_end,
                    'firstCheckInTime'=>$order_students_info->sign_time,
                    'firstCertificateIssuingTime'=>date("Y-m-d H:i:s")
                ]
            ]);
			*/
            return redirect()->route('students.index',['order_id'=>$order_students_info->order_id]);
        }
    }
	public function test(Request $request){
		$result = $this->umsApi->postTrain([
                'contractNum'=>'Y0435',
                'paramJson' =>[
                    'firstTrainBeginTime'=>'2018-11-01 00:00:00',
                    'firstTrainEndTime'=>'2018-11-02 00:00:00',
                    'firstCheckInTime'=>'2018-11-01 00:00:00',
                    'firstCertificateIssuingTime'=>date("Y-m-d H:i:s")
                ]
            ]);
			return $result;
	}
    //设置完成
	public function over_done(Request $request){
        $id = $request->get('order_students_id','');
        if($id){
            $order_students_info = Students::where('id',$id)
                ->where('status',3)
                ->where('is_paid',1)
                ->first();
            if($order_students_info){
                $order =Entry::where('id',$order_students_info->order_id)
                    ->where('status',6)
                    ->first();
                if($order->is_paid !=1){
                    return ['code'=>0,'msg'=>'未支付'];
                }

                $order_students_info->status=4;
                $order_students_info->save();

                $order->status =7;
                $order->save();
                return ['code'=>200,'msg'=>'设置成功!'];
            }
            return ['code'=>0,'msg'=>'数据异常'];
        }
        return ['code'=>0,'msg'=>'数据异常'];
    }
    /**
     * 退训
     */
    public function refund(Request $request){
        $id = $request->get('rid','');
        $remark = $request->get('remark','');
        if($id){
            $order_students_info = Students::where('id',$id)
                ->where('is_paid',1)
                ->first();
            if($order_students_info){
                $order =Entry::where('id',$order_students_info->order_id)->first();
                if($order->is_paid !='1'){
                    return ['code'=>0,'msg'=>'未支付'];
                }
                if($order->status=='-1'){
                    return ['code'=>0,'msg'=>'已退训'];
                }
                if($order->from ==2){
					$order->status=1;
				}
                $order->remark = $remark;
                $order->save();

                $order_students_info->status='-1';
                $order_students_info->save();
                //返还库存 减销量
				if($order->from ==2){
					Trains::where('id',$order->train_id)->increment('pre_num');
					Trains::where('id',$order->train_id)->decrement('sale_num');
				}
                return ['code'=>200,'msg'=>'退训申请已提交!'];
            }
            return ['code'=>0,'msg'=>'数据异常'];
        }
        return ['code'=>0,'msg'=>'数据异常'];
    }
    /**
     * 学员信息
     */
    public function info(Request $request){
        $order_student_id = $request->input('order_students_id','');
        if($order_student_id){
            $info = Students::with('get_nursery_user')->find($order_student_id);
            return response()->json([
                'code'=>'200',
                'msg' =>'ok',
                'data'=>$info
            ]);
        }
    }
	/**
	* 证书预览
	*/
	public function cert(Request $request){
		$train_id = $request->input('train_id','');
        $student_id = $request->input('student_id','');
        $order_id = $request->input('order_id','');
        if($train_id && $student_id){
			$info = TrainCert::where('student_id',$student_id)
            ->where('train_id',$train_id)
            ->where('order_id',$order_id)
            ->first();
			return response()->json(['code'=>200,'msg'=>'ok','data'=>$info]);
		}
	}
	
    /**
     * 学员导出
     */
    public function export_data(Request $request){
		if(Auth::user()->hasRole('admin')){
			$request->client = 0;
		}elseif(Auth::user()->hasRole('qnursery')){
			$request->client = Config::get('category.qnursery');
		}elseif(Auth::user()->hasRole('ynursery')){
			$request->client = Config::get('category.ynursery');
		}
        $lists = Students::where(function($query) use($request){
            if($request->input('status') !==null ){
                $query->where('status',$request->get('status'));
            }
        })
            ->whereHas('get_order',function ($query) use ($request){
                if($request->input('contract_no')){
                    $query->where('contract_no',$request->get('contract_no'));
                }
                if($request->input('park_name')){
                    $query->where('park_name',$request->get('park_name'));
                }
				if($request->input('train_id')){
                    $query->where('train_id',$request->get('train_id'));
                }
				if($request->client){
					$query->where('order_source',$request->client);
				}
            })
            ->whereHas('get_nursery_user',function ($query) use ($request){
                if($request->input('student_phone')){
                    $query->where('student_phone',$request->get('student_phone'));
                }
            })
            ->with(['get_order'=>function($query){
                $query->with('get_train');
            },'get_nursery_user'])
            ->where('is_paid','1')
            ->orderBy('created_at','desc')
            ->get();
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('培训报名表');

        $worksheet->setCellValueByColumnAndRow(1, 1, '园所合同号');
        $worksheet->setCellValueByColumnAndRow(2, 1, '园所名称');
        $worksheet->setCellValueByColumnAndRow(3, 1, '培训主题');
        $worksheet->setCellValueByColumnAndRow(4, 1, '学员姓名');
        $worksheet->setCellValueByColumnAndRow(5, 1, '学员性别');
        $worksheet->setCellValueByColumnAndRow(6, 1, '学员手机号');
        $worksheet->setCellValueByColumnAndRow(7, 1, '学员岗位');
        $worksheet->setCellValueByColumnAndRow(8, 1, '身份证');
        $worksheet->setCellValueByColumnAndRow(9, 1, '学历');
        $worksheet->setCellValueByColumnAndRow(10, 1, '毕业院校');
        $worksheet->setCellValueByColumnAndRow(11, 1, '专业');
		
        $worksheet->setCellValueByColumnAndRow(12, 1, '签到日期');
        $worksheet->setCellValueByColumnAndRow(13, 1, '培训状态');

        for($i=0;$i<count($lists);$i++){
            $j =$i+2;
            $worksheet->setCellValueByColumnAndRow(1,$j,$lists[$i]->get_order->contract_no);
            $worksheet->setCellValueByColumnAndRow(2,$j,$lists[$i]->get_order->park_name);
            $worksheet->setCellValueByColumnAndRow(3,$j,$lists[$i]->get_order->get_train->title);
            $worksheet->setCellValueByColumnAndRow(4,$j,$lists[$i]->get_nursery_user->student_name);
            $worksheet->setCellValueByColumnAndRow(5,$j,$lists[$i]->get_nursery_user->student_sex==1?'男':'女');
            $worksheet->setCellValueByColumnAndRow(6,$j,$lists[$i]->get_nursery_user->student_phone);
            $worksheet->setCellValueByColumnAndRow(7,$j,$lists[$i]->get_nursery_user->student_position);
            $worksheet->setCellValueByColumnAndRow(8,$j,"'".$lists[$i]->get_nursery_user->idcard);
            $worksheet->setCellValueByColumnAndRow(9,$j,$lists[$i]->get_nursery_user->education);
            $worksheet->setCellValueByColumnAndRow(10,$j,$lists[$i]->get_nursery_user->school);
            $worksheet->setCellValueByColumnAndRow(11,$j,$lists[$i]->get_nursery_user->profession);
            $worksheet->setCellValueByColumnAndRow(12,$j,$lists[$i]->sign_time);
            $worksheet->setCellValueByColumnAndRow(13,$j,$this->text_status($lists[$i]->status) );
        }
        $filename = '培训学员表.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        echo $writer->save('php://output');die();

    }
    private function text_status($status){
        switch($status){
            case 0:
                return '未审核';
                break;
            case 1:
                return '审核通过未签到';
                break;
            case 2:
                return '审核未通过';
                break;
            case 3:
                return '已签到';
                break;
            case 4:
                return '已完成';
                break;
            default:
                return '已退训';
                break;
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
	/**
     * 保存图片信息到数据库
     * @param $file_arr array
     * @return string 插入ID
     * */
    protected function saveImageInfo($file_arr)
    {
        $insert_id = $this->imageRepositoryEloquent->saveImage($file_arr, Auth::user());
        return $insert_id;
    }
}
