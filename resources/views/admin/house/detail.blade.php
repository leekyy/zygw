@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">楼盘详细管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建楼盘详细信息
                </button>
            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">

        {{--条件搜索--}}
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="">
                    <!-- form start -->

                </div>
                <!-- /.box -->
            </div>
        </div>
        <!--列表-->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                {{--<th>楼盘图片</th>--}}
                                {{--<th>楼盘名</th>--}}
                                <th>开盘时间</th>
                                <th>交盘时间</th>
                                <th>开发商</th>
                                <th>物业</th>
                                <th>面积</th>
                                <th>总户数</th>
                                <th>容积率</th>
                                <th>绿化率</th>
                                <th>车位数</th>
                                <th>车位比</th>
                                <th>均价</th>
                                <th>物业费</th>
                                <th>建筑面积</th>
                                <th>装修状态</th>
                                <th>产权年限</th>
                                <th>商业配套</th>
                                <th>教育配套</th>
                                <th>交通配套</th>
                                <th>环境配套</th>
                                {{--<th>状态</th>--}}
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

                                    {{--<td>--}}
                                        {{--<img src="{{ $data->house->image ? $data->image.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"--}}
                                             {{--class="img-rect-30 radius-5">--}}
                                    {{--</td>--}}
                                    {{--<td>--}}
                                        {{--<div class="line-height-30">--}}
                                            {{--{{$data->title}}--}}
                                        {{--</div>--}}
                                    {{--</td>--}}
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->kaipantime}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->jiaopantime}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->developer}}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="line-height-30">
                                            {{$data->property}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->size}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->households}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->plotratio}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->green}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->park}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->parkper}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->price}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->propertyfee}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->buildtype}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->decorate}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->years}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->shangye}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->jiaoyu}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->jiaotong}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->huanjing}}
                                        </div>
                                    </td>

                                    {{--<td>--}}
                                        {{--@if($data->status === '0')--}}
                                            {{--<span class="label label-success line-height-30">展示</span>--}}
                                        {{--@else--}}
                                            {{--<span class="label label-default line-height-30">隐藏</span>--}}
                                        {{--@endif--}}

                                    {{--</td>--}}
                                    <td class="opt-th-width-m">
                                        <span class="line-height-30">

                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  onclick="clickEdit({{$data->id}})"
                                                  title="编辑楼盘参数详细信息">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="删除详细信息"
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
                    <h4 class="modal-title">管理楼盘详细信息</h4>
                </div>
                <form id="editHouse" action="{{URL::asset('/admin/detail/edit')}}" method="post" class="form-horizontal"
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
                                <label for="type_id" class="col-sm-2 control-label">楼盘id</label>
                                <div class="col-sm-10">
                                    <input type="text" name="house_id" id="house_id" class="form-control"
                                           value="{{$house->id}}">
                                </div>
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label for="image" class="col-sm-2 control-label">楼盘图片</label>--}}

                                {{--<div class="col-sm-10">--}}
                                    {{--<input id="image" name="image" type="text" class="form-control"--}}
                                           {{--placeholder="图片网路链接地址"--}}
                                           {{--value="">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="form-group">--}}
                                {{--<label for="title" class="col-sm-2 control-label">楼盘名</label>--}}

                                {{--<div class="col-sm-10">--}}
                                    {{--<input id="title" name="title" type="text" class="form-control"--}}
                                           {{--placeholder="楼盘名"--}}
                                           {{--value="">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="form-group">
                                <label for="kaipantime" class="col-sm-2 control-label">开盘时间</label>

                                <div class="col-sm-10">
                                    <input id="kaipantime" name="kaipantime" type="text" class="form-control"
                                           placeholder="开盘时间"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jiaopantime" class="col-sm-2 control-label">交盘时间</label>

                                <div class="col-sm-10">
                                    <input id="jiaopantime" name="jiaopantime" type="text" class="form-control"
                                           placeholder="交盘时间"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="developer" class="col-sm-2 control-label">开发商</label>

                                <div class="col-sm-10">
                                    <input id="developer" name="developer" type="text" class="form-control"
                                           placeholder="开发商"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="property" class="col-sm-2 control-label">物业</label>

                                <div class="col-sm-10">
                                    <input id="property" name="property" type="text" class="form-control"
                                           placeholder="物业"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="size" class="col-sm-2 control-label">面积</label>

                                <div class="col-sm-10">
                                    <input id="size" name="size" type="text" class="form-control"
                                           placeholder="面积"
                                           value="">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="households" class="col-sm-2 control-label">总户数</label>

                                <div class="col-sm-10">
                                    <input id="households" name="households" type="text" class="form-control"
                                           placeholder="总户数"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plotratio" class="col-sm-2 control-label">容积率</label>

                                <div class="col-sm-10">
                                    <input id="plotratio" name="plotratio" type="text" class="form-control"
                                           placeholder="容积率"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="green" class="col-sm-2 control-label">绿化率</label>

                                <div class="col-sm-10">
                                    <input id="green" name="green" type="text" class="form-control"
                                           placeholder="绿化率"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="park" class="col-sm-2 control-label">车位数</label>

                                <div class="col-sm-10">
                                    <input id="park" name="park" type="text" class="form-control"
                                           placeholder="车位数"
                                           value="">
                                </div>
                            </div> <div class="form-group">
                                <label for="parkper" class="col-sm-2 control-label">车位比</label>

                                <div class="col-sm-10">
                                    <input id="parkper" name="parkper" type="text" class="form-control"
                                           placeholder="车位比"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-sm-2 control-label">均价</label>

                                <div class="col-sm-10">
                                    <input id="price" name="price" type="text" class="form-control"
                                           placeholder="均价"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="propertyfee" class="col-sm-2 control-label">物业费</label>

                                <div class="col-sm-10">
                                    <input id="propertyfee" name="propertyfee" type="text" class="form-control"
                                           placeholder="物业费"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="buildtype" class="col-sm-2 control-label">建筑类型</label>

                                <div class="col-sm-10">
                                    <input id="buildtype" name="buildtype" type="text" class="form-control"
                                           placeholder="建筑类型"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="decorate" class="col-sm-2 control-label">装修状态</label>

                                <div class="col-sm-10">
                                    <input id="decorate" name="decorate" type="text" class="form-control"
                                           placeholder="装修状态"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="years" class="col-sm-2 control-label">产权年限</label>

                                <div class="col-sm-10">
                                    <input id="years" name="years" type="text" class="form-control"
                                           placeholder="产权年限"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="shangye" class="col-sm-2 control-label">商业配套</label>

                                <div class="col-sm-10">
                                    <input id="shangye" name="shangye" type="text" class="form-control"
                                           placeholder="商业配套"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jiaoyu" class="col-sm-2 control-label">教育配套</label>

                                <div class="col-sm-10">
                                    <input id="jiaoyu" name="jiaoyu" type="text" class="form-control"
                                           placeholder="教育配套"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="jiaotong" class="col-sm-2 control-label">交通配套</label>

                                <div class="col-sm-10">
                                    <input id="jiaotong" name="jiaotong" type="text" class="form-control"
                                           placeholder="交通配套"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="huanjing" class="col-sm-2 control-label">环境配套</label>

                                <div class="col-sm-10">
                                    <input id="huanjing" name="huanjing" type="text" class="form-control"
                                           placeholder="环境配套"
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
                    <p>您确认要删除该楼盘参数吗？</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="url"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button id="delConfrimModal_confirm_btn" data-value="" onclick="delAdmin();"
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
        var house_id ;
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //获取七牛token
            initQNUploader();
            house_id=GetRequest().house_id;
        });
        function GetRequest() {
            var url = location.search; //获取url中"?"符后的字串
            var theRequest = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for(var i = 0; i < strs.length; i ++) {
                    theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
                }
            }
            return theRequest;
        }

        //点击删除房源
        function clickDel(admin_id) {
            console.log("clickDel admin_id:" + admin_id);
            //为删除按钮赋值
            $("#delConfrimModal_confirm_btn").attr("data-value", admin_id);
            $("#delConfrimModal").modal('show');
        }

        //删除房源
        function delAdmin() {
            var admin_id = $("#delConfrimModal_confirm_btn").attr("data-value");
            console.log("delAdmin admin_id:" + admin_id);
            //进行tr隐藏
            $("#tr_" + admin_id).fadeOut();
            //进行页面跳转
            //var huxing =window.location.search
            window.location.href = "{{URL::asset('/admin/detail/del')}}/" + admin_id+"/?house_id="+house_id;
        }

        //点击新建楼盘参数
        function clickAdd() {
            //清空模态框
            $("#editHouse")[0].reset();
            $("#house_id").val(house_id);
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addHouseModal").modal('show');
        }

        //点击编辑
        function clickEdit(id) {
            console.log("clickEdit id:" + id);
            getHouseDetailById("{{URL::asset('')}}", {id: id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#house_id").val(msgObj.house_id);
//                    $("#image").val(msgObj.image);
//                    $("#title").val(msgObj.title);
                    $("#kaipantime").val(msgObj.kaipantime);
                    $("#jiaopantime").val(msgObj.jiaopantime);
                    $("#developer").val(msgObj.developer)
                    $("#property").attr("src", msgObj.property);
                    $("#size").val(msgObj.size);
                    $("#households").val(msgObj.households);
                    $("#plotratio").val(msgObj.plotratio);
                    $("#green").val(msgObj.green);
                    $("#park").attr("src", msgObj.park);
                    $("#parkper").val(msgObj.parkper);
                    $("#price").val(msgObj.price);
                    $("#propertyfee").val(msgObj.propertyfee);
                    $("#buildtype").val(msgObj.buildtype);
                    $("#decorate").attr("src", msgObj.decorate);
                    $("#years").val(msgObj.years);
                    $("#shangye").val(msgObj.shangye);
                    $("#jiaoyu").val(msgObj.jiaoyu);
                    $("#jiaotong").val(msgObj.jiaotong);

                    //展示modal
                    $("#addHouseModal").modal('show');
                }
            })
        }


        //合规校验
        function checkValid() {
            //合规校验
            var kaipantime = $("#kaipantime").val();
            if (judgeIsNullStr(kaipantime)) {
                $("#kaipantime").focus();
                return false;
            }
            var jiaopantime = $("#jiaopantime").val();
            if (judgeIsNullStr(jiaopantime)) {
                $("#jiaopantime").focus();
                return false;
            }
            var developer = $("#developer").val();
            if (judgeIsNullStr(developer)) {
                $("#developer").focus();
                return false;
            }
            var property = $("#property").val();
            if (judgeIsNullStr(property)) {
                $("#property").focus();
                return false;
            }

            var size = $("#size").val();
            if (judgeIsNullStr(size)) {
                $("#size").focus();
                return false;
            }
            var households = $("#households").val();
            if (judgeIsNullStr(households)) {
                $("#households").focus();
                return false;
            }
            var plotratio = $("#plotratio").val();
            if (judgeIsNullStr(plotratio)) {
                $("#plotratio").focus();
                return false;
            }
            var green = $("#green").val();
            if (judgeIsNullStr(green)) {
                $("#green").focus();
                return false;
            }
            var park = $("#park").val();
            if (judgeIsNullStr(park)) {
                $("#park").focus();
                return false;
            }
            var parkper = $("#parkper").val();
            if (judgeIsNullStr(parkper)) {
                $("#parkper").focus();
                return false;
            }
            var price = $("#price").val();
            if (judgeIsNullStr(price)) {
                $("#price").focus();
                return false;
            }
            var propertyfee = $("#propertyfee").val();
            if (judgeIsNullStr(propertyfee)) {
                $("#propertyfee").focus();
                return false;
            }
            var decorate = $("#decorate").val();
            if (judgeIsNullStr(decorate)) {
                $("#decorate").focus();
                return false;
            }
            var buildtype = $("#buildtype").val();
            if (judgeIsNullStr(buildtype)) {
                $("#buildtype").focus();
                return false;
            }
            var years = $("#years").val();
            if (judgeIsNullStr(years)) {
                $("#years").focus();
                return false;
            }
            var shangye = $("#shangye").val();
            if (judgeIsNullStr(shangye)) {
                $("#shangye").focus();
                return false;
            }
            var jiaoyu = $("#jiaoyu").val();
            if (judgeIsNullStr(jiaoyu)) {
                $("#jiaoyu").focus();
                return false;
            }
            var jiaotong = $("#jiaotong").val();
            if (judgeIsNullStr(jiaotong)) {
                $("#jiaotong").focus();
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







    </script>
@endsection