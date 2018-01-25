@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">积分商品兑换管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建订单
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
                                <th>兑换用户</th>
                                <th>用户电话</th>
                                <th>兑换商品名</th>
                                <th>兑换商品</th>
                                <th>商品积分</th>
                                <th>申请时间</th>
                                <th>审核人</th>
                                <th>状态</th>
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
                                            {{$data->user->real_name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->user->phonenum}}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="line-height-30">
                                            {{isset($data->goods) ? $data->goods->name : "--"}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            <img src="{{$data->goods->image}}?imageView2/1/w/60/h/40/interlace/1/q/75|imageslim">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->goods->jifen}}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="line-height-30">{{isset($data->dh_time)?$data->dh_time:"--"}}</span>
                                    </td>

                                    <td>
                                        {{isset($data->admin) ? $data->admin->name : "--"}}
                                    </td>

                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-default line-height-30">未兑换</span>
                                        @else
                                            <span class="label label-success line-height-30">已兑换</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/goodsexchange/setStatus')}}/{{$data->id}}?status=1"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="该商品已被用户成功兑换">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/goodsexchange/setStatus')}}/{{$data->id}}?status=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="暂未成功兑换">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="编辑该订单"
                                                  onclick="clickEdit({{$data->id}})">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除该订单"
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
    <div class="modal fade -m" id="addGoodsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理楼盘</h4>
                </div>
                <form id="editGoodsExchange" action="{{URL::asset('/admin/goodsexchange/edit')}}" method="post" class="form-horizontal"
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
                                <label for="real_name" class="col-sm-2 control-label">兑换用户</label>

                                <div class="col-sm-10">
                                    <input id="real_name" name="real_name" type="text" class="form-control"
                                           placeholder="用户名"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="phonenum" class="col-sm-2 control-label">用户电话</label>

                                <div class="col-sm-10">
                                    <input id="phonenum" name="phonenum" type="text" class="form-control"
                                           placeholder="用户电话"
                                           value="">
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
                    <p>您确认要删除该订单吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delGoods();"
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
//            //获取七牛token
//            initQNUploader();
        });

        //点击删除
        function clickDel(goods_id) {
            console.log("clickDel goods_id:" + goods_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", goods_id);
            $("#delConfrimModal").modal('show');
        }

        //删除订单
        function delGoods() {
            var goods_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delGoods goods_id:" + goods_id);
            //进行tr隐藏
            $("#tr_" + goods_id).fadeOut();
            //进行页面跳转
            window.location.href = "{{URL::asset('/admin/goodsexchange/del')}}/" + goods_id;
        }

        //点击新建楼盘
        function clickAdd() {
            //清空模态框
            $("#editGoodsExchange")[0].reset();
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addGoodsModal").modal('show');
        }


        //点击编辑
        function clickEdit(goods_id) {
            console.log("clickEdit goods_id:" + goods_id);
            getGoodsExchangeById("{{URL::asset('')}}", {id: goods_id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#real_name").val(msgObj.real_name);
                    $("#phonenum").val(msgObj.phonenum);
//                    $("#name").val(msgObj.name);
//                    $("#jifen").val(msgObj.jifen);
//                    $("#dh_time").val(msgObj.dh_time);
//                    $("#image").val(msgObj.image);
//                    $("#pickfiles").attr("src", msgObj.image);
                    //展示modal
                    $("#addGoodsModal").modal('show');
                }
            })
        }

        //合规校验
        function checkValid() {
            console.log("checkValid");
            var real_name = $("#real_name").val();
            //合规校验
            if (judgeIsNullStr(real_name)) {
                $("#real_name").focus();
                return false;
            }
            var phonenum = $("#phonenum").val();
            if (judgeIsNullStr(phonenum)) {
                $("#phonenum").focus();
                return false;
            }
//            var name = $("#name").val();
//            if (judgeIsNullStr(name)) {
//                $("#name").focus();
//                return false;
//            }
//            var dh_time = $("#dh_time").val();
//            if (judgeIsNullStr(dh_time)) {
//                $("#dh_time").focus();
//                return false;
//            }
//
//            var jifen = $("#jifen").val();
//            if (judgeIsNullStr(jifen)) {
//                $("#jifen").focus();
//                return false;
//            }
//            var image = $("#image").val();
//            if (judgeIsNullStr(image)) {
//                $("#image").focus();
//                return false;
//            }
            return true;
        }






    </script>
@endsection