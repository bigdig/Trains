@extends('admin.layouts.main')

{{--顶部前端资源--}}
@section('styles')
    <link href="{{ asset('vendor/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-md-3">

        </div>
        <div class="col-md-6 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-red-sunglo">
                        <i class="icon-settings font-red-sunglo"></i>
                        <span class="caption-subject bold uppercase"> 发放证书 </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form role="form" enctype="multipart/form-data" lpformnum="1" _lpchecked="1" method="post" action="{{ url('admin/students/done') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <div class="form-group">
                                <label>姓名</label>
                                <input type="text" name="student_name" class="form-control" placeholder="姓名" value="{{ $students_info->get_nursery_user->student_name }}">
                            </div>
							<div class="form-group">
                                <label>职位</label>
                                <input type="text" name="student_position" class="form-control" placeholder="职位" value="{{ $students_info->get_nursery_user->student_position }}">
                            </div>
							<div class="form-group">
                                <label>园所</label>
                                <input type="text" name="park_name" class="form-control" placeholder="园所" value="{{ $order_info->park_name }}">
                            </div>
							<div class="form-group last">
                                <label class="control-label"><span style="color:red">*</span>证件照(建议尺寸...)</label>
                                <div class="">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                            <img src="{{ asset('assets/admin/img/no_image.png') }}" alt="" /> </div>
                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                        <div>
                                            <span class="btn default btn-file">
                                                <span class="fileinput-new"> 选择图片 </span>
                                                <span class="fileinput-exists"> 更换 </span>
                                                <input type="file" name="cert_picture">
                                            </span>
                                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 删除 </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
                                <label>培训项目</label>
                                <input type="text" name="train_name" class="form-control" placeholder="培训项目" value="{{ $order_info->get_train->title }}">
                            </div>
							<div class="form-group">
                                <label>成绩</label>
                                <input type="text" name="score" class="form-control" placeholder="成绩" value="">
                            </div>
							<div class="form-group">
                                <label>编号</label>
                                <input type="text" name="number" class="form-control" placeholder="编号" value="">
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="hidden" name="student_id" value="{{ $students_info->get_nursery_user->id }}" />
                            <input type="hidden" name="train_id" value="{{ $order_info->get_train->id }}" />
                            <input type="hidden" name="order_id" value="{{ $order_info->id }}" />
                            <input type="hidden" name="order_student_id" value="{{ $students_info->id }}">
                            <button type="submit" class="btn blue">提交</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
        </div>
    </div>
@endsection

{{--尾部前端资源--}}
@section('script')
    <script src="{{ asset('vendor/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
       
    </script>
@endsection