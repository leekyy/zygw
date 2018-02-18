@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">案场负责人管理</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{--条件搜索--}}
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="">
                    <!-- form start -->
                    <form action="{{URL::asset('/admin/acfzr/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <input id="search_word" name="search_word" type="text" class="form-control"
                                           placeholder="根据手机号码搜索"
                                           value="">
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
                                <th>头像</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>积分</th>
                                <th>注册时间</th>
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
                                        <img src="{{ $data->avatar ? $data->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->real_name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->phonenum}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->jifen}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->created_at}}
                                        </div>
                                    </td>
                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-info line-height-30">冻结</span>
                                        @endif
                                        @if($data->status === '1')
                                            <span class="label label-success line-height-30">正常</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                          <a href="{{URL::asset('/admin/acfzr/setStatus')}}/{{$data->id}}?status=1"
                                             class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                             data-toggle="tooltip"
                                             data-placement="top" title="解冻">
                                            <i class="fa fa-check opt-btn-i-size"></i>
                                          </a>
                                          <a href="{{URL::asset('/admin/acfzr/setStatus')}}/{{$data->id}}?status=0"
                                             class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                             data-toggle="tooltip"
                                             data-placement="top" title="冻结">
                                            <i class="fa fa-close opt-btn-i-size"></i>
                                          </a>
                                            <a href="{{URL::asset('/admin/acfzr/smst')}}?id={{$data->id}}"
                                               class="btn btn-social-icon btn-danger opt-btn-size"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看统计信息">
                                            <i class="fa fa-bar-chart opt-btn-i-size"></i>
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
                {!! $datas->links() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="application/javascript">

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()

        });
    </script>
@endsection