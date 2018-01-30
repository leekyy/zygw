@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">楼盘综合统计</li>
                </ol>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{--统计--}}
        <div class="row">
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{$data->zqdrcs}}</h3>

                        <p>总楼盘数</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息<i class="fa fa-arrow-circle-right margin-left-5"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{$data->zqdrs}}</h3>

                        <p>总房源</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">更多信息<i class="fa fa-arrow-circle-right margin-left-5"></i></a>
                </div>
            </div>
            <!-- ./col -->
            {{--<div class="col-lg-3 col-xs-6">--}}
                {{--<!-- small box -->--}}
                {{--<div class="small-box bg-red">--}}
                    {{--<div class="inner">--}}
                        {{--<h3>{{$data->zpsjfs}}</h3>--}}

                        {{--<p>兑换订单总积分</p>--}}
                    {{--</div>--}}
                    {{--<div class="icon">--}}
                        {{--<i class="ion ion-pie-graph"></i>--}}
                    {{--</div>--}}
                    {{--<a href="#" class="small-box-footer">更多信息<i class="fa fa-arrow-circle-right margin-left-5"></i></a>--}}
                {{--</div>--}}
            {{--</div>--}}
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">

            </div>
        </div>

        {{--折线图--}}
        <div style="background-color: white;">
            <div id="chart-content" style="width: 100%;height: 400px;padding: 20px;">

            </div>
        </div>

    </section>
@endsection

@section('script')
    <script type="application/javascript">

        //入口函数
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            getLPRecentDatas("{{URL::asset('')}}", {_token: "{{ csrf_token() }}"}, function (ret) {
                if (ret.result) {
                    var msgObj = ret.ret;
                    showChart("chart-content", msgObj, '楼盘统计', '近15日楼盘总数/楼盘综合统计');
                }
            });
        });

        //描绘折线图
        function showChart(dom_id, data_obj, title, subTitle) {
            //定义关键指标
            var date_arr = [];
            var jf_arr = [];
            var rs_arr = [];

            //整理数据
            for (var i = 0; i < data_obj.length; i++) {
                date_arr.push(data_obj[i].tjdate);
                //楼盘数
                rs_arr.push(data_obj[i].qdrs);
                jf_arr.push(data_obj[i].psjfs);
//                jf_arr.push(data_obj[i].psjfs1);
            }
            //柱状图对象
            var barChart = echarts.init(document.getElementById(dom_id));
            option = {
                title: {
                    text: title,
                    subtext: subTitle
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: [ '楼盘数']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: date_arr
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        name: '楼盘数',
                        type: 'line',
                        stack: '总量',
                        data: rs_arr
                    },
//                    {
//                        name: '楼盘类型',
//                        type: 'line',
//                        stack: '总量',
//                        data: jf_arr
//                    }
                ]
            };
            // 使用刚指定的配置项和数据显示图表
            barChart.setOption(option, true);
        }

    </script>
@endsection