<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeachProfess;
use Validator;
use Config;
use Auth;

class TeachProfessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		if(Auth::user()->hasRole('admin')){
			$lists = TeachProfess::where('status','>=',0)->orderBy('id','desc')->paginate(10);
		}elseif(Auth::user()->hasRole('qnursery')){
			$lists = TeachProfess::where('status','>=',0)->where('profess_type',Config::get('category.qnursery'))->orderBy('id','desc')->paginate(10);
		}elseif(Auth::user()->hasRole('ynursery')){
			$lists = TeachProfess::where('status','>=',0)->where('profess_type',Config::get('category.ynursery'))->orderBy('id','desc')->paginate(10);
		}
        return view('admin.profess.index',['lists'=>$lists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.profess.create_edit',['edit'=>false]);
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
            'professional' => 'required|max:8|string',
            'desc'=>'required'
        ]);
        if(!$validator->fails()) {
			if( Auth::user()->hasRole('admin') ){
				$id = TeachProfess::create([
					'professional'=>$request->get('professional'),
					'desc'=>$request->get('desc'),
					'profess_type'=>$request->get('profess_type')
				])->id;
			}else{
				if(Auth::user()->hasRole('qnursery')){
					$request->client = Config::get('category.qnursery');
				}elseif(Auth::user()->hasRole('ynursery')){
					$request->client = Config::get('category.ynursery');
				}
				$id = TeachProfess::create([
					'professional'=>$request->get('professional'),
					'desc'=>$request->get('desc'),
					'profess_type'=>$request->client
				])->id;
			}
            if($id){
                return redirect()->route('profess.index');
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
        $info = TeachProfess::find($id);
        if($info){
            return view('admin.profess.create_edit',['edit'=>true,'info'=>$info]);
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
        $info = TeachProfess::find($id);
        $info->professional = $request->get('professional','');
        $info->desc         = $request->get('desc','');
        $info->status       = $request->get('status',1);
		if( Auth::user()->hasRole('admin') ){
			$info->profess_type = $request->get('profess_type');
		}elseif( Auth::user()->hasRole('qnursery') ){
			$info->profess_type = Config::get('category.qnursery');
		}elseif( Auth::user()->hasRole('ynursery') ){
			$info->profess_type = Config::get('category.ynursery');
		}
        if( $info->save() ){
            return redirect()->route('profess.index');
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
        $info = TeachProfess::find($id);
        if( !empty($info) ){
            $info->delete();
            return response()->json(['code' => 0, 'message' => 'success']);
        }
        return response()->json(['code' => 1, 'error' => '404未找到'], 422);
    }
}
