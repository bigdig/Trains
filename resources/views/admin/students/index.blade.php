@extends('admin.layouts.main')

{{--顶部前端资源--}}
@section('styles')
	<style>
	.cert_img{
		position: absolute;
		top: 208px;
		left: 266px;
		width: 69px;
		height: 75px;
	}
	.cert_name{
		position: absolute;
		top: 407px;
		left: 139px;
	}
	.cert_student_position{
		position: absolute;
		top: 407px;
		left: 293px;
	}
	.cert_train_name{
		position: absolute;
		top: 407px;
		right: 35px;
		width: 137px;
	}
	.cert_park_name{
		position: absolute;
		top: 430px;
		left: 139px;
	}
	.cert_score{
		position: absolute;
		top: 452px;
		left: 149px;
	}
	.cert_number{
		position: absolute;
		top: 472px;
		left: 130px;
	}
	.cert_created_at{
		position: absolute;
		top: 505px;
		left: 99px;
	}

	</style>
    {{--ajax使用--}}
    <link href="{{ asset('vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">名单</span>
                    </div>
                    <form style="float: right;" class="form-inline" method="get" action="{{route('students.index')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="contract_no" id="contract_no" value="{{isset($search['contract_no'])?$search['contract_no']:''}}" placeholder="合同号">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="park_name" id="park_name" value="{{isset($search['park_name'])?$search['park_name']:''}}" placeholder="园所名称">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="student_phone" id="student_phone" value="{{isset($search['student_phone'])?$search['student_phone']:''}}" placeholder="学员手机号">
                        </div>
                        <div class="form-group">
                            <label for="">培训主题</label>
                            <select class="form-control" name="train_id" id="train_id">
                                <option value="">全部</option>
                                @foreach($trains as $train)
                                    <option value="{{ $train->id }}">{{ $train->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">状态</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">全部</option>
                                <option value="0">未审核</option>
                                <option value="2">审核未通过</option>
                                <option value="1">未签到</option>
                                <option value="3">已签到</option>
                                <option value="4">已完成</option>
                                <option value="-1">退训</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">搜索</button>
                        <button type="button" onclick="export_data()" class="btn btn-default">导出</button>
                    </form>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th > 园所合同号 </th>
                                <th > 园所名称 </th>
                                <th > 培训主题 </th>
                                <th > 学员姓名 </th>
                                <th > 性别 </th>
                                <th > 学员手机号 </th>
                                <th > 学员岗位</th>
                                <th > 签到日期 </th>
                                <th > 培训状态 </th>
                                <th > 操作 </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr id="students_li_{{$list->id}}">
                                    <td>{{ $list->get_order->contract_no }}</td>
                                    <td>{{ $list->get_order->park_name }}</td>
                                    <td>{{ $list->get_order->get_train->title }}</td>
                                    <td class="name">{{ $list->get_nursery_user->student_name }}</td>
                                    <td class="sex">{{ $list->get_nursery_user->student_sex==1?'男':'女' }}</td>
                                    <td class="phone">{{ $list->get_nursery_user->student_phone }}</td>
                                    <td class="position">{{ $list->get_nursery_user->student_position }}</td>
                                    <td>{{ $list->sign_time }}</td>
                                    <td>
                                        @if($list->status ==0)
                                            未审核
                                        @elseif($list->status ==1)
                                            审核通过未签到
                                        @elseif($list->status==2)
                                            审核未通过
                                        @elseif($list->status==3)
                                            已签到
                                        @elseif($list->status==4)
                                            已完成
                                        @else
                                            退训
                                        @endif
                                    </td>
                                    <td>
										<button class="btn" onclick="show_detail({{$list->id}})">查看</button>
                                        @if($list->status==0)
                                            <button class="btn" onclick="show_check({{$list->id}})">审核</button>
                                            <button class="btn" onclick="show_refund({{$list->id}})">退训</button>
                                        @elseif($list->status ==1)
                                            <button class="btn" onclick="show_refund({{$list->id}})">退训</button>
                                            <button class="btn" onclick="show_sign({{$list->id}})">签到</button>
                                        @elseif($list->status ==2 || $list->status ==3)
                                            <button class="btn" onclick="show_refund({{$list->id}})">退训</button>
                                        @else
                                        @endif
										@if($list->status ==3)
											@if($list->get_order->get_train->get_charge->is_cert)
											<a href="{{ url('admin/students/go_done',$list->id) }}" class="btn btn-outline green btn-sm purple"><i class="fa fa-edit"></i>发放证书</a>
											@else
                                            <button class="btn" onclick="over_done({{$list->id}})">设置完成</button>
											@endif
										@endif
										@if($list->status ==4)
											@if($list->get_order->get_train->get_charge->is_cert)
												<button class="btn" onclick="show_cert( {{$list->get_order->get_train->id}},{{$list->get_nursery_user->id}},{{$list->get_order->id}} )">证书预览</button>
											@endif
										@endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $lists->appends($search)->links() }}
            </div>
        </div>
    </div>
    <div id="sign" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">请确认以下信息</h4>
                </div>
                <div class="modal-body">
                    <div class="scroller" style="height:200px" data-always-visible="1" data-rail-visible1="1">
                        <div class="col-md-12">
                            <dl>
                                <dt>学员姓名</dt>
                                <dd id="sname"></dd>
                                <dt>学员手机号</dt>
                                <dd id="sphone"></dd>
                                <dt>学员性别</dt>
                                <dd id="ssex"></dd>
                                <dt>学员岗位</dt>
                                <dd id="sposition"></dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="order_students_id" value="">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                    <button type="button" class="btn green" href="javascript:;" onclick="sign()">保存</button>
                </div>
            </div>
        </div>
    </div>

    <div id="refund" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">退训申请</h4>
                </div>
                <div class="modal-body">
                    <div class="scroller" style="height:200px" data-always-visible="1" data-rail-visible1="1">
                        <div class="col-md-12">
                            <input type="hidden" id="rid" name="rid">
                            <div class="">
                                <p>
                                    <input class="form-control" readonly type="text" id="rname" value="">
                                </p>
                            </div>
                            <div class="">
                                <p>
                                    <input class="form-control" readonly type="text" id="rcontract_no" value="">
                                </p>
                            </div>
                            <div class="">
                                <p>
                                    <textarea class="form-control" row="4" id="rremark" placeholder="退训原因"></textarea>
                                </p>
                            </div>
                            <p class="alert alert-danger" style="display: none" id="tag_error">
                                <strong>错误!</strong>&nbsp;&nbsp;<span id="post-error"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                    <button type="button" class="btn green" href="javascript:;" onclick="refund()">保存</button>
                </div>
            </div>
        </div>
    </div>

    <div id="check" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">审核学员</h4>
                </div>
                <div class="modal-body">
                    <div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible1="1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <colgroup>
                                        <col class="col-xs-2">
                                        <col class="col-xs-6">
                                    </colgroup>
                                    <thead>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">学员姓名</th>
                                        <td class="csname"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员性别</th>
                                        <td class="cssex"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员手机号</th>
                                        <td class="csphone"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员岗位</th>
                                        <td class="csposition"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">毕业学校</th>
                                        <td class="csschool"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学历</th>
                                        <td class="cseducation"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">专业</th>
                                        <td class="csprofession"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">身份证号</th>
                                        <td class="csidcard"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">身份证</th>
                                        <td class="csidcardp"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">健康证</th>
                                        <td class="cshealth"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">劳动合同</th>
                                        <td class="cslabor"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">培训协议</th>
                                        <td class="cslearnership"></td>
                                    </tr>
                                    <tr>
                                        <th>备注/退训原因</th>
                                        <td>
                                            <textarea name="cremark" id="cremark" cols="50" rows="3"></textarea>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="check_order_student" value="">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                    <button type="button" class="btn green" href="javascript:;" onclick="check_success(1)">通过</button>
                    <button type="button" class="btn red" href="javascript:;" onclick="check_success(2)">驳回</button>
                </div>
            </div>
        </div>
    </div>
	
	<div id="detail" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">查看学员</h4>
                </div>
                <div class="modal-body">
                    <div class="scroller" style="height:500px" data-always-visible="1" data-rail-visible1="1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <colgroup>
                                        <col class="col-xs-2">
                                        <col class="col-xs-6">
                                    </colgroup>
                                    <thead>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th scope="row">学员姓名</th>
                                        <td class="csname"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员性别</th>
                                        <td class="cssex"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员手机号</th>
                                        <td class="csphone"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学员岗位</th>
                                        <td class="csposition"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">毕业学校</th>
                                        <td class="csschool"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">学历</th>
                                        <td class="cseducation"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">专业</th>
                                        <td class="csprofession"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">身份证号</th>
                                        <td class="csidcard"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">身份证</th>
                                        <td class="csidcardp"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">健康证</th>
                                        <td class="cshealth"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">劳动合同</th>
                                        <td class="cslabor"></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">培训协议</th>
                                        <td class="cslearnership"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                </div>
            </div>
        </div>
    </div>
	
	<div id="cert" class="modal fade" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">培训证书</h4>
                </div>
                <div class="modal-body" style="font-size:10px">
                        <img style="width:100%;height:550px" src="{{ asset('assets/admin/img/zs.png')}}">
						<span><img src="" class="cert_img"/></span>
						<span class="cert_name"></span>
						<span class="cert_student_position"></span>
						<span class="cert_train_name"></span>
						<span class="cert_park_name"></span>
						<span class="cert_score"></span>
						<span class="cert_number"></span>
						<span class="cert_created_at"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--尾部前端资源--}}
@section('script')
    <script type="text/javascript">
		var train_id = "{{ isset($search['train_id'])?$search['train_id']:'' }}";
		$("#train_id").val(train_id);
        $(function () {
            SweetAlert.init();
        })
		function over_done(id){
            swal({
                title: "确定设置完成吗？",
                text: "设置完成后自动确认发放证书",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            },
            function(){
                $.ajax({
                    url:"{{ url('admin/students/over_done') }}",
                    type:'POST',
                    data:{
                        order_students_id:id,
                        '_token': "{{ csrf_token() }}"
                    },
                    success:function (e) {
                        if(e.code=='200'){
                            swal("OK!", e.msg, "success");
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        }else{
                            swal("OOPS!", e.msg, "error");
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
                        }
                    }
                })
            });
        }
        //导出
        function export_data(){
            var contract_no   = $("#contract_no").val();
            var park_name     = $("#park_name").val();
            var student_phone = $("#student_phone").val();
            var train_id      = $("#train_id").val();
			var s_train_id    = "{{ isset($search['train_id'])?$search['train_id']:'' }}";
            var status        = $("#status").val();
			var trainId       = train_id?train_id:(s_train_id?s_train_id:'');

            window.location.href = "{{ url('admin/students/export_data') }}?contract_no="+contract_no+"&park_name="+park_name+"&student_phone="+student_phone+"&train_id="+trainId+"&status="+status;
        }
        function show_check(id) {
            $.ajax({
                url:"{{ url('admin/students/info') }}",
                type:"POST",
                data:{
                    order_students_id:id,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    if(e.code =='200'){
                        var data = e.data;
                        $(".csname").html(data.get_nursery_user.student_name);
                        $(".cssex").html(data.get_nursery_user.student_sex ==1?"男":"女");
                        $(".csphone").html(data.get_nursery_user.student_phone);
                        $(".csposition").html(data.get_nursery_user.student_position);
                        $(".csschool").html(data.get_nursery_user.school);
                        $(".cseducation").html(data.get_nursery_user.education);
                        $(".cseducation").html(data.get_nursery_user.education);
                        $(".csprofession").html(data.get_nursery_user.profession);
                        $(".csidcard").html(data.get_nursery_user.idcard);
						if(data.get_nursery_user.card_z){
							$(".csidcardp").html("<a target='_bank' href='"+data.get_nursery_user.card_z+"'><img width='150' src='"+data.get_nursery_user.card_z+"'></a> <a target='_bank' href='"+data.get_nursery_user.card_f+"'><img width='150' src='"+data.get_nursery_user.card_f+"'></a>");
						}
						if(data.get_nursery_user.health_1){
							$(".cshealth").html("<a target='_bank' href='"+data.get_nursery_user.health_1+"'><img width='150' src='"+data.get_nursery_user.health_1+"'></a> <a target='_bank' href='"+data.get_nursery_user.health_2+"'><img width='150' src='"+data.get_nursery_user.health_2+"'></a>");
						}
						if(data.get_nursery_user.labor_1){
							$(".cslabor").html("<a target='_bank' href='"+data.get_nursery_user.labor_1+"'><img width='150' src='"+data.get_nursery_user.labor_1+"'></a> <a target='_bank' href='"+data.get_nursery_user.labor_2+"'><img width='150' src='"+data.get_nursery_user.labor_2+"'></a>");
						}
						if(data.get_nursery_user.learnership){
							$(".cslearnership").html("<a target='_bank' href='"+data.get_nursery_user.learnership+"'><img width='150' src='"+data.get_nursery_user.learnership+"'></a>");
						}
                        $("#check_order_student").val(id);
                        $("#check").modal();
                    }
                }
            })
        }
		function show_detail(id) {
            $.ajax({
                url:"{{ url('admin/students/info') }}",
                type:"POST",
                data:{
                    order_students_id:id,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    if(e.code =='200'){
                        var data = e.data;
                        $(".csname").html(data.get_nursery_user.student_name);
                        $(".cssex").html(data.get_nursery_user.student_sex ==1?"男":"女");
                        $(".csphone").html(data.get_nursery_user.student_phone);
                        $(".csposition").html(data.get_nursery_user.student_position);
                        $(".csschool").html(data.get_nursery_user.school);
                        $(".cseducation").html(data.get_nursery_user.education);
                        $(".cseducation").html(data.get_nursery_user.education);
                        $(".csprofession").html(data.get_nursery_user.profession);
                        $(".csidcard").html(data.get_nursery_user.idcard);
						if(data.get_nursery_user.card_z){
							$(".csidcardp").html("<a target='_bank' href='"+data.get_nursery_user.card_z+"'><img width='150' src='"+data.get_nursery_user.card_z+"'></a> <a target='_bank' href='"+data.get_nursery_user.card_f+"'><img width='150' src='"+data.get_nursery_user.card_f+"'></a>");
						}
						if(data.get_nursery_user.health_1){
							$(".cshealth").html("<a target='_bank' href='"+data.get_nursery_user.health_1+"'><img width='150' src='"+data.get_nursery_user.health_1+"'></a> <a target='_bank' href='"+data.get_nursery_user.health_2+"'><img width='150' src='"+data.get_nursery_user.health_2+"'></a>");
						}
						if(data.get_nursery_user.labor_1){
							$(".cslabor").html("<a target='_bank' href='"+data.get_nursery_user.labor_1+"'><img width='150' src='"+data.get_nursery_user.labor_1+"'></a> <a target='_bank' href='"+data.get_nursery_user.labor_2+"'><img width='150' src='"+data.get_nursery_user.labor_2+"'></a>");
						}
						if(data.get_nursery_user.learnership){
							$(".cslearnership").html("<a target='_bank' href='"+data.get_nursery_user.learnership+"'><img width='150' src='"+data.get_nursery_user.learnership+"'></a>");
						}
                        $("#detail").modal();
                    }
                }
            })
        }
		function show_cert(train_id,student_id,order_id) {
            $.ajax({
                url:"{{ url('admin/students/cert') }}",
                type:"POST",
                data:{
                    train_id:train_id,
                    student_id:student_id,
					order_id:order_id,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    if(e.code =='200'){
                        var data = e.data;
						$(".cert_img").prop("src",data.cert_picture);
						$(".cert_name").html(data.student_name);
						$(".cert_student_position").html(data.student_position);
						$(".cert_train_name").html(data.park_name);
						$(".cert_park_name").html(data.train_name);
						$(".cert_score").html(data.score);
						$(".cert_number").html(data.number);
						$(".cert_created_at").html(data.created_at.substring(0,data.created_at.lastIndexOf("-")+3));
                        $("#cert").modal();
                    }
                }
            })
        }
        function check_success(status) {
            var check_order_student_id = $("#check_order_student").val();
            var remark = $("#cremark").val();
            $.ajax({
                url:"{{ url('admin/students/check') }}",
                type:"POST",
                data:{
                    order_students_id:check_order_student_id,
                    status           :status,
                    remark           :remark,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    $('#check').modal('hide');
                    if(e.code=='200'){
                        swal("OK!", e.msg, "success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else{
                        swal("OOPS!", e.msg, "error");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                }
            })
        }
        function show_sign(id) {
            var name = $("#students_li_"+id).find('.name').text();
            var sex = $("#students_li_"+id).find('.sex').text();
            var phone = $("#students_li_"+id).find('.phone').text();
            var position = $("#students_li_"+id).find('.position').text()

            $("#sname").html(name);
            $("#sphone").html(phone);
            $("#ssex").html(sex);
            $("#sposition").html(position);
            $("#order_students_id").val(id);
            $("#sign").modal();
        }
        function sign() {
            var order_students_id = $("#order_students_id").val();
            $.ajax({
                url:"{{ url('admin/students/sign') }}",
                type:'POST',
                data:{
                    order_students_id:order_students_id,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    $('#sign').modal('hide');
                    if(e.code=='200'){
                        swal("OK!", e.msg, "success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else{
                        swal("OOPS!", e.msg, "error");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                }
            })
        }
        function show_refund(id) {
            var name = $("#students_li_"+id).find('.name').text();
            var phone = $("#students_li_"+id).find('.phone').text();
            $("#rname").val(name);
            $("#rcontract_no").val(phone);
            $("#rid").val(id);
            $("#refund").modal();
        }
        function refund() {
            var rid = $("#rid").val();
            var rremark = $("#rremark").val();

            $.ajax({
                url:"{{ url('admin/students/refund') }}",
                type:"POST",
                data:{
                    rid:rid,
                    remark:rremark,
                    '_token': "{{ csrf_token() }}"
                },
                success:function (e) {
                    if(e.code=='200'){
                        $('#refund').modal('hide');
                        swal("OK!", e.msg, "success");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }else{
                        $('#refund').modal('hide');
                        swal("OOPS!", e.msg, "error");
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    }
                }
            })
        }
    </script>
    <script src="{{asset('assets/admin/layouts/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
    {{--ajax使用--}}
    <script src="{{asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    {{--sweetalert弹窗--}}
    <script src="{{asset('assets/admin/layouts/scripts/sweetalert/sweetalert-ajax-delete.js')}}" type="text/javascript"></script>
@endsection

