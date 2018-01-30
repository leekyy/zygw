@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">房产商客户管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +导入房产商客户
                </button>
            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">
        <!--列表-->
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>电话</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->id}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->phonenum}}
                                        </div>
                                    </td>
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该轮房产商客户"
                                                  onclick="clickDel({{$data->id}})">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
                                            </span>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-sm-5">

            </div>
            <div class="col-sm-7 text-right">
                {!! $datas->links() !!}
            </div>
        </div>
    </section>

    {{--新建对话框--}}
    <div class="modal fade -m" id="addHouseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理房产商客户</h4>
                </div>
                <form id="editHouseClient" action="{{URL::asset('/admin/houseClient/edit')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="id" name="id" type="text" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="house_id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="house_id" name="house_id" type="text" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="admin_id" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="admin_id" name="admin_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="house_id" class="col-sm-2 control-label">房产商客户id</label>
                                <div class="col-sm-10">
                                    <input type="house_id" name="house_id" id="house_id" class="form-control"
                                           value="{{$house->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phonenums" class="col-sm-2 control-label">导入电话</label>
                                <div class="col-sm-10">
                                      <textarea id="phonenums" name="phonenums" class="form-control" rows="5"
                                                placeholder="请输入置业房产商客户的姓名，用,分割多个客户"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addHouseModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    {{--删除对话框--}}
    <div class="modal fade " id="delConfrimModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body">
                    <p>您确认要删除该房产商客户吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delHouseClient();"
                            class="btn btn-success"
                            data-dismiss="modal">确定
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('script')
    <script type="application/javascript">

        var house_id;
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            house_id = getQueryString("house_id");
        });


        //点击删除房产商客户
        function clickDel(house_client_id) {
            console.log("clickDel house_client_id:" + house_client_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", house_client_id);
            $("#delConfrimModal").modal('show');
        }

        //删除房产商客户
        function delHouseClient() {
            var house_client_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delHouseClient house_client_id:" + house_client_id);
            //进行tr隐藏
            $("#tr_" + house_client_id).fadeOut();
            //进行页面跳转
            //var huxing =window.location.search
            window.location.href = "{{URL::asset('/admin/houseClient/del')}}/" + house_client_id + "?house_id=" + house_id;
        }

        //点击导入房产商客户
        function clickAdd() {
            //清空模态框
            $("#editHouseClient")[0].reset();
            $("#house_id").val(house_id);
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addHouseModal").modal('show');
        }


        //合规校验
        function checkValid() {
            //合规校验
            var phonenums = $("#phonenums").val();
            if (judgeIsNullStr(phonenums)) {
                $("#phonenums").focus();
                return false;
            }
            return true;
        }

    </script>
@endsection