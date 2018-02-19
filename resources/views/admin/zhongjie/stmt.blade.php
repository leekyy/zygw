@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">中介明细</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">

            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">
        {{--中介基本信息--}}
        <div class="white-bg">
            <div style="padding: 15px;padding-bottom: 0px;">
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td rowspan="3">
                                    <img src="{{ $user->avatar ? $user->avatar : URL::asset('/img/default_headicon.png')}}"
                                         style="width: 80px;height: 80px;">
                                </td>
                                <td>微信昵称</td>
                                <td>
                                    {{$user->nick_name}}
                                </td>
                                <td>电话</td>
                                <td>
                                    {{$user->phonenum}}
                                </td>
                                <td>身份证</td>
                                <td>{{$user->cardID}}</td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{{$user->real_name}}</td>
                                <td>性别</td>
                                <td>
                                    @if($user->gender == "0")
                                        保密
                                    @endif
                                    @if($user->gender == "1")
                                        男
                                    @endif
                                    @if($user->gender == "2")
                                        女
                                    @endif
                                </td>
                                <td>角色</td>
                                <td>
                                    @if($user->role == "0")
                                        中介
                                    @endif
                                    @if($user->role == "1")
                                        中介
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>省份</td>
                                <td>{{$user->province}}</td>
                                <td>城市</td>
                                <td>{{$user->city}}</td>
                                <td>积分</td>
                                <td>{{$user->jifen}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{--基础统计--}}
        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="font-size-14 grey-bg">
                    <div style="padding: 10px;padding-bottom: 0px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td>全部记录数</td>
                                <td>{{$stmt['all_nums']}}</td>
                                <td>已报备数</td>
                                <td>{{$stmt['baobei_status0']}}</td>
                                <td>已到访数</td>
                                <td>{{$stmt['baobei_status1']}}</td>
                                <td>已成交数</td>
                                <td>{{$stmt['baobei_status2']}}</td>
                            </tr>
                            <tr>
                                <td>已签约数</td>
                                <td>{{$stmt['baobei_status3']}}</td>
                                <td>全款到账数</td>
                                <td>{{$stmt['baobei_status4']}}</td>
                                <td>可结算数</td>
                                <td> {{$stmt['can_jiesuan_status1']}}</td>
                                <td>已结算数</td>
                                <td>{{$stmt['pay_zhongjie_status1']}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="margin-top-10">
            <form action="{{URL::asset('/admin/zhongjie/stmt')}}" method="get"
                  class="form-horizontal">
                {{csrf_field()}}
                <input id="id" name="id" type="text" class="form-control hidden"
                       placeholder="中介id" value="{{$user->id}}">
                <div class="row">
                    <div class="col-xs-4">
                        <input id="trade_no" name="trade_no" type="text" class="form-control"
                               placeholder="根据报备流水搜索" value="">
                    </div>
                    <div class="col-xs-2">
                        <select id="baobei_status" name="baobei_status" class="form-control"
                                value="">
                            <option value="">全部状态</option>
                            <option value="0">已报备</option>
                            <option value="1">已到访</option>
                            <option value="2">已成交</option>
                            <option value="3">已签约</option>
                            <option value="4">全款到账</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <select id="can_jiesuan_status" name="can_jiesuan_status" class="form-control"
                                value="">
                            <option value="">全部状态</option>
                            <option value="0">不可结算</option>
                            <option value="1">可以结算</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <select id="pay_zhongjie_status" name="pay_zhongjie_status" class="form-control"
                                value="">
                            <option value="">全部状态</option>
                            <option value="0">待结算</option>
                            <option value="1">已结算</option>
                        </select>
                    </div>
                    <div class="col-xs-2">
                        <button type="submit" class="btn btn-info btn-block btn-flat" onclick="">
                            搜索
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{--中介的报备列表--}}
        <div class="row margin-top-10">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>报备流水</th>
                                <th>客户姓名</th>
                                <th>客户电话</th>
                                <th>意向楼盘</th>
                                <th>分润佣金</th>
                                <th>报备状态</th>
                                <th>可结算</th>
                                <th>已支付</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr id="tr_{{$data->id}}">
                                    <td>
                                        <div class="line-height-30">
                                            <a href="{{URL::asset('/admin/baobei/info')}}?id={{$data->id}}"
                                               target="_blank">{{$data->trade_no}}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->client->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->client->phonenum}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->house->title}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->yongjin}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            @if($data->baobei_status=="0")
                                                已报备
                                            @endif
                                            @if($data->baobei_status=="1")
                                                已到访
                                            @endif
                                            @if($data->baobei_status=="2")
                                                已成交
                                            @endif
                                            @if($data->baobei_status=="3")
                                                已签约
                                            @endif
                                            @if($data->baobei_status=="4")
                                                全款到账
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="line-height-30">
                                            @if($data->can_jiesuan_status === "0")
                                                不可结算
                                            @else
                                                可以结算
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            @if($data->pay_zhongjie_status === "0")
                                                待结算
                                            @else
                                                已结算
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            @if($data->pay_zhongjie_status=='0' && $data->can_jiesuan_status=='1' && $data->yongjin > 0)
                                                <span class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                                      data-toggle="tooltip"
                                                      onclick="clickPayZhongjie({{$data->id}})"
                                                      data-placement="top" title="进行佣金结算">
                                                <i class="fa fa-check opt-btn-i-size"></i>
                                            </span>
                                            @endif
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
    <div class="modal fade -m" id="payZhongjieModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">中介佣金支付</h4>
                </div>
                <form id="editHouse" action="{{URL::asset('/admin/zhongjie/payYongjin')}}" method="post"
                      class="form-horizontal"
                      onsubmit="return checkValid();">
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-2 control-label">报备id</label>
                                <div class="col-sm-10">
                                    <input id="baobei_id" name="baobei_id" type="text" class="form-control"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group hidden">
                                <label for="admin_id" class="col-sm-2 control-label">兑付人id</label>
                                <div class="col-sm-10">
                                    <input id="admin_id" name="admin_id" type="text" class="form-control"
                                           value="{{$admin->id}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">兑付人</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control"
                                           value="{{$admin->name}}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pay_zhongjie_attach" class="col-sm-2 control-label">支付凭证</label>

                                <div class="col-sm-10">
                                    <input id="pay_zhongjie_attach" name="pay_zhongjie_attach" type="text"
                                           class="form-control"
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

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            //获取七牛token
            initQNUploader();
        });

        //合规校验
        function checkValid() {
            //合规校验
            var pay_zhongjie_attach = $("#pay_zhongjie_attach").val();
            if (judgeIsNullStr(pay_zhongjie_attach)) {
                $("#pay_zhongjie_attach").focus();
                return false;
            }
            return true;
        }

        //点击商品兑付
        function clickPayZhongjie(baobei_id) {
            console.log("clickPayZhongjie baobei_id:" + baobei_id);
            $("#baobei_id").val(baobei_id);
            //展示modal
            $("#payZhongjieModal").modal('show');
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
                        $("#pay_zhongjie_attach").val(sourceLink);
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