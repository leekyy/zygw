@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">中介管理</li>
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
                                <th>到访数排名（个）</th>
                                <th>头像</th>
                                <th>微信昵称</th>
                                <th>姓名</th>
                                <th>电话</th>
                                <th>积分</th>
                                <th>注册时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($datas as $data)
                                <tr>
                                    <td>
                                        <div class="line-height-30 text-center">
                                            {{$data->num}}
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{ $data->user->avatar ? $data->user->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim' : URL::asset('/img/default_headicon.png')}}"
                                             class="img-rect-30 radius-5">
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            <a href="{{URL::asset('/admin/zhongjie/stmt')}}?id={{$data->user->id}}"
                                               class="text-primary"
                                               data-toggle="tooltip"
                                               data-placement="top" title="查看统计信息">
                                                {{$data->user->nick_name}}
                                            </a>
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
                                            {{$data->user->jifen}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->user->created_at}}
                                        </div>
                                    </td>
                                    <td>
                                        @if($data->user->status === '0')
                                            <span class="label label-info line-height-30">冻结</span>
                                        @endif
                                        @if($data->user->status === '1')
                                            <span class="label label-success line-height-30">正常</span>
                                        @endif
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