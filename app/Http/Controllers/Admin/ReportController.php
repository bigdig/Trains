<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Entry;
use DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$lists = Entry::where('is_paid',1)
			->where(function($query) use ($request){
				if($request->has('contract_no')){
					$query->where('contract_no',$request->get('contract_no'));
				}
			})
            ->groupBy(['contract_no','park_name'])
            ->select(DB::raw('count(*) as trains_num'),'park_name','contract_no')
            ->paginate(10);
        return view('admin.report.index',['lists'=>$lists,'search'=>$request->toArray()]); 
    }
	public function entry_list($contract_no){
        $lists = Entry::where('is_paid',1)
            ->where('contract_no',$contract_no)
            ->with([
                'get_train',
                'get_students'=>function($query){
                    $query->with('get_nursery_user');
                }
            ])
            ->paginate(10);
        return view('admin.report.entry_list',['lists'=>$lists]);
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
