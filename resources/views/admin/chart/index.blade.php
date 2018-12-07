<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/vendor/editormd/css/editormd.preview.css">
    <style>
        * {
            font-family: 'PingFang-SC-Medium';
        }
        .body {
            background-color: #edeff1;
        }

        .nopadding {
            padding: 0;
        }

        .peopleDataBox {
            padding: 0 10px 0 0;
        }

        .peopleDataDetail {
            height: 210px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        .peopleDataDetail p {
            margin-bottom: 0;
            text-align: center;
        }

        .peopleDataIcon {
            width: 36px;
            height: 36px;
            background: #ccc;
            margin: 28px auto 6px;
            border-radius: 50%;
        }

        .peopleDataIcon > img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .peopleDataTitle {
            height: 12px;
            line-height: 12px;
            font-size: 12px;
            color: #999;
        }

        .peopleDataNumber {
            height: 24px;
            line-height: 24px;
            font-size: 24px;
            color: #555;
            margin-bottom: 10px !important;
            font-family: 'PingFang-SC-Heavy' !important;
            font-weight: bolder;
        }

        .peopleData_span {
            color: #888;
        }

        .peopleData_red {
            color: #fe0000;
        }

        .peopleData_green {
            color: #00a21e;
        }

        .peopleData_red,
        .peopleData_green {
            display: inline-block;
            width: 90px;
            text-align: right;
        }

        .peopleDataDay,
        .peopleDataWeek,
        .peopleDataMonth {
            margin-bottom: 5px !important;
        }

        .trainSituationBox {
            height: 300px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .trainSituationSearchBox {
            overflow: hidden;
        }

        .trainSituationSearch_span,
        .trainSituationSearch_input,
        .trainSituationSearch_button {
            float: left;
        }

        .trainSituationSearch_span {
            height: 30px;
            line-height: 30px;
            color: #555;
            font-size: 14px;
            margin-right: 36px;
        }

        .trainSituationSearch_input {
            margin-right: 12px;
        }

        .trainSituationSearch_input input {
            width: 224px;
            height: 30px;
            border: none;
            border: 1px solid #cdcdcd;
            border-radius: 4px;
        }

        .trainSituationSearch_button button {
            width: 54px;
            height: 30px;
            background-color: #0074ff;
            font-family: 'PingFang-SC-Regular';
            font-size: 14px;
            color: #fff;
            border-radius: 4px;
            border: none;
            outline: none;
        }
        .schoollistck{
            width: 338px;
            height: 206px;
            border: 1px solid #d2d2d2;
            border-radius: 6px;
            margin: 26px 0 0 45px;
            position: relative;
            padding: 14px 35px;
            overflow-y: auto;
        }

        .trainSituationSearchResultsBox {
            /* clear: both; */
            width: 280px;
            height: 206px;
            border: 1px solid #d2d2d2;
            border-radius: 6px;
            margin: 26px 0 0 5px;
            position: relative;
            padding: 14px 5px;
            overflow-y: auto;
        }

        .trainSituationSearchResults_top {
            width: 20px;
            height: 20px;
            position: absolute;
            right: 58px;
            top: -20px;
            z-index: 2;
        }

        .trainSituationSearchResults_top_a1,
        .trainSituationSearchResults_top_a2 {
            width: 0;
            height: 0;
            display: block;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 5;
            border-top: 10px transparent dashed;
            border-left: 10px transparent dashed;
            border-right: 10px transparent dashed;
            border-bottom: 10px white solid;
            overflow: hidden;
        }

        .trainSituationSearchResults_top_a1 {
            border-bottom: 10px #d2d2d2 solid;
        }

        .trainSituationSearchResults_top_a2 {
            top: 1px;
            border-bottom: 10px white solid;
        }

        .trainSituationSearchResults {
            height: 26px;
            line-height: 26px;
            background-color: #eaeaea;
            border-radius: 13px;
            margin-bottom: 10px;
            padding-left: 24px;
        }

        .trainSituationSearchResultsClose {
            float: right;
            margin-right: 10px;
            cursor: pointer;
        }
        .regionTrainBox_left{
            position:relative
        }
        .regionTrainBox_left,
        .regionTrainBox_right {
            height: 514px;
        }

        .regionTrainBox_left {
            padding: 0 24px;
        }

        .regionTrainBox_left,
        .regionTrainBox_right_top,
        .regionTrainBox_right_right,
        .trainIncome {
            border-radius: 10px;
            background-color: #fff;
        }

        .regionTrainBox_right_top,
        .regionTrainBox_right_right {
            height: 252px;
            padding: 0 24px;
        }

        .regionTrainBox_right_top {
            margin-bottom: 10px;
        }

        .trainIncome {
            padding: 0 22px;
            overflow: hidden;
        }

        .regionTrainBox_title,
        .trainIncome_title {
            height: 50px;
            line-height: 50px;
            border-bottom: 2px solid #f4f4f4;
            color: #555;
        }

        .regionTrainBox_Btn {
            float: right;
            margin-top: 12px;
            width: 53px;
            height: 28px;
            line-height: 28px;
            padding: 0;
            background-color: #0074ff;
            font-family: 'PingFang-SC-Regular';
            font-size: 14px;
            color: #fff;
            border-radius: 4px;
            border: none;
            outline: none;
        }

        .table {
            margin-bottom: 20px;
        }

        .regionTrainBox_position {
            position: absolute;
            top: 12px;
            right: 80px;
            width: 150px;
            height: 200px;
            background: #fff;
            z-index: 999;
            border: 1px solid #edeff1;
            border-radius: 4px;
            display: none;
            overflow-y: auto;
        }
        .regionTrainBox_position>div {
            line-height: 24px;
            padding: 0 10px;
        }
        .regionTrainBox_position>div>input[type='checkbox'] {
            margin-right: 10px;
        }

        .pagination {
            position: relative;
            top: 0;
            left: 50%;
        }

        .pagination li {
            cursor: pointer;
        }
        #top5mapInfo{
            position:absolute;
            top:0px;
            left:22px;
            height: 200px;
            width: 320px;

        }
        .span_trainSSR{
            width: 13em;
            height:16px;
            line-height: 1.6rem;
            display: inline-block;
            overflow: hidden;
            margin-top: 5px;
        }
        .span_time{
            width: 120px;
            height: 30px;
            border: none;
            border: 1px solid #cdcdcd;
            border-radius: 4px;
            text-align: center;
        }
        .btn_normal{
            display: inline-block;
            width: 54px;
            height: 30px;
            line-height: 30px;
            background-color: #0074ff;
            font-family: 'PingFang-SC-Regular';
            font-size: 14px;
            color: #fff;
            border-radius: 4px;
            border: none;
            outline: none;
        }
        .trainIncome_span{
            margin-right: 36px;
        }
        .h_map_tip{
            text-align: center;
        }
    </style>
</head>

<body class="body">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12" style="padding:0 10px 0 20px;margin: 20px 0 10px 0;">
            <div class="col-sm-3 col-md-3 peopleDataBox">
                <div class="peopleDataDetail">
                    <div class="peopleDataIcon">
                        <img src="{{asset('assets/admin/chart/imgs/icon_view.png')}}">
                    </div>
                    <p class="peopleDataTitle">昨日新用户</p>
                    <p class="peopleDataNumber dayD"></p>
                    <p class="peopleDataDay"><span class="peopleData_span">访问人数</span><span class="peopleData_red dayA"></span>
                    </p>
                    <p class="peopleDataWeek"><span class="peopleData_span">访问次数</span><span class="peopleData_red dayB"></span>
                    </p>
                    <p class="peopleDataMonth"><span class="peopleData_span">打开次数</span><span class="peopleData_green dayC"></span>
                    </p>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 peopleDataBox">
                <div class="peopleDataDetail">
                    <div class="peopleDataIcon">
                        <img src="{{asset('assets/admin/chart/imgs/icon_viewnew.png')}}">
                    </div>
                    <p class="peopleDataTitle">上周新用户</p>
                    <p class="peopleDataNumber weekD"></p>
                    <p class="peopleDataDay"><span class="peopleData_span">访问人数</span><span class="peopleData_red weekA"></span>
                    </p>
                    <p class="peopleDataWeek"><span class="peopleData_span">访问次数</span><span class="peopleData_red weekB"></span>
                    </p>
                    <p class="peopleDataMonth"><span class="peopleData_span">打开次数</span><span class="peopleData_green weekC"></span>
                    </p>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 peopleDataBox">
                <div class="peopleDataDetail">
                    <div class="peopleDataIcon">
                        <img src="{{asset('assets/admin/chart/imgs/icon_number.png')}}">
                    </div>
                    <p class="peopleDataTitle">上个月新用户</p>
                    <p class="peopleDataNumber monthD"></p>
                    <p class="peopleDataDay"><span class="peopleData_span">访问人数</span><span class="peopleData_red monthA"></span>
                    </p>
                    <p class="peopleDataWeek"><span class="peopleData_span">访问次数</span><span class="peopleData_red monthB"></span>
                    </p>
                    <p class="peopleDataMonth"><span class="peopleData_span">打开次数</span><span class="peopleData_green monthC"></span>
                    </p>
                </div>
            </div>
            <div class="col-sm-3 col-md-3 peopleDataBox">
                <div class="peopleDataDetail">
                    <div class="peopleDataIcon">
                        <img src="{{asset('assets/admin/chart/imgs/icon_numbernew.png')}}">
                    </div>
                    <p class="peopleDataTitle">用户数</p>
                    <p class="peopleDataNumber allD"></p>
                    <p class="peopleDataDay"><span class="peopleData_span">累计用户数</span><span class="peopleData_red allA"></span>
                    </p>
                    <p class="peopleDataWeek"><span class="peopleData_span">转发人数</span><span class="peopleData_red allB"></span>
                    </p>
                    <p class="peopleDataMonth"><span class="peopleData_span">转发次数</span><span class="peopleData_green allC"></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12" style="padding: 0 20px;margin-bottom: 10px;">
            <div class="trainSituationBox">
                <div class="col-sm-6 col-md-6 nopadding">
                    <div class="trainSituationSearchBox">
                        <div class="trainSituationSearch_span">园所培训情况</div>
                        <div class="trainSituationSearch_input">
                            <input type="text" id="like" name="like" value="北京">
                        </div>
                        <div class="trainSituationSearch_button">
                            <button class="schoolseach">搜索</button>
                        </div>
                    </div>
                    <div class="col-sm-6 trainSituationSearchResultsBox schoollistck"></div>
                    <div class="col-sm-6 trainSituationSearchResultsBox">
                        <b class="trainSituationSearchResults_top">
                            <i class="trainSituationSearchResults_top_a1"></i>
                            <i class="trainSituationSearchResults_top_a2"></i>
                        </b>

                        <div class="schoolelist">

                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 nopadding" style="height: 100%">
                    <div id="kindergarten" style="height: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12" style="padding: 0 20px;margin-bottom: 10px;">
            <div class="col-sm-7 col-md-7" style="padding: 0 10px 0 0;">
                <div class="regionTrainBox_left">
                    <div class="regionTrainBox_title">区域培训情况</div>
                    <div id="china-map" style="height: 90%"></div>
                    <div id="top5mapInfo"></div>
                </div>
            </div>
            <div class="col-sm-5 col-md-5 nopadding">
                <div class="regionTrainBox_right">
                    <div class="regionTrainBox_right_top">
                        <div class="regionTrainBox_title">
                            参训次数
                            <button class="regionTrainBox_Btn">筛选</button>
                            <div class="regionTrainBox_position" id="regionTrainBox_position">

                            </div>
                        </div>
                        <div id="trainNumber" style="height: 80%"></div>
                    </div>
                    <div class="regionTrainBox_right_right">
                        <div class="regionTrainBox_title">园所参训情况</div>
                        <div id="kindergartenTrain" style="height: 80%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12" style="padding: 0 20px;margin-bottom: 20px;">
            <div class="trainIncome">
                <div class="trainIncome_title">

                    <span class="trainIncome_span">培训收入</span>
                    <input type="text" id="start_time" name="start_time" value="" placeholder="开始时间" class="span_time"
                           readonly>
                    -
                    <input type="text" id="end_time" name="end_time" value="" placeholder="结束时间" class="span_time"
                           readonly>
                    <button class="btn_normal  trainIncomeseach">搜索</button>
                </div>
                <div id="trainIncome" style="height: 280px"></div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>培训名称</th>
                        <th>报名人数</th>
                        <th>实际参训人数</th>
                        <th>培训金额（元 ）</th>
                    </tr>
                    </thead>
                    <tbody class="tbodylist">

                    </tbody>
                </table>
                <nav aria-label="Page navigation">
                    <ul class="pagination">

                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
<script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
<script src="{{asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script>
    //日历控件初始化
    !function(a){a.fn.datepicker.dates["zh-CN"]={days:["星期日","星期一","星期二","星期三","星期四","星期五","星期六"],daysShort:["周日","周一","周二","周三","周四","周五","周六"],daysMin:["日","一","二","三","四","五","六"],months:["一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月"],monthsShort:["1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月"],today:"今日",clear:"清除",format:"yyyy年mm月dd日",titleFormat:"yyyy年mm月",weekStart:1}}(jQuery);
    $("#start_time,#end_time").datepicker({
        autoclose: true,
        todayHighlight: true,
        language:"zh-CN",
        format:"yyyy-mm-dd"
    });

    function initWxData() {
        //$.get("{{route('chart.getWxDatas')}}" , function (data) {
        $.get("http://192.168.2.59:8081/test/ajax/getWxDatas", function (data) {
            if (data.code == 200) {
                var daily = data.data.daily;
                var weekly = data.data.weekly;
                var monthly = data.data.monthly;
                var daily_all = data.data.daily_all;


                $('.dayA').html(daily.list[0].visit_uv);
                $('.dayB').html(daily.list[0].visit_pv);
                $('.dayC').html(daily.list[0].session_cnt);
                $('.dayD').html(daily.list[0].visit_uv_new);

                $('.weekA').html(weekly.list[0].visit_uv);
                $('.weekB').html(weekly.list[0].visit_pv);
                $('.weekC').html(weekly.list[0].session_cnt);
                $('.weekD').html(weekly.list[0].visit_uv_new);

                $('.monthA').html(monthly.list[0].visit_uv);
                $('.monthB').html(monthly.list[0].visit_pv);
                $('.monthC').html(monthly.list[0].session_cnt);
                $('.monthD').html(monthly.list[0].visit_uv_new);

                $('.allA').html(daily_all.list[0].visit_total);
                $('.allB').html(daily_all.list[0].share_uv);
                $('.allC').html(daily_all.list[0].share_pv);
                $('.allD').html(daily_all.list[0].visit_total);


            }
        });
    }
    initWxData();


    //保存初始化数据
    var series_data = {
        kindergarten: []
    }
    $('.schoolseach').click(function () {
        initKindergartenOption();
    })
    setTimeout(function(){  //使用  setTimeout（）方法设定定时2000毫秒
        initKindergartenOption();
    },200);
    //initKindergartenOption();

    function initKindergartenOption() {
        // $.get("{{route('chart.getSchool')}}" + "?like=" + $('#like').val(), function (data) {
        $.get("http://192.168.2.59:8081/test/ajax/getSchool" + "?like=" + $('#like').val(), function (data) {
            if (data.code == 200) {
                data = data.data;
                initKindergartenEle(data, 5); //默认前5个选中
            }
        }, "json")
    }
    /**
     * ck_num 默认前几个选中
     */
    function initKindergartenEle(data, ck_num) {
        var arr = [];
        $.each(data, function (i, t) {
            arr.push($.extend({}, t, {
                checked: i < 5
            }))
        })
        window.series_data.kindergarten = arr;
        initKindergartenCKlist(); //初始化CheckBox控件
        initKindergartenPlist(); //初始化p标签控件
        initKindergartenEchart(); //条形图
    }

    function initKindergartenCKlist() {
        var arr = [];
        $.each(series_data.kindergarten, function (i, t) {
            arr.push('<p>')
            if (t.checked) {
                arr.push('<input name="ck_kindergarten" type="checkbox"  checked="true" value="' + t.park_name +
                    '" />');
            } else {
                arr.push('<input name="ck_kindergarten" type="checkbox"  value="' + t.park_name + '" />');
            }
            arr.push(t.park_name);
            arr.push('</p>')
        })
        $('.schoollistck').html(arr.join(''));
    }
    $(".schoollistck").on("click", "[name=ck_kindergarten]", function (e) { //选择多选右侧变动
        var name = $(this).val();
        var flag = $(this).prop("checked");
        e.stopPropagation();
        $.each(series_data.kindergarten, function (i, t) {
            if (t.park_name == name)
                t.checked = flag;
        })
        initKindergartenPlist(); //初始化p标签控件
        initKindergartenEchart(); //条形图
    })

    function initKindergartenPlist() {
        var arr = [];
        $.each(series_data.kindergarten, function (i, t) {
            if (t.checked) {
                arr.push('<p class="trainSituationSearchResults" title="' + t.park_name + '">');
                arr.push('<span class="span_trainSSR">' + t.park_name + '</span>');
                arr.push('<span class="trainSituationSearchResultsClose" title="关闭">×</span></p>')
            }
        })
        $('.schoolelist').html(arr.join(''));
    }
    $(".schoolelist").on("click", ".trainSituationSearchResultsClose", function (e) { //点X关闭
        var name = $(this).parent().remove().attr("title");
        e.stopPropagation();
        $.each(series_data.kindergarten, function (i, t) {
            if (t.park_name == name)
                t.checked = false;
        })
        initKindergartenCKlist(); //初始化ck
        initKindergartenEchart(); //条形图
    })
    //设置条形图
    function initKindergartenEchart() {
        var yAxis = [];
        var series = [];
        $.each(series_data.kindergarten, function (i, t) {
            if (t.checked) {
                yAxis.push(t.park_name);
                series.push(t.t_count);
            }
        });
        setKindergartenOption(yAxis, series);
    }


    $(".regionTrainBox_Btn").click(function () {
        $(".regionTrainBox_position")[$(".regionTrainBox_position").is(':hidden') ? "show" : "hide"]();
    })
    var kindergarten = document.getElementById("kindergarten");
    var KindergartenMyChart = echarts.init(kindergarten);
    var kindergartenApp = {};
    kindergartenOption = null;
    kindergartenApp.title = '园所培训情况 - 条形图';

    function setKindergartenOption(key, value) {
        kindergartenOption = {
            title: {
                text: '园所培训情况'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data: ['2018年']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: {
                type: 'value',
                boundaryGap: [0, 1],
                max: "dataMax",
                splitNumber: 1 //间隔
                ,show: false,
                "axisLine": { //y轴
                    "show": false

                },
                "axisTick": { //y轴刻度线
                    "show": false
                },
                "splitLine": { //网格线
                    "show": false
                },
            },
            yAxis: {
                type: 'category',
                data: key
                ,"axisLine": { //y轴
                    "show": false

                },
                "axisTick": { //y轴刻度线
                    "show": false
                },
                "splitLine": { //网格线
                    "show": false
                },
            },
            series: [{
                name: '2018年',
                type: 'bar',
                barMaxWidth: 20, //柱图宽度
                data: value,
                itemStyle: {
                    color: '#000',
                    barBorderRadius: 7,
                    normal: {
                        barBorderRadius: 12,
                        label: { // 柱子-文字
                            show: true,
                            position: "right",
                            textStyle: {
                                fontSize: '14',
                            }
                        },
                        color: function (params) {
                            return ["#ff9900", "#ff6666", "#9966cc", "#99ccff", "#33cccc"][params.dataIndex] ||
                                "#f7c498"
                        }
                    }
                }
            }]
        };
        if (kindergartenOption && typeof kindergartenOption === "object") {
            KindergartenMyChart.setOption(kindergartenOption, true);
        }
    }

    // 岗位筛选条形图
    setTimeout(function(){  //使用  setTimeout（）方法设定定时2000毫秒
        initTrainNumberOption();
    },500);

    function initTrainNumberOption() {
        // $.get("{{route('chart.getPosition')}}",function (data) {
        $.get("http://192.168.2.59:8081/test/ajax/getPosition", function (data) {
            if (data.code == 200) {
                data = data.data;
                var map = new Array();
                var yAxis = new Array()
                var series = new Array()
                for (x in data) {
                    if (x < 10) {
                        yAxis.push(data[x].student_position);
                        series.push(data[x].t_count);
                        $("#regionTrainBox_position").append(
                            "<div><input name='positionbox' class='positionbox' type='checkbox' value='" +
                            data[x].student_position + "' checked='checked'>" + data[x].student_position +
                            "</div>")
                    } else {
                        $("#regionTrainBox_position").append(
                            "<div><input name='positionbox' class='positionbox' type='checkbox' value='" +
                            data[x].student_position + "'>" + data[x].student_position + "</div>")
                    }
                    map[data[x].student_position] = data[x].t_count;
                }
                $('.positionbox').change(function () {
                    yAxis = [];
                    series = [];
                    $("input:checkbox[name='positionbox']:checked").each(function () { // 遍历name=test的多选框
                        yAxis.push($(this).val());
                        series.push(map[$(this).val()]);
                    });
                    setTrainNumberOption(yAxis, series);
                });
                //设置条形图
                setTrainNumberOption(yAxis, series);
            }
        });
    }
    var trainNumber = document.getElementById("trainNumber");
    var trainNumberMyChart = echarts.init(trainNumber);
    var trainNumberApp = {};
    trainNumberOption = null;
    //参训次数
    function setTrainNumberOption(key, value) {
        trainNumberOption = {
            tooltip: {
                trigger: 'item',
                formatter: "{b}: {c}"
            },
            xAxis: {
                type: 'category',
                //data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                data: key,
                axisLabel: {
                    interval: 0, //横轴信息全部显示
                    rotate: -30, //-30度角倾斜显示
                }

            },
            yAxis: {
                type: 'value'
            },
            series: [{
                data: value,
                type: 'bar',
                barWidth: 30 //柱图宽度
                ,
                itemStyle: {
                    normal: {
                        color: "#99ccff"
                    },
                    emphasis: {
                        //柱形图圆角，初始化效果
                        barBorderRadius: [50, 50, 10, 10],
                        barBorderRadius: 30
                    },
                }
            }]

        };
        if (trainNumberOption && typeof trainNumberOption === "object") {
            trainNumberMyChart.setOption(trainNumberOption, true);
        }
    }

    // top 5 培训最多五家园所
    function initKindergarten() {
        //$.get("{{route('chart.getTopSchool')}}" , function (data) {
        $.get("http://192.168.2.59:8081/test/ajax/getTopSchool", function (data) {
            if (data.code == 200) {
                data = data.data;
                var list = '';
                var yAxis = new Array()
                var series = new Array()
                for (x in data) {
                    yAxis.push(data[x].park_name);
                    series.push({
                        'name': data[x].park_name,
                        'value': data[x].t_count
                    });
                }
                //设置条形图
                setKindergartenTrainOption(yAxis, series);
            }
        });
    }
    setTimeout(function(){  //使用  setTimeout（）方法设定定时2000毫秒
        initKindergarten();
    },750);

    var kindergartenTrain = document.getElementById("kindergartenTrain");
    var kindergartenTrainMyChart = echarts.init(kindergartenTrain);
    var kindergartenTrainApp = {};
    kindergartenTrainOption = null;
    kindergartenTrainApp.title = '环形图';
    //园所参训情况
    function setKindergartenTrainOption(key, value) {
        kindergartenTrainOption = {

            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'left',
                data: key
            },
            series: [{
                name: '培训占比',
                type: 'pie',
                center: ["65%",
                    "50%"
                ],
                radius: ['50%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    normal: {
                        show: false,
                        position: 'center'
                    },
                    emphasis: {
                        show: false,
                        textStyle: {
                            fontSize: '15',
                            fontWeight: 'bold'
                        }
                    }
                },
                labelLine: {
                    normal: {
                        show: false
                    }
                },
                data: value,
                normal: {
                    barBorderRadius: 7,
                    label: { // 柱子-文字
                        show: true,
                        position: "right",
                        textStyle: {
                            fontSize: '14',
                        }
                    }

                },
                color: function (params) {
                    return ["#ff9900", "#ff6666", "#9966cc", "#99ccff", "#33cccc"][params.dataIndex] ||
                        '#f7c498'
                }
            }]
        };

        if (kindergartenTrainOption && typeof kindergartenTrainOption === "object") {
            kindergartenTrainMyChart.setOption(kindergartenTrainOption, true);
        }
    }



    //培训收入
    setTimeout(function(){  //使用  setTimeout（）方法设定定时2000毫秒
        initTrainIncomeOption(1);
    },1000);

    function initTrainIncomeOption(page) {
        var param = {
            start_time: $("#start_time").val() || '',
            end_time: $("#end_time").val() || "",
            page: page
        }
        // $.get("{{route('chart.getIncome')}}"+"?page="+page, function (data) {
        $.get("http://192.168.2.59:8081/test/ajax/getIncome?", param, function (data) {
            if (data.code == 200) {
                var datas = data.data.line;
                var yAxis = new Array()
                var series = new Array()
                for (x in datas) {
                    yAxis.push(datas[x].key);
                    series.push(datas[x].value);
                }
                //设置条形图
                setTrainIncomeOption(yAxis, series);
                var dat = data.data.dat.data;
                var html = '';
                for (var i = 1; i <= data.data.dat.last_page; i++) {
                    html += '<li><a onclick="initTrainIncomeOption(' + i + ')">' + i + '</a></li>'
                }
                $(".pagination").html(html);
                var str = "";
                for (x in dat) {
                    str += " <tr> <td>" + dat[x].title + "</td> <td>" + dat[x].t_count +
                        "</td> <td>" + dat[x].t_num + "</td><td>" + dat[x].t_fee + "</td></tr>"

                }
                $('.tbodylist').html(str);
            }
        });
    }
    // trainIncome
    var trainIncome = document.getElementById("trainIncome");
    var trainIncomeMyChart = echarts.init(trainIncome);
    var trainIncomeApp = {};
    trainIncomeOption = null;

    function setTrainIncomeOption(key, value) {
        var amount = 0;
        $.each(value,function(i,t){
            amount += t/1;
        })
        trainIncomeOption = {
            title: {
                text: '总计：'+amount +'元'
                ,left:0
                ,textStyle:{
                    color:"#999"
                }
            },
            grid:{
                x:50
                ,x2:20
            },
            xAxis: {
                type: 'category',
                // data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                data: key
            },
            yAxis: {
                type: 'value'
            },
            calculable: false,
            series: [{
                data: value,
                type: 'line',
                smooth: true,
                itemStyle: {
                    color: "#33cccc"
                }
            }],
            tooltip: {
                trigger: 'item',
                formatter: value
            }
        };;
        if (trainIncomeOption && typeof trainIncomeOption === "object") {
            trainIncomeMyChart.setOption(trainIncomeOption, true);
        }
    }

    /**
     * 中国地图
     */
    var myChart = echarts.init(document.getElementById('china-map'));
    var option = {
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                //定义一个res变量来保存最终返回的字符结果,并且先把地区名称放到里面
                var res = params.name?'<h4 class="h_map_tip">' + params.name +'('+params.value+')'+ '</h4>':'';
                var arr_show = [];
                var arr = params.data && params.data.info || [];
                $.each(arr, function (i, t) {
                    arr_show.push(t.park_name + ":" + t.t_count)
                })
                res += arr_show.join('<br>') || "无数据"
                return res;
            }
        },
        dataRange: {
            orient: 'horizontal',
            min: 0,
            max: 0,
            x:"right",
            text:['高','低'], // 文本，默认为数值文本
            color:['#ff6666','#FAFF00'],
            splitNumber: 0//线性渐变
        },
        series: [{
            type: 'map',
            mapType: 'china',
            label: {
                normal: {
                    show: false, //显示省份标签
                    textStyle: {
                        color: "#555" //"#c71585"
                    } //省份标签字体颜色
                },
                emphasis: { //对应的鼠标悬浮效果
                    show: true,
                    textStyle: {
                        color: "#800080"
                    }
                }
            },
            itemStyle: {
                normal: {
                    borderWidth: .5, //区域边框宽度
                    borderColor: "#fff", //'#009fe8', //区域边框颜色
                    areaColor: "#99ccff", // "#ffefd5", //区域颜色
                },
                emphasis: {
                    borderWidth: .5,
                    borderColor: '#4b0082',
                    areaColor: "#ffdead",
                }
            },
            data: []
        }]
    };


    function initChinaMapData(cb) {
        $.get("http://192.168.2.59:8081/test/ajax/getSchooleByProvinceNameFromBase", function (data) {
            if (data.code == 200) {
                var datas = data.data;
                cb(datas);
            }
        });

    }
    initChinaMapData(function (data) {
        var arr = [];
        $.each(data, function (i, t) {
            var tempName = i;
            var count = 0;
            if (["内蒙古自治区", "黑龙江省"].indexOf(i) > -1) {
                tempName = i.substr(0, 3)
            } else {
                tempName = i.substr(0, 2);
            }
            $.each(t, function (i, t) {
                count += t.t_count || 0;
            })
            arr.push($.extend({}, {
                name: tempName,
                selected: false,
                info: t,
                value:count
            }))
        })
        arr = arr.sort(sortBy("value"));////根据园所数值排序后的省
        option.dataRange.max = arr[0]&&arr[0]["value"]||100;//默认100为最高，0为最低
        option.series[0].data = arr;
        myChart.setOption(option);
        initChinaTop5(arr); //加载top5省的数据
    })

    function initChinaTop5(_arr) {
        var newArr = _arr;
        var arr = [];
        $.each(newArr, function (i, t) {
            if (i < 5) {
                arr.unshift(t)
            }
        })
        var yAxis = [];
        var series = [];
        $.each(arr, function (i, t) {
            yAxis.push(t.name);
            series.push(t.value);
        })
        initChinaTopEle(yAxis, series);
    }

    function initChinaTopEle(yAxis, series) {
        var option = {
            calculable: false,
            xAxis: [{
                type: 'value',
                boundaryGap: [0, 1],
                show: false,
                "axisLine": { //y轴
                    "show": false

                },
                "axisTick": { //y轴刻度线
                    "show": false
                },
                "splitLine": { //网格线
                    "show": false
                },
                max: "dataMax"
            }],
            yAxis: [{
                type: 'category',
                data: yAxis,
                "axisLine": { //y轴
                    "show": false

                },
                "axisTick": { //y轴刻度线
                    "show": false
                },
                "splitLine": { //网格线
                    "show": false
                }
            }],
            series: [{
                type: 'bar',
                data: series,
                itemStyle: {
                    color: '#000',
                    barBorderRadius: 7,
                    normal: {
                        barBorderRadius: 7,
                        label: { // 柱子-文字
                            show: true,
                            position: "right",
                            textStyle: {
                                fontSize: '14',
                            }
                        },
                        color: function (params) {
                            return ["#ff9900", "#ff6666", "#9966cc", "#99ccff", "#33cccc"][params.dataIndex]
                        }
                    }
                }
            }]
        };
        var myChart = echarts.init(document.getElementById('top5mapInfo'));
        myChart.setOption(option);
    }

    //数组排序
    function sortBy(field) {
        return function (a, b) {
            return b[field] - a[field];
        }
    }
    // myChart.setOption(option);
    $(".trainIncomeseach").on("click", function () {
        initTrainIncomeOption(1);
    })
</script>
</body>

</html>