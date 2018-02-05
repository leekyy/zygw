@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">楼盘管理</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">
                <button type="button" class="btn btn-primary" onclick="clickAdd();">
                    +新建楼盘
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
                    <form action="{{URL::asset('/admin/house/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input id="search_word" name="search_word" type="text" class="form-control"
                                           placeholder="请输入楼盘名称">
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-info btn-block btn-flat" onclick="">
                                        搜索
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </form>
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
                                <th>楼盘名</th>
                                <th>楼盘地址</th>
                                <th>楼盘价格</th>
                                <th>楼盘类型</th>
                                <th>楼盘面积</th>
                                <th>楼盘标签</th>
                                <th>佣金</th>
                                <th>地址</th>
                                <th>价格(元/㎡)</th>
                                <th>类型</th>
                                <th>佣金(元)</th>
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
                                            {{$data->title}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->address}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->price}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->type}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->size}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->label}}
                                        </div>
                                    </td>
                                            @foreach($data->types as $type)
                                                {{$type->name}}
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->yongjin}}
                                        </div>
                                    </td>

                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-success line-height-30">展示</span>
                                        @else
                                            <span class="label label-default line-height-30">隐藏</span>
                                        @endif

                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                              <a href="{{URL::asset('/admin/house/setStatus')}}/{{$data->id}}?opt=0"
                                                 class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                                 data-toggle="tooltip"
                                                 data-placement="top" title="在小程序页面中展示该轮楼盘">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/house/setStatus')}}/{{$data->id}}?opt=1"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="在小程序页面中隐藏该楼盘">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/house/getHouseById')}}/?house_id={{$data->id}}"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看该楼盘下房源">
                                                <i class="fa fa-eye-slash opt-btn-i-size"></i>
                                            </a>
                                             <a href="{{URL::asset('/admin/house/detail')}}/{{$data->id}}?house_id={{$data->id}}"
                                                class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                                data-toggle="tooltip"
                                                data-placement="top" title="查看该楼盘详细信息">
                                                <i class="fa fa-eye opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-success margin-right-10 opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  onclick="clickEdit({{$data->id}})"
                                                  title="编辑该楼盘">
                                                <i class="fa fa-edit opt-btn-i-size"></i>
                                            </span>
                                            <a href="{{URL::asset('/admin/huxing/index')}}?house_id={{$data->id}}"
                                               class="btn btn-social-icon btn-primary margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看该楼盘下房源">
                                                <i class="fa fa-building-o opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/zygw/index')}}?house_id={{$data->id}}"
                                               class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看该楼盘的置业顾问">
                                                <i class="fa fa-black-tie opt-btn-i-size"></i>
                                            </a>
                                            <a href="{{URL::asset('/admin/houseClient/index')}}?house_id={{$data->id}}"
                                               class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看该楼盘的厂商客户">
                                                <i class="fa fa-male opt-btn-i-size"></i>
                                            </a>
                                            <span class="btn btn-social-icon btn-danger opt-btn-size"
                                                  data-toggle="tooltip"
                                                  data-placement="top"
                                                  title="查看统计信息"
                                                  onclick="clickStmt({{$data->id}})">
                                                <i class="fa fa-bar-chart opt-btn-i-size"></i>
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
                    <h4 class="modal-title">管理楼盘</h4>
                </div>
                <form id="editHouse" action="{{URL::asset('/admin/house/edit')}}" method="post" class="form-horizontal"
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
                                <label for="title" class="col-sm-2 control-label">楼盘名</label>

                                <div class="col-sm-10">
                                    <input id="title" name="title" type="text" class="form-control"
                                           placeholder="楼盘名"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="image" class="col-sm-2 control-label">楼盘图片</label>

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
                                <label for="address" class="col-sm-2 control-label">楼盘地址</label>

                                <div class="col-sm-10">
                                    <input id="address" name="address" type="text" class="form-control"
                                           placeholder="楼盘地址"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="price" class="col-sm-2 control-label">价格/元</label>

                                <div class="col-sm-10">
                                    <input id="price" name="price" type="text" class="form-control"
                                           placeholder="请输入楼盘价格"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="size" class="col-sm-2 control-label">面积/㎡</label>

                                <div class="col-sm-10">
                                    <input id="size" name="size" type="text" class="form-control"
                                           placeholder="请输入楼盘面积"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label">楼盘类型</label>

                                <div class="col-sm-10">
                                    <div class="row">
                                        @foreach($houseTypes as $houseType)
                                            <div class="col-xs-4">
                                                <input type="checkbox" name="type_ids[]" id="type_id{{$houseType->id}}"
                                                       value="{{$houseType->id}}"
                                                       class="minimal">
                                                <span
                                                        class="margin-left-10">{{$houseType->name}}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="label" class="col-sm-2 control-label">楼盘标签</label>

                                <div class="col-sm-10">
                                    <div class="row">
                                        @foreach($houseLabels as $houseLabel)
                                            <div class="col-xs-4">
                                                <input type="checkbox" name="label_ids[]"
                                                       id="label_id{{$houseLabel->id}}"
                                                       value="{{$houseLabel->id}}"
                                                       class="minimal">
                                                <span
                                                        class="margin-left-10">{{$houseLabel->name}}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                                <div class="col-sm-10">
                                    <input id="period" name="period" type="text" class="form-control"
                                           placeholder="请输入结算周期"
                                           value="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="yongjin" class="col-sm-2 control-label">佣金/元</label>

                                <div class="col-sm-10">
                                    <input id="yongjin" name="yongjin" type="text" class="form-control"
                                           placeholder="请输入该楼盘佣金分成"
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
@endsection

@section('script')
    <script type="application/javascript">


        //优化icheck展示
        function setICheck() {
            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            })
        }

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            //获取七牛token
            initQNUploader();
            setICheck();
        });


        //点击新建楼盘
        function clickAdd() {1
            //清空模态框
            $("#editHouse")[0].reset();
            $("#admin_id").val("{{$admin->id}}");
            $("#pickfiles").attr("src", '{{URL::asset('/img/upload.png')}}');
            $("#addHouseModal").modal('show');
        }

        //点击编辑
        function clickEdit(house_id) {
            console.log("clickEdit house_id:" + house_id);
            getHouseById("{{URL::asset('')}}", {id: house_id, _token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    //对象配置
                    $("#id").val(msgObj.id);
                    $("#title").val(msgObj.title);
                    $("#address").val(msgObj.address);
                    $("#image").val(msgObj.image)
                    $("#pickfiles").attr("src", msgObj.image);
                    $("#price").val(msgObj.price);
                    $("#size").val(msgObj.size);
                    //设置type
                    var type_arr = [];
                    if (!judgeIsNullStr(msgObj.type_ids)) {
                        type_arr = msgObj.type_ids.split(',');
                    }
                    console.log("type_arr:" + JSON.stringify(type_arr));
                    for (var i = 0; i < type_arr.length; i++) {
                        $("#type_id" + type_arr[i]).attr('checked', 'true');
                    }
                    //设置label
                    var label_arr = [];
                    if (!judgeIsNullStr(msgObj.label_ids)) {
                        label_arr = msgObj.label_ids.split(',');
                    }
                    for (var i = 0; i < label_arr.length; i++) {
                        $("#label_id" + label_arr[i]).attr('checked', 'true');
                    }
                    $("#type").val(msgObj.type);
                    $("#label").val(msgObj.label);
                    $("#yongjin").val(msgObj.yongjin);
                    //设置icheck
                    setICheck();
                    //展示modal
                    $("#addHouseModal").modal('show');
                }
            })
        }


        //合规校验
        function checkValid() {
            //合规校验
            var title = $("#title").val();
            if (judgeIsNullStr(title)) {
                $("#title").focus();
                return false;
            }
            var price = $("#price").val();
            if (judgeIsNullStr(price)) {
                $("#price").focus();
                return false;
            }
            var image = $("#image").val();
            if (judgeIsNullStr(image)) {
                $("#image").focus();
                return false;
            }

            var size = $("#size").val();
            if (judgeIsNullStr(size)) {
                $("#size").focus();
                return false;
            }
            var address = $("#address").val();
            if (judgeIsNullStr(address)) {
                $("#address").focus();
                return false;
            }
<<<<<<< HEAD
            var label = $("#label").val();
            if (judgeIsNullStr(label)) {
                $("#label").focus();
=======

            var period = $("#period").val();
            if (judgeIsNullStr(period)) {
                $("#period").focus();
>>>>>>> 6d3a162ad68981c2c1a34fa6d63ec49d1d1a5179
                return false;
            }
            var yongjin = $("#yongjin").val();
            if (judgeIsNullStr(yongjin)) {
                $("#yongjin").focus();
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