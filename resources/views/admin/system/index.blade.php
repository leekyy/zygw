@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active"> 系统配置</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>签到积分（每次签到赠送的积分值）</th>
                                <th>推荐积分（每次推荐新用户获得的积分值）</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="tr_{{$data->id}}">
                                <td>
                                    <div class="line-height-30">
                                        {{$data->qd_jifen}}
                                    </div>
                                </td>
                                <td>
                                    <div class="line-height-30">
                                        {{$data->tj_jifen}}
                                    </div>
                                </td>
                                <td>
                                    <span class="line-height-30">
                                        <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                              data-toggle="tooltip"
                                              data-placement="top"
                                              title="编辑该系统设置"
                                              onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                        </span>
                                    </span>
                                </td>
                            </tr>
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

            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>配置时间</th>
                                <th>配置人</th>
                                <th>配置操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($systemRecords as $systemRecord)
                                <tr id="tr_{{$systemRecord->id}}">
                                    <td>
                                        <div class="line-height-30">
                                            {{$systemRecord->updated_at}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$systemRecord->admin->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$systemRecord->desc}}
                                        </div>
                                    </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>

    </section>
    {{--新建对话框--}}
    <div class="modal fade -m" id="addSystemModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理系统设置</h4>
                </div>
                <form id="editSystemInfo" action="{{URL::asset('/admin/system/edit')}}" method="post"
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
                                <label for="admin_id" class="col-sm-2 control-label">录入人id</label>
                                <div class="col-sm-10">
                                    <input id="admin_id" name="admin_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title" class="col-sm-2 control-label">配置人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qd_jifen" class="col-sm-2 control-label">签到积分</label>

                                <div class="col-sm-10">
                                    <input id="qd_jifen" name="qd_jifen" type="number" class="form-control"
                                           placeholder="每日签到赠送积分"
                                           value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tj_jifen" class="col-sm-2 control-label">推荐积分</label>

                                <div class="col-sm-10">
                                    <input id="tj_jifen" name="tj_jifen" type="number" class="form-control"
                                           placeholder="推荐用户赠送积分"
                                           value="0">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addSystemModal_confirm_btn" data-value=""
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
                    <p>您确认要删除该系统设置片吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delSystemInfo();"
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

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        //点击新建系统设置
        function clickAdd() {
            //清空模态框
            $("#editSystemInfo")[0].reset();
            $("#admin_id").val("{{$admin->id}}");
            $("#addSystemModal").modal('show');
        }

        //点击编辑
        function clickEdit(systemInfo_id) {
            console.log("clickEdit systemInfo_id:" + systemInfo_id);
            getSystemInfo("{{URL::asset('')}}", {_token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#qd_jifen").val(msgObj.qd_jifen);
                    $("#tj_jifen").val(msgObj.tj_jifen);
                    //展示modal
                    $("#addSystemModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            console.log("checkValid");
            var qd_jifen = $("#qd_jifen").val();
            //合规校验
            if (judgeIsNullStr(qd_jifen)) {
                $("#qd_jifen").focus();
                return false;
            }
            var tj_jifen = $("#tj_jifen").val();
            if (judgeIsNullStr(tj_jifen)) {
                $("#tj_jifen").focus();
                return false;
            }
            return true;
        }
    </script>
@endsection