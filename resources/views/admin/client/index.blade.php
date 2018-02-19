@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">客户管理</li>
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
                    <form action="{{URL::asset('/admin/client/search')}}" method="post" class="form-horizontal">
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
                                <th>姓名</th>
                                <th>电话</th>
                                <th>报备中介</th>
                                <th>报备次数</th>
                                <th>首次报备时间</th>
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
                                            {{$data->name}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->phonenum}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            @if($data->user!=null)
                                                <a href="{{URL::asset('/admin/zhongjie/stmt')}}?id={{$data->user->id}}"
                                                   target="_blank">
                                                    {{isset($data->user)?$data->user->real_name:'--'}}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->baobei_times}}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="line-height-30">
                                            {{$data->created_at}}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                            <a href="{{URL::asset('/admin/client/stmt')}}?id={{$data->id}}"
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