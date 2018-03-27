@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">楼盘标签管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建楼盘标签
                </button>
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
                                <th>ID</th>
                                <th>内容</th>
                                <th>时间</th>
                                <th>录入人</th>
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
                                        <div class="line-height-30 text-info text-oneline">
                                            {{$data->name}}
                                        </div>
                                    </td>
                                    <td><span class="line-height-30">{{$data->created_at_str}}</span>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                        @if ($data->admin)
                                                {{$data->admin->name}}
                                            @else
                                                未知
                                            @endif
                                        </span>
                                    </td>



                                    <td>
                                        <span class="line-height-30">
                                        <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                              data-toggle="tooltip"
                                              data-placement="top"
                                              title="编辑该楼盘标签"
                                              onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                        </span>
                                    </span>
                                        <span class="line-height-30">
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该楼盘标签"
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

            </div>
        </div>
    </section>
    {{--新建对话框--}}
    <div class="modal fade -m" id="addHouseLabelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理楼盘标签图</h4>
                </div>
                <form id="editHouseLabel" action="{{URL::asset('/admin/houseLabel/edit')}}" method="post"
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
                                <label for="title" class="col-sm-2 control-label">录入人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">名称*</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="请输入楼盘标签名称，例如地铁、学区等"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="url"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                        <button type="submit" id="addHouseLabelModal_confirm_btn" data-value=""
                                class="btn btn-success">确定
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    {{--提示Modal--}}
    <div class="modal fade" id="tipModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">提示信息</h4>
                </div>
                <div class="modal-body" id="tipModalBody">

                </div>
                <div class="modal-footer">
                    <button id="delConfrimModal_confirm_btn" data-value=""
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

        //点击删除
        function clickDel(ad_id) {
            console.log("clickDel ad_id:" + ad_id);
            $("#tipModalBody").html('<p>楼盘标签数据关联较多信息，请联系管理员处理</p>');
            $("#tipModal").modal('show');
        }


        //点击新建楼盘标签图
        function clickAdd() {
            //清空模态框
            $("#editHouseLabel")[0].reset();
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addHouseLabelModal").modal('show');
        }

        //点击编辑
        function clickEdit(id) {
            //普通管理员没有修改权限
            if ("{{$admin->role}}" == "0") {
                $("#tipModalBody").html('<p>普通管理员没有新建/管理管理员权限，请联系超级管理员处理</p>');
                $("#tipModal").modal('show');
                return;
            }
            console.log("clickEdit id:" + id);
            getHouseLabelInfo("{{URL::asset('')}}", {id: id,_token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);

                    //展示modal
                    $("#addHouseLabelModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            console.log("checkValid");
            var name = $("#name").val();
            //合规校验
            if (judgeIsNullStr(name)) {
                $("#name").focus();
                return false;
            }
            return true;
        }
    </script>
@endsection