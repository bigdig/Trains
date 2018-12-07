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
                        <span class="caption-subject font-red sbold uppercase">课程管理</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group">
                            <a href="{{ route('course.create') }}" class="btn green btn-outline">
                                <i class="fa fa-edit"></i>
                                添加课程
                            </a>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <table class="table table-striped table-bordered table-hover" id="datatable_ajax">
                            <thead>
                            <tr role="row" class="heading">
                                <th > ID </th>
                                <th > 所属培训 </th>
                                <th > 课程名称 </th>
                                <th > 适用园所类型 </th>
                                <th > 描述 </th>
                                <th > 状态 </th>
                                <th > 创建时间 </th>
                                <th > 操作 </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lists as $list)
                                <tr id="course_li_{{$list->id}}">
                                    <td>{{ $list->id }}</td>
                                    <td>{{ isset($list->get_train->title)?$list->get_train->title:'通用' }}</td>
                                    <td>{{ $list->course_name }}</td>
									<td>
										@if($list->course_type ==1)
											亲子园
										@elseif($list->course_type ==2)
											幼儿园
										@else
										@endif
									</td>
                                    <td>{{ $list->desc }}</td>
                                    <td>
                                        @if($list->status ==1)
                                            正常
                                        @elseif($list->status ==0)
                                            禁用
                                        @else
                                            <span style="color: red;">已删除</span>
                                        @endif
                                    </td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-outline dark btn-sm black mt-sweetalert" style="margin-bottom: 0;" data-title="确定要删除该课程吗？" data-message="" data-type="warning" data-allow-outside-click="true" data-show-cancel-button="true" data-cancel-button-text="点错了" data-cancel-button-class="btn-danger" data-show-confirm-button="true" data-confirm-button-text="确定" data-confirm-button-class="btn-info" data-popup-title-success="删除成功" data-close-on-cancel="true" data-close-on-confirm="false" data-show-loader-on-confirm="true" data-ajax-url="{{ route('course.destroy',$list->id) }}" data-remove-dom="course_li_" data-id="{{ $list->id }}">
                                            <i class="fa fa-trash-o"></i>
                                            删除
                                        </a>
                                        <a href="{{ route('course.edit',$list->id) }}" class="btn btn-outline green btn-sm purple"><i class="fa fa-edit"></i>编辑</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $lists->links() }}
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

