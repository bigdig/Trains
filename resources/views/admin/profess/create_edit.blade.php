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
                        <span class="caption-subject bold uppercase"> 编辑职称 </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form role="form" lpformnum="1" _lpchecked="1" method="post" action="{{ $edit?route('profess.update',$info->id):route('profess.store') }}">
                        @if($edit)
                        <input type="hidden" name="_method" value="PUT">
                        @endif
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-body">
							@if(Auth::user()->hasRole('admin'))
							<div class="form-group">
                                <label for=""><span style="color:red">*</span>适用园所类型</label>
                                <div class="radio dinfo">
                                    <label class="radio-inline">
                                        <input type="radio" checked name="profess_type" value="1"> 亲子园
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="profess_type" value="2">幼儿园
                                    </label>
                                </div>
                            </div>
							@endif
                            <div class="form-group">
                                <label>职称名称</label>
                                <input type="text" name="professional" class="form-control" placeholder="职称名称" value="{{ $edit?$info->professional:'' }}">
                            </div>
                            <div class="form-group">
                                <label>描述信息</label>
                                <textarea name="desc" class="form-control" id="" cols="100" rows="5" placeholder="描述信息">{{ $edit?$info->desc:'' }}</textarea>
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
            $("input[name='profess_type'][value='{{ $edit?$info->profess_type:'1' }}']").attr('checked','true');
        })
    </script>
@endsection