<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeachCourse;
use App\Models\Trains;
use Validator;
use Auth;
use Config;

class TeachCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(Auth::user()->hasRole('admin')){
			$lists = TeachCourse::where('status','>=',0)->with('get_train')->orderBy('id','desc')->paginate(10);
		}elseif( Auth::user()->hasRole('qnursery') ){
			$lists = TeachCourse::where('status','>=',0)->where('course_type',Config::get('category.qnursery'))->with('get_train')->orderBy('id','desc')->paginate(10);
		}elseif( Auth::user()->hasRole('ynursery') ){
			$lists = TeachCourse::where('status','>=',0)->where('course_type',Config::get('category.ynursery'))->with('get_train')->orderBy('id','desc')->paginate(10);
		}
        return view('admin.course.index',['lists'=>$lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $trains =Trains::where('status',2)->select('id','title')->get();
        return view('admin.course.create_edit',['trains'=>$trains,'edit'=>false]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'train_id'    => 'required|integer',
            'course_name' => 'required|max:8|string',
            'desc'        => 'required'
        ]);
        if(!$validator->fails()) {
			if( Auth::user()->hasRole('admin') ){
				$id = TeachCourse::create([
					'train_id'   =>$request->get('train_id'),
					'course_name'=>$request->get('course_name'),
					'desc'       =>$request->get('desc'),
					'course_type'=>$request->get('course_type'),
				])->id;
			}else{
				if(Auth::user()->hasRole('qnursery')){
					$request->client = Config::get('category.qnursery');
				}elseif(Auth::user()->hasRole('ynursery')){
					$request->client = Config::get('category.ynursery');
				}
				$id = TeachCourse::create([
					'train_id'   =>$request->get('train_id'),
					'course_name'=>$request->get('course_name'),
					'desc'       =>$request->get('desc'),
					'course_type'=>$request->client
				])->id;
			}
            if($id){
                return redirect()->route('course.index');
            }
        }
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
        $trains =Trains::where('status',2)->select('id','title')->get();
        $info = TeachCourse::where('status','>=',0)->with('get_train')->find($id);
        if($info){
            return view('admin.course.create_edit',['info'=>$info,'trains'=>$trains,'edit'=>true]);
        }
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
        $info = TeachCourse::find($id);
        $info->train_id     = $request->get('train_id','');
        $info->course_name  = $request->get('course_name','');
        $info->desc         = $request->get('desc','');
        $info->status       = $request->get('status',1);
		if( Auth::user()->hasRole('admin') ){
			$info->course_type = $request->get('course_type');
		}elseif( Auth::user()->hasRole('qnursery') ){
			$info->course_type = Config::get('category.qnursery');
		}elseif( Auth::user()->hasRole('ynursery') ){
			$info->course_type = Config::get('category.ynursery');
		}
        if( $info->save() ){
            return redirect()->route('course.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $info = TeachCourse::find($id);
        if( !empty($info) ){
            $info->delete();
            return response()->json(['code' => 0, 'message' => 'success']);
        }
        return response()->json(['code' => 1, 'error' => '404未找到'], 422);
    }
}
