<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class Chart extends Model
{
    //获取小程序日留存
    function getVisitTrend(){
        $visitTrend=[];

        $app=Factory::miniProgram(config('wechat.mini.1'));
        //日信息
        $now = (Carbon::yesterday())->toDateString();
        $dailySummary = $app->data_cube->dailyVisitTrend(str_replace('-', '', $now),str_replace('-', '', $now));
        $visitTrend['daily']=$dailySummary;


        $dailySummary_all = $app->data_cube->summaryTrend(str_replace('-', '', $now),str_replace('-', '', $now));
        $visitTrend['daily_all']=$dailySummary_all;

		//周信息
		$weekstart = Carbon::now()->subWeek()->startOfWeek()->toDateTimeString();
		$weekend =Carbon::now()->subWeek()->endOfWeek()->toDateTimeString();
		$weekSummary = $app->data_cube->weeklyVisitTrend($weekstart,$weekend);
		$visitTrend['weekly']=$dailySummary;
		//月记录
		$monthstart=(Carbon::now()->subMonth()->firstOfMonth())->toDateString();
        $monthend=(Carbon::now()->subMonth()->lastOfMonth())->toDateString();
        $monthly = $app->data_cube->monthlyVisitTrend($monthstart,$monthend);
		$visitTrend['monthly']=$dailySummary;

		//趋势


		return $visitTrend;
    }



    //
}
