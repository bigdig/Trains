@extends('admin.layouts.main')

{{--顶部前端资源--}}
@section('styles')

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
                        <span class="caption-subject bold uppercase"> {{ $edit?'编辑':'新增' }}课程 </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form role="form" lpformnum="1" _lpchecked="1" method="post" action="{{ $edit?route('course.update',$info->id):route('course.store') }}">
                        @if($edit)
                            <input type="hidden" name="_method" value="PUT">
                        @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
                            <div class="form-group">
                                <label>所属培训</label>
                                <select name="train_id" id="train_id" class="form-control">
                                    <option value="-1">所属培训</option>
                                    <option value="0">通用</option>
                                    @foreach($trains as $train)
                                        <option value="{{ $train->id }}">{{ $train->title }}</option>
                                    @endforeach
                                </select>
                            </div>
							@if( Auth::user()->hasRole('admin') )
							<div class="form-group">
                                <label for=""><span style="color:red">*</span>适用园所类型</label>
                                <div class="radio dinfo">
                                    <label class="radio-inline">
                                        <input type="radio" checked name="course_type" value="1"> 亲子园
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="course_type" value="2">幼儿园
                                    </label>
                                </div>
                            </div>
							@endif
                            <div class="form-group">
                                <label>课程名称</label>
                                <input type="text" name="course_name" class="form-control" placeholder="课程名称" value="{{ $edit?$info->course_name:'' }}">
                            </div>
                            <div class="form-group">
                                <label>描述信息</label>
                                <textarea name="desc" id="" cols="100" rows="5" placeholder="描述信息">{{ $edit?$info->desc:'' }}</textarea>
                            </div>
                            @if($edit)
                                <div class="form-group">
                                    <label>状态</label>
                                    <input type="radio" name="status" value="0">禁用
                                    <input type="radio" name="status" value="1">启用
                                </div>
                            @endif
                        </div>
                        <div class="form-actions">
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
    <script type="text/javascript">
        $(function(){
            $("input[name='status'][value='{{ $edit?$info->status:'1' }}']").attr('checked','true');
            $("#train_id").val({{ $edit?(isset($info->get_train->id)?$info->get_train->id:0):'-1' }});
			$("input[name='course_type'][value='{{ $edit?$info->course_type:'1' }}']").attr('checked','true');
        })
    </script>
@endsection