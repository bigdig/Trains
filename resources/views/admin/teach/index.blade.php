@extends('admin.layouts.main')

{{--顶部前端资源--}}
@section('styles')

@endsection

{{--页面内容--}}
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN SAMPLE TABLE PORTLET-->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-red"></i>
                        <span class="caption-subject font-red sbold uppercase">教师管理</span>
                    </div>
					<div class="col-md-4">
                        <form class="form-inline" method="get" action="{{ route('teach.index') }}">
                            <input type="text" name="keyword" class="form-control" placeholder="园所合同号、手机号">
                            <button type="submit" class="btn btn-default">搜索</button>
                        </form>
                    </div>
					<!--
                    <div class="actions">
                        <div class="btn-group">
                            <a href="{{ route('teach.index') }}" class="btn green btn-outline">
                                <i class="fa fa-edit"></i>
                                添加课程
                            </a>
                        </div>
                    </div>
					-->
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th > ID </th>
                                <th > 合同号 </th>
                                <th > 学员名称 </th>
                                <th > 性别 </th>
                                <th > 手机号 </th>
                                <th > 学历 </th>
                                <th > 毕业学校 </th>
                                <th > 专业 </th>
                                <th > 创建时间 </th>
                                <th > 操作 </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr id="teach_li_{{$list->id}}">
                                    <td>{{ $list->id }}</td>
                                    <td>{{ $list->contract_no }}</td>
                                    <td>{{ $list->student_name }}</td>
                                    <td>{{ $list->student_sex ==1?'男':'女' }}</td>
                                    <td>{{ $list->student_phone }}</td>
                                    <td>{{ $list->education }}</td>
                                    <td>{{ $list->school }}</td>
                                    <td>{{ $list->profession }}</td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>
                                        <a href="{{ url('admin/teach/get_train_record',['student_id'=>$list->id]) }}" class="btn btn-outline green btn-sm purple"><i class="fa fa-search-plus"></i>查看</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                {{ $lists->appends($search)->links() }}
            </div>
            <!-- END SAMPLE TABLE PORTLET-->
        </div>
    </div>
@endsection

{{--尾部前端资源--}}
@section('script')
    <script src="{{ asset('vendor/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var SweetAlert = function () {
            return {
                //main function to initiate the module
                init: function () {
                    $('.mt-sweetalert').each(function(){
                        var sa_title = $(this).data('title');
                        var sa_message = $(this).data('message');
                        var sa_type = $(this).data('type');
                        var sa_allowOutsideClick = $(this).data('allow-outside-click');
                        var sa_showConfirmButton = $(this).data('show-confirm-button');
                        var sa_showCancelButton = $(this).data('show-cancel-button');
                        var sa_closeOnConfirm = $(this).data('close-on-confirm');
                        var sa_closeOnCancel = $(this).data('close-on-cancel');
                        var sa_confirmButtonText = $(this).data('confirm-button-text');
                        var sa_cancelButtonText = $(this).data('cancel-button-text');
                        var sa_popupTitleSuccess = $(this).data('popup-title-success');
                        var sa_popupMessageSuccess = $(this).data('popup-message-success');
                        var sa_popupTitleCancel = $(this).data('popup-title-cancel');
                        var sa_popupMessageCancel = $(this).data('popup-message-cancel');
                        var sa_confirmButtonClass = $(this).data('confirm-button-class');
                        var sa_cancelButtonClass = $(this).data('cancel-button-class');
                        var sa_showLoaderOnConfirm = $(this).data('show-loader-on-confirm');
                        var ajax_url = $(this).data('ajax-url');
                        var remove_dom = $(this).data('remove-dom');
                        var date_json = $(this).data('date-json');
                        var date = $(this).data('date');
                        $(this).click(function(){
                            swal({
                                    title: sa_title,
                                    text: sa_message,
                                    type: sa_type,
                                    allowOutsideClick: sa_allowOutsideClick,
                                    showConfirmButton: sa_showConfirmButton,
                                    showCancelButton: sa_showCancelButton,
                                    confirmButtonClass: sa_confirmButtonClass,
                                    cancelButtonClass: sa_cancelButtonClass,
                                    closeOnConfirm: sa_closeOnConfirm,
                                    closeOnCancel: sa_closeOnCancel,
                                    confirmButtonText: sa_confirmButtonText,
                                    cancelButtonText: sa_cancelButtonText,
                                    showLoaderOnConfirm: sa_showLoaderOnConfirm,
                                    popupMessageSuccess: sa_popupMessageSuccess,
                                    popupTitleCancel: sa_popupTitleCancel,
                                    popupMessageCancel: sa_popupMessageCancel
                                },
                                function () {
                                    setTimeout(function () {
                                        var settings = {
                                            type: "DELETE",
                                            url: ajax_url,
                                            dataType:"json",
                                            data: date_json,
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                            },
                                            success: function(data) {
                                                if (data.result == 'error') {
                                                    sweetAlert({
                                                        title:"删除失败",
                                                        text:"请联系管理员",
                                                        type:"error"
                                                    });
                                                } else {
                                                    $("#" + remove_dom + date).remove();
                                                    swal(sa_popupTitleSuccess);
                                                }
                                            },
                                            error:function (xhr, errorText, errorType) {
                                                if (xhr.responseJSON.error == 'no_permissions') {
                                                    sweetAlert({
                                                        title:'您没有此权限',
                                                        text:"请联系管理员",
                                                        type:"error"
                                                    });
                                                    return false;
                                                } else {
                                                    sweetAlert({
                                                        title:'未知错误',
                                                        text:"请联系管理员",
                                                        type:"error"
                                                    });
                                                    return false;
                                                }
                                            }
                                        };
                                        $.ajax(settings);
                                    }, 500);
                                });
                        });
                    });

                }
            }

        }();
        /*alert()弹窗*/
        jQuery(document).ready(function() {
            SweetAlert.init();
        });
    </script>

@endsection

