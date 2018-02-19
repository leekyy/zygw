@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">案场负责人明细</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">

            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">
        {{--案场负责人基本信息--}}
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
                                        案场负责人
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
            <form action="{{URL::asset('/admin/acfzr/stmt')}}" method="get"
                  class="form-horizontal">
                {{csrf_field()}}
                <input id="id" name="id" type="text" class="form-control hidden"
                       placeholder="案场负责人id" value="{{$user->id}}">
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

        {{--案场负责人的报备列表--}}
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
            $('[data-toggle="tooltip"]').tooltip()
        });


    </script>
@endsection