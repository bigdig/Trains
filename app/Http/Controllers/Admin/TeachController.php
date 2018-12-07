<?php

namespace App\Http\Controllers\Admin;

use App\Models\Students;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NurseryStudents;
use App\Models\TrainCert;
use Auth;
use Config;

class TeachController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home(Request $request)
    {
        return view('admin.teach.index');
    }
    /**
     * 教师管理
     */
    public function index(Request $request){
		if(Auth::user()->hasRole('admin')){
			$request->client = 0;
		}elseif(Auth::user()->hasRole('qnursery')){
			$request->client = Config::get('category.qnursery');
		}elseif(Auth::user()->hasRole('ynursery')){
			$request->client = Config::get('category.ynursery');
		}
        $lists = NurseryStudents::where(function($query) use($request){
            if($request->get('keyword')){
                $query->where('contract_no',$request->get('keyword'));
                $query->orWhere('student_phone',$request->get('keyword'));
            }
			if($request->client){
				$query->where('student_type',$request->client);
			}
        })->paginate(10);
        return view('admin.teach.index',['lists'=>$lists,'search'=>$request->toArray()]);
    }
    /**
     * 培训记录
     */
    public function get_train_record($student_id){
        $lists = TrainCert::where('student_id',$student_id)->get();
        return view('admin.teach.train_record',['lists'=>$lists]);
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
