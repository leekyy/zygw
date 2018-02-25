@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">报备单明细</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">

            </div>
        </div>
    </section>
    {{--<div id = 'upload_token' style="display: none;">{{$upload_token}}</div>--}}

    <!-- Main content -->
    <section class="content">
        {{--报备单信息--}}
        <div class="white-bg">
            <div style="padding: 15px;">
                <h3 class="box-title">报备单基本信息/<span class="text-primary">{{$data->status=='0'?'无效报备':'有效报备'}}</span>
                </h3>
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td>报备流水</td>
                                <td>
                                    {{$data->trade_no}}
                                    ({{$data->status == '0'?'无效':'有效'}})
                                </td>
                                <td>客户姓名</td>
                                <td>
                                    {{$data->client->name}}
                                </td>
                                <td>电话</td>
                                <td>
                                    {{$data->client->phonenum}}
                                </td>
                                <td>报备时间</td>
                                <td>{{$data->created_at}}</td>
                            </tr>
                            <tr>
                                <td>意向楼盘</td>
                                <td>
                                    {{$data->house->title}}
                                </td>
                                <td>顾问</td>
                                <td>
                                    {{isset($data->guwen)?$data->guwen->name:'--'}}
                                </td>
                                <td>客户所在区域</td>
                                <td> {{isset($data->area)?$data->area->name:'--'}}</td>
                                <td>客户住址</td>
                                <td> {{isset($data->address)?$data->address:'--'}}</td>
                            </tr>
                            <tr>
                                <td>意向面积</td>
                                <td>
                                    {{$data->size}}㎡
                                </td>
                                <td>关注要点</td>
                                <td>
                                    {{isset($data->care)?$data->care->name:'--'}}
                                </td>
                                <td>认知途径</td>
                                <td> {{isset($data->know_way)?$data->know_way->name:'--'}}</td>
                                <td>购房目的</td>
                                <td> {{isset($data->purpose)?$data->purpose->name:'--'}}</td>
                            </tr>
                            <tr>
                                <td>
                                    购买意向
                                </td>
                                <td>
                                    {{$data->intention_status == "0"?'无意向':'有意向'}}
                                </td>
                                <td>报备进度</td>
                                <td>
                                    <span class="text-primary">
                                        @if($data->baobei_status=='0')
                                            已报备
                                        @endif
                                        @if($data->baobei_status=='1')
                                            已到访
                                        @endif
                                        @if($data->baobei_status=='2')
                                            已成交
                                        @endif
                                        @if($data->baobei_status=='3')
                                            已签约
                                        @endif
                                        @if($data->baobei_status=='4')
                                            全款到账
                                        @endif
                                    </span>
                                </td>
                                <td>是否可结算</td>
                                <td>
                                    <span class="text-primary">
                                        @if($data->can_jiesuan_status=='0')
                                            不可结算
                                        @endif
                                        @if($data->can_jiesuan_status=='1')
                                            可以结算
                                        @endif
                                    </span>
                                </td>
                                <td>是否已结算</td>
                                <td>
                                    <span class="text-primary">
                                        @if($data->pay_zhongjie_status=='0')
                                            暂未结算
                                        @endif
                                        @if($data->pay_zhongjie_status=='1')
                                            已经结算
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    备注
                                </td>
                                <td colspan="7">
                                    {{isset($data->remark)?$data->remark:'--'}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{--中介明细--}}
        <div class="white-bg">
            <div style="padding: 15px;">
                <h3 class="box-title">中介信息</h3>
                <div class="margin-top-10 font-size-14 grey-bg">
                    <div style="padding: 10px;">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td rowspan="3">
                                    <img src="{{ $data->user->avatar ? $data->user->avatar : URL::asset('/img/default_headicon.png')}}"
                                         style="width: 80px;height: 80px;">
                                </td>
                                <td>微信昵称</td>
                                <td>
                                    {{$data->user->nick_name}}
                                </td>
                                <td>电话</td>
                                <td>
                                    {{$data->user->phonenum}}
                                </td>
                                <td>身份证</td>
                                <td>{{$data->user->cardID}}</td>
                            </tr>
                            <tr>
                                <td>姓名</td>
                                <td>{{$data->user->real_name}}</td>
                                <td>性别</td>
                                <td>
                                    @if($data->user->gender == "0")
                                        保密
                                    @endif
                                    @if($data->user->gender == "1")
                                        男
                                    @endif
                                    @if($data->user->gender == "2")
                                        女
                                    @endif
                                </td>
                                <td>报备次数</td>
                                <td>
                                    {{$data->user->baobei_times}}
                                </td>
                            </tr>
                            <tr>
                                <td>省份</td>
                                <td>{{isset($data->user->province)?$data->user->province:'--'}}</td>
                                <td>城市</td>
                                <td>{{isset($data->user->city)?$data->user->city:'--'}}</td>
                                <td>积分</td>
                                <td>{{$data->user->jifen}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{--案场负责人--}}
        @if(isset($data->anchang))
            <div class="white-bg">
                <div style="padding: 15px;">
                    <h3 class="box-title">案场负责人信息</h3>
                    <div class="margin-top-10 font-size-14 grey-bg">
                        <div style="padding: 10px;">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td rowspan="3">
                                        <img src="{{ $data->anchang->avatar ? $data->anchang->avatar : URL::asset('/img/default_headicon.png')}}"
                                             style="width: 80px;height: 80px;">
                                    </td>
                                    <td>微信昵称</td>
                                    <td>
                                        {{$data->anchang->nick_name}}
                                    </td>
                                    <td>电话</td>
                                    <td>
                                        {{$data->anchang->phonenum}}
                                    </td>
                                    <td>身份证</td>
                                    <td>{{$data->anchang->cardID}}</td>
                                </tr>
                                <tr>
                                    <td>姓名</td>
                                    <td>{{$data->anchang->real_name}}</td>
                                    <td>性别</td>
                                    <td>
                                        @if($data->anchang->gender == "0")
                                            保密
                                        @endif
                                        @if($data->anchang->gender == "1")
                                            男
                                        @endif
                                        @if($data->anchang->gender == "2")
                                            女
                                        @endif
                                    </td>
                                    <td>报备次数</td>
                                    <td>
                                        {{$data->anchang->baobei_times}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>省份</td>
                                    <td>{{isset($data->anchang->province)?$data->anchang->province:'--'}}</td>
                                    <td>城市</td>
                                    <td>{{isset($data->anchang->city)?$data->anchang->city:'--'}}</td>
                                    <td>积分</td>
                                    <td>{{$data->anchang->jifen}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--报备时间轴--}}
        <div class="row margin-top-10">
            <div class="col-md-12">
                <!-- The time line -->
                <ul class="timeline">
                    <!-- timeline time label -->
                    <li class="time-label">
                      <span class="bg-green">
                        报备时间轴
                      </span>
                    </li>
                    <!-- END timeline item -->
                    <!-- timeline item -->
                    @if($data->baobei_status>=0)
                        <li>
                            <i class="fa fa-user bg-aqua"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o">{{$data->created_at}}</i></span>
                                <h3 class="timeline-header">报备客户</h3>
                                <div class="timeline-body">
                                    <div class="">
                                        中介：<span class="text-primary">{{$data->user->real_name}}
                                            ({{$data->user->phonenum}})</span>
                                    </div>
                                    <div class="margin-top-20">
                                        客户：<span class="text-primary">{{$data->client->name}}
                                            ({{$data->client->phonenum}})</span>
                                    </div>
                                    <div class="margin-top-20">
                                        楼盘：<span class="text-primary">{{$data->house->title}}</span>
                                    </div>
                                    <div class="margin-top-20">
                                        计划到访时间：<span class="text-primary">{{$data->plan_visit_time}}
                                            /{{$data->visit_way=='0'?'中介带访':'自行到访'}}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if($data->baobei_status>=1)
                        <li>
                            <i class="fa fa-comments bg-yellow"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i>{{$data->visit_time}}</span>

                                <h3 class="timeline-header">客户到访</h3>

                                <div class="timeline-body">
                                    <div class="">
                                        案场负责人：<span class="text-primary">
                                            @if(isset($data->anchang))
                                                {{$data->anchang->real_name}}
                                                ({{$data->anchang->phonenum}})
                                            @else
                                                暂无案场负责人接收
                                            @endif
                                           </span>
                                    </div>
                                    <div class="margin-top-20">
                                        <img src="{{$data->visit_attach}}" style="width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if($data->baobei_status>=2)
                        <li>
                            <i class="fa fa-money bg-maroon"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o">{{$data->deal_time}}</i></span>
                                <h3 class="timeline-header">报备成交</h3>
                                <div class="timeline-body">
                                    <div class="">
                                        成交面积：<span class="text-primary">{{$data->deal_size}}m²</span>
                                    </div>
                                    <div class="margin-top-20">
                                        成交金额：<span class="text-primary">{{$data->deal_price}}元</span>
                                    </div>
                                    <div class="margin-top-20">
                                        成交户型：<span class="text-primary">{{$data->deal_huxing->name}}
                                            {{$data->deal_huxing->yongjin_type == '0'?'按固定金额':'按千分比'}}-
                                            {{$data->deal_huxing->yongjin_value}}
                                            {{$data->deal_huxing->yongjin_type == '0'?'元':'‰'}}
                                        </span>
                                    </div>
                                    <div class="margin-top-20">
                                        成交房号：<span class="text-primary">{{$data->deal_room}}</span>
                                    </div>
                                    <div class="margin-top-20">
                                        付款方式：<span class="text-primary">{{$data->pay_way->name}}</span>
                                    </div>
                                    <div class="margin-top-20">
                                        产生佣金：<span class="text-primary">{{$data->yongjin}}元</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                    @if($data->baobei_status>=3)
                        <li>
                            <i class="fa fa-yelp bg-purple"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o">{{$data->sign_time}}</i></span>
                                <h3 class="timeline-header">客户签约</h3>
                            </div>
                        </li>
                    @endif
                    @if($data->baobei_status>=4)
                        <li>
                            <i class="fa fa-calendar-check-o bg-aqua"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o">{{$data->qkdz_time}}</i></span>
                                <h3 class="timeline-header">全款到账</h3>
                            </div>
                        </li>
                    @endif
                    @if($data->baobei_status>=2&&$data->pay_zhongjie_status=='1')
                        <li>
                            <i class="fa fa-credit-card bg-red"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o">{{$data->pay_zhongjie_time}}</i></span>
                                <h3 class="timeline-header">向中介结算</h3>
                                <div class="timeline-body">
                                    <div class="">
                                        结算管理员：<span class="text-primary">{{$data->admin->name}}</span>
                                    </div>
                                    <div class="margin-top-20">
                                        <img src="{{$data->pay_zhongjie_attach}}" style="width: 300px;">
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
            <!-- /.col -->
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