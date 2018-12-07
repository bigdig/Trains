<?php

namespace App\Http\Controllers\Admin;

use App\Models\Chart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use GuzzleHttp\Client;

class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->getSchooleByProvinceNameFromBase($request);
        return view('admin.chart.index');
    }
    public function getWxDatas()
    {
        $chart=new Chart();
        $visitTrend=$chart->getVisitTrend();
       // $data='{"daily":{"list":[{"ref_date":"20181129","session_cnt":535,"visit_pv":2998,"visit_uv":167,"visit_uv_new":16,"stay_time_uv":204.7904,"stay_time_session":63.9252,"visit_depth":2.5551}]},"daily_all":{"list":[{"ref_date":"20181129","visit_total":5390,"share_pv":28,"share_uv":17}]},"weekly":{"list":[{"ref_date":"20181119-20181125","session_cnt":2470,"visit_pv":8913,"visit_uv":754,"visit_uv_new":248,"stay_time_uv":226.4748,"stay_time_session":69.1344,"visit_depth":1.9798}]},"monthly":{"list":[{"ref_date":"201810","session_cnt":10316,"visit_pv":32040,"visit_uv":1943,"visit_uv_new":775,"stay_time_uv":267.2872,"stay_time_session":50.3431,"visit_depth":1.6989}]}}';
        //$visitTrend=json_decode($data);

        return ['code'=>200,'msg'=>'获取成功','data'=>$visitTrend] ;
    }
    /**
     * 模糊搜索园所
     */
    function getSchool(Request $request){
        if($request->has('like')){
            $lists = DB::table('t_train_order') ->select(DB::raw('count(*) as t_count, park_name'))
               -> where('park_name','like','%'.$request->like.'%')
                ->groupBy('park_name')->orderBy('t_count','desc') ->get();
        }else{
            $lists = DB::table('t_train_order') ->select(DB::raw('count(*) as t_count, park_name'))
                ->groupBy('park_name') ->orderBy('t_count','desc') ->get();
        }
        return ['code'=>200,'msg'=>'获取成功','data'=>$lists->toArray()] ;
    }

    /**
     * 模糊搜索排名园所
     */
    function getTopSchool(Request $request){
        $top=$request->top?:5;
            $lists = DB::table('t_train_order') ->select(DB::raw('count(*) as t_count, park_name'))
                ->groupBy('park_name')->orderBy('t_count','desc')->take($top)->get();
        return ['code'=>200,'msg'=>'获取成功','data'=>$lists->toArray()] ;
    }

    function getPosition(Request $request){
        $map=[];
        $lists = $this->getPositionByTime();
        return ['code'=>200,'msg'=>'获取成功','data'=>$lists->toArray()] ;
    }

    function getPositionByTime($time=null,$map=null){
        $lists = DB::table('t_nursery_students')
            ->select(DB::raw('count(*) as t_count'),'t_nursery_students.student_position')
            ->leftJoin('t_order_students',  function ($join) use ($time){
               $join->on('t_nursery_students.id', '=', 't_order_students.student_id');
            })
            ->groupBy('t_nursery_students.student_position')
            ->orderBy('t_count','desc')
            ->get();
        return $lists;
    }

//        $listtotal = DB::table('t_train_order') ->select(DB::raw('sum(total_fee) as t_fee, train_id'))
//            ->groupBy('train_id')
//            ->where('is_paid',1)
//            ->leftJoin('t_trains', 'train_id', '=', 't_trains.id')
//            ->get();
//        $arr=[];
//        foreach ($listtotal as $n){
//            $arr[$n->train_id]=$n->t_fee;
//        }
//        foreach ($listpays as $n){
//            if(isset($arr[$n->train_id])){
//                $n->t_ree=$arr[$n->train_id];
//            }else{
//                $n->t_ree=0;
//            }
//        }
//

    //培训收入
    function getIncome(Request $request){
        $start=$request->start_time?:'2018-08-17';
        $end=$request->end_time?:'2018-08-24';
        $dates=$this->getTimeAarray(strtotime($start),strtotime($end));
        $map=[];
        $key=['1'=>'Mon','2'=>'Tue','3'=> 'Wed','4'=> 'Thu','5'=> 'Fri','6'=> 'Sat','7'=> 'Sun','0'=> 'Sun'];
        foreach ($dates as $da){
            $lists = DB::table('t_train_order')
                -> where('pay_time','like',''.$da.'%')->sum('total_fee');
            //$map[]=['key'=>$key[(Carbon::parse($da)->dayOfWeek)],'value'=>$lists];
            $map[]=['key'=>$da,'value'=>$lists];
        }
        $listpays = DB::table('t_train_order') ->select(DB::raw('sum(apply_num) as t_count,sum(total_fee) as t_fee,count(*) as t_num,  train_id'),'t_trains.title')
            -> whereBetween('t_train_order.created_at',['2017-08-17'." 00:00:00",$end." 00:00:00"])
            ->groupBy('train_id')
            ->leftJoin('t_trains', 't_train_order.train_id', '=', 't_trains.id')
            ->paginate($perPage = 8, $columns = ['*'], $pageName = 'page', $page = null);
        return ['code'=>200,'msg'=>'获取成功','data'=>['line'=>$map,'dat'=>$listpays->toArray()]] ;
    }


    //获取一段时间内的日期
    function getTimeAarray($stime,$etime){
        $datearr=array();
        while($stime <= $etime){
            $datearr[] = date('Y-m-d',$stime);//得到dataarr的日期数组。
            $stime=$stime + 3600*24;
        }
        return $datearr;
    }


    //通过省份名字模糊查询园所
    function getSchooleByProvinceNameFromBase(Request $request){
        $type=$request->catalog?:1;
        $data =[];
        $data['key'] = '27a15511082d11e6b23b00163e005ebf';
        $data['dt']  = 'json';
        $data['catalog']  = $type;
        $data['school.provinceName']= $request->provinceName?:'';
        $http = new Client();
        $response = $http->request('post','http://base.rybbaby.com/api/base/schoolList',['form_params'=>$data]);
        $data = json_decode((string)$response->getBody(), true);

        if($data['result']){
            $keys=[];
            foreach ($data['json'] as $nursy){
                $keys[$nursy['schCode']]=$nursy['schProvinceName'];
            }
            $lists = DB::table('t_train_order') ->select(DB::raw('count(*) as t_count, park_name,contract_no'))
               // -> whereIn('contract_no',$keys)
                ->groupBy('contract_no')->orderBy('t_count','desc') ->get();
            $re=[];
            foreach ($lists as $list){
                if(isset($keys[$list->contract_no])){
                    $re[$keys[$list->contract_no]][]=$list;
                }
            }
            return ['code'=>200,'msg'=>'获取成功','data'=>$re] ;
        }else{
            return ['code'=>200,'msg'=>'获取成功','data'=>null] ;
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
