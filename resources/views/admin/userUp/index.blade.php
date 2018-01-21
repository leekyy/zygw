@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">申请审核管理</li>
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
                    <form action="{{URL::asset('/admin/userUp/search')}}" method="post" class="form-horizontal">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <select id="search_status" name="search_status" class="form-control">
                                        <option value="0">未审核</option>
                                        <option value="1">审核通过</option>
                                        <option value="2">审核驳回</option>
                                        <option value="">全部申请</option>
                                    </select>
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
                                <th>申请人</th>
                                <th>电话</th>
                                <th>申请楼盘</th>
                                <th>时间</th>
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
                                            {{isset($data->house) ? $data->house->title : "--"}}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="line-height-30">{{isset($data->sh_time)?$data->sh_time:"--"}}</span>
                                    </td>
                                    <td>
                                        {{isset($data->admin) ? $data->admin->name : "--"}}
                                    </td>
                                    <td>
                                        @if($data->status === '0')
                                            <span class="label label-info line-height-30">待审核</span>
                                        @endif
                                        @if($data->status === '1')
                                            <span class="label label-success line-height-30">审核通过</span>
                                        @endif
                                        @if($data->status === '2')
                                            <span class="label label-default line-height-30">审核驳回</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="line-height-30">
                                          <a href="{{URL::asset('/admin/userUp/setStatus')}}/{{$data->id}}?status=1&admin_id={{$admin->id}}"
                                             class="btn btn-social-icon btn-info margin-right-10 opt-btn-size"
                                             data-toggle="tooltip"
                                             data-placement="top" title="审核通过">
                                            <i class="fa fa-check opt-btn-i-size"></i>
                                          </a>
                                          <a href="{{URL::asset('/admin/userUp/setStatus')}}/{{$data->id}}?status=2&admin_id={{$admin->id}}"
                                             class="btn btn-social-icon btn-warning margin-right-10 opt-btn-size"
                                             data-toggle="tooltip"
                                             data-placement="top" title="审核驳回">
                                            <i class="fa fa-close opt-btn-i-size"></i>
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