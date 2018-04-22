@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">户型管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建户型
                </button>
            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">

        {{--产品基本信息--}}
        <div class="white-bg">
            <div style="padding: 15px;">
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td rowspan="3">
                                    <img src="{{ $huxing->image ? $huxing->image.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/upload.png')}}"
                                         style="width: 80px;height: 80px;">
                                </td>
                                <td>产品</td>
                                <td>
                                    {{ $huxing->name }}
                                </td>
                                <td>类型</td>
                                <td>
                                    {{$huxing->type->name}}
                                </td>
                                <td>户型</td>
                                <td>{{$huxing->huxing}}</td>
                            </tr>
                            <tr>
                                <td>面积区间</td>
                                <td>{{$huxing->size_min}}㎡</td>
                                <td>面积区间</td>
                                <td>{{$huxing->size_max}}㎡</td>
                                <td></td>
                                <td>--</td>
                            </tr>
                            <tr>
                                <td>佣金类型</td>
                                <td>
                                    {{$huxing->yongjin_type=='0'?'按固定金额':'按成交比例'}}
                                </td>
                                <td>佣金制</td>
                                <td>
                                    {{$huxing->yongjin_value}}{{$huxing->yongjin_type=='0'?'元':'‰'}}
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
                                <th>户型</th>
                                <th>面积</th>
                                <th>朝向</th>
                                <th>推荐理由</th>
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
                                        <img src="{{ $data->image ? $data->image.'?imageView2/1/w/400/h/300/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             style="width: 40px;height: 30px;">
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->size}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->orientation}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->reason}}
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
                                              <a href="{{URL::asset('/admin/huxingStyle/setStatus')}}/{{$data->id}}?opt=1"
                                                 class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                                 data-toggle="tooltip"
                                                 data-placement="top" title="在小程序页面中展示该户型">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/huxingStyle/setStatus')}}/{{$data->id}}?opt=0"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中隐藏该户型">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>

                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  onclick="clickEdit({{$data->id}})"
                                                  title="编辑该户型">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/huxingStyle/del')}}/{{$data->id}}"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="删除该户型">
                                                <i class="fa fa-trash-o opt-btn-i-size"></i>
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



    {{--添加户型管理--}}
    <div class="modal fade -m" id="addHuxingStyleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content message_align">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                    <h4 class="modal-title">管理户型样式</h4>
                </div>
                <form id="editHuxingStyle" action="{{URL::asset('/admin/huxingStyle/edit')}}" method="post"
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
                            <div class="form-group hidden">
                                <label for="admin_id" class="col-sm-2 control-label">户型id</label>
                                <div class="col-sm-10">
                                    <input id="huxing_id" name="huxing_id" type="text" class="form-control"
                                           value="{{$huxing->id}}">
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
                                <label for="title" class="col-sm-2 control-label">户型</label>

                                <div class="col-sm-10">
                                    <input id="name" name="name" type="text" class="form-control"
                                           placeholder="户型"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="seq" class="col-sm-2 control-label">展示顺序</label>

                                <div class="col-sm-10">
                                    <input id="seq" name="seq" type="number" class="form-control"
                                           placeholder="值越大越靠前"
                                           value="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="image" class="col-sm-2 control-label">户型图片</label>

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
                                <div style="font-size: 12px;margin-top: 10px;" class="text-gray">*请上传600*400尺寸图片
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="size" class="col-sm-2 control-label">面积</label>

                                <div class="col-sm-10">
                                    <input id="size" name="size" type="text" class="form-control"
                                           placeholder="户型面积"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="orientation" class="col-sm-2 control-label">朝向</label>
                                <div class="col-sm-10">
                                    <input id="orientation" name="orientation" type="text" class="form-control"
                                           placeholder="户型朝向"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="reason" class="col-sm-2 control-label">推荐理由</label>

                                <div class="col-sm-10">
                                    <input id="reason" name="reason" type="text" class="form-control"
                                           placeholder="推荐理由"
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
        var huxing_id;
        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //获取七牛token
            initQNUploader();
        });


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

        //点击新建楼盘
        function clickAdd() {
            //清空模态框
            $("#editHuxingStyle")[0].reset();
            $("#admin_id").val("{{$admin->id}}");
            $("#huxing_id").val("{{$huxing->id}}");
            $("#seq").val(0);
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addHuxingStyleModal").modal('show');
        }


        //点击编辑
        function clickEdit(huxingStyle_id) {
            console.log("clickEdit huxingStyle_id:" + huxingStyle_id);
            getHuxingStyleById("{{URL::asset('')}}", {
                id: huxingStyle_id,
                _token: "{{ csrf_token() }}"
            }, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#name").val(msgObj.name);
                    $("#seq").val(msgObj.seq);
                    $("#image").val(msgObj.image);
                    $("#pickfiles").attr("src", msgObj.image);
                    $("#size").val(msgObj.size);
                    $("#orientation").val(msgObj.orientation);
                    $("#reason").val(msgObj.reason);
                    //展示modal
                    $("#addHuxingStyleModal").modal('show');
                }
            })
        }


        //合规校验
        function checkValid() {
            //合规校验
            var name = $("#name").val();
            if (judgeIsNullStr(name)) {
                $("#name").name();
                console.log("name is error");
                return false;
            }
            var image = $("#image").val();
            if (judgeIsNullStr(image)) {
                $("#image").focus();
                console.log("image is error");
                return false;
            }
            var seq = $("#seq").val();
            if (judgeIsNullStr(seq)) {
                $("#seq").focus();
                console.log("seq is error");
                return false;
            }
            var size = $("#size").val();
            if (judgeIsNullStr(size)) {
                $("#size").focus();
                console.log("size is error");
                return false;
            }
            var orientation = $("#orientation").val();
            if (judgeIsNullStr(orientation)) {
                $("#orientation").focus();
                console.log("orientation is error");
                return false;
            }
            var reason = $("#reason").val();
            if (judgeIsNullStr(reason)) {
                $("#reason").focus();
                console.log("reason is error");
                return false;
            }
            return true;
        }


    </script>
@endsection