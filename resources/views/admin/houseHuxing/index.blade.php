@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">产品管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建产品
                </button>
            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">

        {{--楼盘基本信息--}}
        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td rowspan="3">
                                    <img src="{{ $house->image ? $house->image.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/upload.png')}}"
                                         style="width: 80px;height: 80px;">
                                </td>
                                <td>楼盘</td>
                                <td>
                                    {{ $house->title }}
                                </td>
                                <td>地址</td>
                                <td>
                                    {{$house->address}}
                                </td>
                                <td>楼盘均价</td>
                                <td>{{$house->price}}元</td>
                            </tr>
                            <tr>
                                <td>面积区间</td>
                                <td>{{$house->size_min}}㎡</td>
                                <td>面积区间</td>
                                <td>{{$house->size_max}}㎡</td>
                                <td></td>
                                <td>--</td>
                            </tr>
                            <tr>
                                <td>标签</td>
                                <td>
                                    @foreach($house->labels as $label)
                                        {{$label->name}}
                                    @endforeach
                                </td>
                                <td>类型</td>
                                <td>
                                    @foreach($house->types as $type)
                                        {{$type->name}}
                                    @endforeach
                                </td>
                                <td></td>
                                <td>--</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--列表-->
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>图片</th>
                                <th>名称</th>
                                <th>类型</th>
                                <th>佣金类型</th>
                                <th>佣金额度</th>
                                <th>管理员</th>
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
                                        <img src="{{ $data->image ? $data->image.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->type->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->yongjin_type == '0'?'按固定金额':'按千分比'}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->yongjin_value}}{{$data->yongjin_type == '0'?'元':'‰'}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->admin->name}}
                                        </div>
                                    </td>
                                    <td>
                                        @if($data->status === '1')
                                            <span class="label label-success line-height-30">展示</span>
                                        @else
                                            <span class="label label-default line-height-30">隐藏</span>
                                        @endif

                                    </td>
                                    <td class="con-th-width-m">
                                        <span class="line-height-30">
                                              <a href="{{URL::asset('/admin/huxing/setStatus')}}/{{$data->id}}?opt=1"
                                                 class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                                 data-toggle="tooltip"
                                                 data-placement="top" title="在小程序页面中展示该产品">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/huxing/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中隐藏该产品">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>

                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  onclick="clickEdit({{$data->id}})"
                                                  title="编辑该产品">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size margin-right-10"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="设置佣金分成"
                                                  onclick="clickSetYongjing({{$data->id}})">
                                                <i class="fa fa-cny opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/huxingYongjinRecord/index')}}?huxing_id={{$data->id}}"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="佣金设置记录">
                                                <i class="fa fa-align-justify opt-btn-i-size"></i>
                                            </a>
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
                {{--{!! $datas->links() !!}--}}
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
                    <h4 class="modal-title">管理产品</h4>
                </div>
                <form id="editHouse" action="{{URL::asset('/admin/huxing/edit')}}" method="post" class="form-horizontal"
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
                            <div class="form-group hidden">
                                <label for="type_id" class="col-sm-2 control-label">楼盘id</label>
                                <div class="col-sm-10">
                                    <input type="text" name="house_id" id="house_id" class="form-control"
                                           value="{{$house->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type_id" class="col-sm-2 control-label">产品类型</label>

                                <div class="col-sm-10">
                                    <select id="type_id" name="type_id" class="form-control"
                                            value="">
                                        @foreach($houseTypes as $houseType)
                                            <option value="{{$houseType->id}}">{{$houseType->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">产品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" id="name" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="yongjin_type" class="col-sm-2 control-label">佣金类型</label>
                                <div class="col-sm-10">
                                    <select id="yongjin_type" name="yongjin_type" class="form-control"
                                            value="" onchange="changeYongjinType();">
                                        <option value="0">按固定金额</option>
                                        <option value="1">按千分比分成</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="yongjin_value_text" for="yongjin_value"
                                       class="col-sm-2 control-label">佣金(元)</label>
                                <div class="col-sm-10">
                                    <input id="yongjin_value" name="yongjin_value" type="text" class="form-control"
                                           placeholder="请输入佣金结算额/千分比"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="image" class="col-sm-2 control-label">产品图片</label>

                                <div class="col-sm-10">
                                    <input id="image" name="image" type="text" class="form-control"
                                           placeholder="图片网路链接地址"
                                           value="">
                                </div>
                            </div>
                            <div style="margin-top: 10px;" class="text-center">
                                <div id="container">
                                    <img id="pickfiles"
                                         src="{{URL::asset('/img/upload.png')}}"
                                         style="width: 350px;">
                                </div>
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传350*200尺寸图片
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="size" class="col-sm-2 control-label">面积区间</label>

                                <div class="col-sm-10">
                                    <input id="size_min" name="size_min" type="text" class="form-control"
                                           placeholder="面积区间左侧边界，例如56.6"
                                           value="">
                                    <input id="size_max" name="size_max" type="text"
                                           class="form-control margin-top-10"
                                           placeholder="面积区间右侧边界，例如280"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="benefit" class="col-sm-2 control-label">产品优点</label>
                                <div class="col-sm-10">
                                    <input id="benefit" name="benefit" type="text" class="form-control"
                                           placeholder="请输入产品优点"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="huxing" class="col-sm-2 control-label">户型描述</label>
                                <div class="col-sm-10">
                                    <input id="huxing" name="huxing" type="text" class="form-control"
                                           placeholder="请输入户型描述，例如三室一厅、二室一厅、一室一厅等"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="orientation" class="col-sm-2 control-label">朝向</label>

                                <div class="col-sm-10">
                                    <input id="orientation" name="orientation" type="text" class="form-control"
                                           placeholder="例如南北、西北、东南"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-sm-2 control-label">推荐理由</label>

                                <div class="col-sm-10">
                                    <input id="reason" name="reason" type="text" class="form-control"
                                           placeholder="请输入推荐理由，例如采光效果好、功能齐全等"
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


    <div class="modal fade -m" id="addHouseYongjinModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理产品佣金</h4>
                </div>
                <form id="editHouse" action="{{URL::asset('/admin/huxing/editYongjin')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkYongjinValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="set_id" class="col-sm-2 control-label">id</label>
                                <div class="col-sm-10">
                                    <input id="set_id" name="set_id" type="text" class="form-control"
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
                                <label for="set_yongjin_type" class="col-sm-2 control-label">佣金类型</label>
                                <div class="col-sm-10">
                                    <select id="set_yongjin_type" name="set_yongjin_type" class="form-control"
                                            value="" onchange="changeYongjinType();">
                                        <option value="0">按固定金额</option>
                                        <option value="1">按千分比分成</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label id="set_yongjin_value_text" for="yongjin_value"
                                       class="col-sm-2 control-label">佣金(元)</label>
                                <div class="col-sm-10">
                                    <input id="set_yongjin_value" name="set_yongjin_value" type="text"
                                           class="form-control"
                                           placeholder="请输入佣金结算额/千分比"
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
        var house_id;
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //获取七牛token
            initQNUploader();
            house_id = getQueryString("house_id");
        });
        function GetRequest() {
            var url = location.search; //获取url中"?"符后的字串
            var theRequest = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for (var i = 0; i < strs.length; i++) {
                    theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
                }
            }
            return theRequest;
        }


        //点击新建楼盘
        function clickAdd() {
            //清空模态框
            $("#editHouse")[0].reset();
            $("#house_id").val(house_id);
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            //将佣金设置为可编辑
            $("#yongjin_type").attr("disabled", false);
            $("#yongjin_value").attr("disabled", false);
            $("#addHouseModal").modal('show');
        }

        //点击编辑
        function clickEdit(id) {
            console.log("clickEdit id:" + id);
            getHuxingById("{{URL::asset('')}}", {id: id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#house_id").val(msgObj.house_id);
                    $("#name").val(msgObj.name);
                    $("#type_id").val(msgObj.type_id);
                    $("#yongjin_type").val(msgObj.yongjin_type);
                    $("#yongjin_value").val(msgObj.yongjin_value);
                    $("#size_min").val(msgObj.size_min);
                    $("#size_max").val(msgObj.size_max);
                    $("#huxing").val(msgObj.huxing);
                    $("#image").val(msgObj.image)
                    $("#pickfiles").attr("src", msgObj.image);
                    $("#reason").val(msgObj.reason);
                    $("#orientation").val(msgObj.orientation);
                    $("#benefit").val(msgObj.benefit);
                    //展示modal
                    $("#addHouseModal").modal('show');
                    //将佣金设置为不可编辑
                    $("#yongjin_type").attr("disabled", true);
                    $("#yongjin_value").attr("disabled", true);
                }
            })
        }

        //点击设置佣金
        function clickSetYongjing(id) {
            console.log("clickSetYongjing id:" + id);
            getHuxingById("{{URL::asset('')}}", {id: id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#set_id").val(msgObj.id);
                    $("#set_yongjin_type").val(msgObj.yongjin_type);
                    $("#set_yongjin_value").val(msgObj.yongjin_value);
                    //展示modal
                    $("#addHouseYongjinModal").modal('show');
                }
            })
        }

        //合规校验佣金设置
        function checkYongjinValid() {
            console.log("checkYongjinValid");
            var yongjin_type = $("#set_yongjin_type").val();
            var yongjin_value = parseFloat($("#set_yongjin_value").val());
            if (judgeIsNullStr(yongjin_type) || judgeIsNullStr(yongjin_value)) {
                $("#set_yongjin_value").focus();
                return;
            }
            //如果是固定金额
            if (yongjin_type == 0 && yongjin_value < 100) {
                $("#tipModalBody").html('<p>请确认佣金类型和金额，可能输入存在错误</p>');
                $("#tipModal").modal('show');
                console.log("yongjin set error");
                return false;
            }
            //如果是千分比
            if (yongjin_type == 1 && yongjin_value > 5) {
                $("#tipModalBody").html('<p>请确认佣金类型和金额，可能输入存在错误</p>');
                $("#tipModal").modal('show');
                console.log("yongjin set error");
                return false;
            }
            return true;
        }


        //合规校验
        function checkValid() {
            //合规校验
            var yongjin_type = $("#yongjin_type").val();
            var yongjin_value = parseFloat($("#yongjin_value").val());
            if (judgeIsNullStr(yongjin_type) || judgeIsNullStr(yongjin_value)) {
                $("#yongjin_value").focus();
                return;
            }
            //如果是固定金额
            if (yongjin_type == 0 && yongjin_value < 100) {
                $("#tipModalBody").html('<p>请确认佣金类型和金额，可能输入存在错误</p>');
                $("#tipModal").modal('show');
                return false;
            }
            //如果是千分比
            if (yongjin_type == 1 && yongjin_value > 5) {
                $("#tipModalBody").html('<p>请确认佣金类型和金额，可能输入存在错误</p>');
                $("#tipModal").modal('show');
                return false;
            }
            var image = $("#image").val();
            if (judgeIsNullStr(image)) {
                $("#image").focus();
                return false;
            }
            var size_min = $("#size_min").val();
            if (judgeIsNullStr(size_min)) {
                $("#size_min").focus();
                return false;
            }
            var size_max = $("#size_max").val();
            if (judgeIsNullStr(size_max)) {
                $("#size_max").focus();
                return false;
            }
            var benefit = $("#benefit").val();
            if (judgeIsNullStr(benefit)) {
                $("#benefit").focus();
                return false;
            }
            var reason = $("#reason").val();
            if (judgeIsNullStr(reason)) {
                $("#reason").focus();
                return false;
            }
            var orientation = $("#orientation").val();
            if (judgeIsNullStr(orientation)) {
                $("#orientation").focus();
                return false;
            }
            return true;
        }


        //初始化七牛上传模块
        function initQNUploader() {
            var uploader = Qiniu.uploader({
                runtimes: 'html5,flash,html4',      // 上传模式，依次退化
                browse_button: 'pickfiles',         // 上传选择的点选按钮，必需
                container: 'container',//上传按钮的上级元素ID
                // 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
                // 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
                // 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
                uptoken: "{{$upload_token}}", // uptoken是上传凭证，由其他程序生成
                // uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
                // uptoken_func: function(file){    // 在需要获取uptoken时，该方法会被调用
                //    // do something
                //    return uptoken;
                // },
                get_new_uptoken: false,             // 设置上传文件的时候是否每次都重新获取新的uptoken
                // downtoken_url: '/downtoken',
                // Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
                unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
                // save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
                domain: 'http://twst.isart.me/',     // bucket域名，下载资源时用到，必需
                max_file_size: '100mb',             // 最大文件体积限制
                flash_swf_url: 'path/of/plupload/Moxie.swf',  //引入flash，相对路径
                max_retries: 3,                     // 上传失败最大重试次数
                dragdrop: true,                     // 开启可拖曳上传
                drop_element: 'container',          // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
                chunk_size: '4mb',                  // 分块上传时，每块的体积
                auto_start: true,                   // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
                //x_vars : {
                //    查看自定义变量
                //    'time' : function(up,file) {
                //        var time = (new Date()).getTime();
                // do something with 'time'
                //        return time;
                //    },
                //    'size' : function(up,file) {
                //        var size = file.size;
                // do something with 'size'
                //        return size;
                //    }
                //},
                init: {
                    'FilesAdded': function (up, files) {
                        plupload.each(files, function (file) {
                            // 文件添加进队列后，处理相关的事情
//                                            alert(alert(JSON.stringify(file)));
                        });
                    },
                    'BeforeUpload': function (up, file) {
                        // 每个文件上传前，处理相关的事情
//                        console.log("BeforeUpload up:" + up + " file:" + JSON.stringify(file));
                    },
                    'UploadProgress': function (up, file) {
                        // 每个文件上传时，处理相关的事情
//                        console.log("UploadProgress up:" + up + " file:" + JSON.stringify(file));
                    },
                    'FileUploaded': function (up, file, info) {
                        // 每个文件上传成功后，处理相关的事情
                        // 其中info是文件上传成功后，服务端返回的json，形式如：
                        // {
                        //    "hash": "Fh8xVqod2MQ1mocfI4S4KpRL6D98",
                        //    "key": "gogopher.jpg"
                        //  }
//                        console.log(JSON.stringify(info));
                        var domain = up.getOption('domain');
                        var res = JSON.parse(info);
                        //获取上传成功后的文件的Url
                        var sourceLink = domain + res.key;
                        $("#image").val(sourceLink);
                        $("#pickfiles").attr('src', qiniuUrlTool(sourceLink, "ad"));
//                        console.log($("#pickfiles").attr('src'));
                    },
                    'Error': function (up, err, errTip) {
                        //上传出错时，处理相关的事情
                        console.log(err + errTip);
                    },
                    'UploadComplete': function () {
                        //队列文件处理完毕后，处理相关的事情
                    },
                    'Key': function (up, file) {
                        // 若想在前端对每个文件的key进行个性化处理，可以配置该函数
                        // 该配置必须要在unique_names: false，save_key: false时才生效

                        var key = "";
                        // do something with key here
                        return key
                    }
                }
            });
        }

        //监听更改佣金类型
        function changeYongjinType() {
            //同时控制2个modal
            var set_yongjin_type = $("#set_yongjin_type").val();
            var yongjin_type = $("#yongjin_type").val();

            //如果是固定金额
            if (yongjin_type == "0") {
                $("#yongjin_value_text").text("金额(元)");
            }
            if (set_yongjin_type == "0") {
                $("#set_yongjin_value_text").text("金额(元)");
            }
            //如果是千分比
            if (yongjin_type == "1") {
                $("#yongjin_value_text").text("千分比(‰)");
            }
            if (set_yongjin_type == "1") {
                $("#yongjin_value_text").text("千分比(‰)");
            }
        }


    </script>
@endsection