@extends('admin.layouts.main')

{{--顶部前端资源--}}
@section('styles')
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
                        <span class="caption-subject font-red sbold uppercase">培训报表</span>
                    </div>
                    <form style="float: right;" class="form-inline" method="get" action="{{route('report.index')}}">
                        <div class="form-group">
                            <input type="text" class="form-control" name="contract_no" id="contract_no" placeholder="合同号">
                        </div>
                        <button type="submit" class="btn btn-default">搜索</button>
                    </form>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th > 园所合同号 </th>
                                <th > 园所名称 </th>
                                <th > 报名次数 </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr id="report_li_{{$list->contract_no}}">
                                    <td>{{ $list->contract_no }}</td>
                                    <td>{{ $list->park_name }}</td>
                                    <td>
										<a href="{{ url('admin/report/entry_list/'.$list['contract_no']) }}">{{ $list->trains_num }}</a>
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
@endsection

{{--尾部前端资源--}}
@section('script')
    <script type="text/javascript">
        
    </script>
    <script src="{{asset('assets/admin/layouts/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('vendor/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
    {{--ajax使用--}}
    <script src="{{asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
    {{--sweetalert弹窗--}}
    <script src="{{asset('assets/admin/layouts/scripts/sweetalert/sweetalert-ajax-delete.js')}}" type="text/javascript"></script>
@endsection

