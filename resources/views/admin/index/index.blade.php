@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>近30日综合统计</small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        {{--条件搜索--}}
        <form action="{{URL::asset('/admin/dashboard/index')}}" method="get"
              class="form-horizontal">
            {{csrf_field()}}
            <div class="row">
                <div class="col-md-3">
                    <input id="start_date" name="start_date" type="date" value="{{$start_date}}" class="form-control">
                </div>
                <div class="col-md-3">
                    <input id="end_date" name="end_date" type="date" value="{{$end_date}}" class="form-control">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-block btn-flat" onclick="">
                        搜索
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="" target="_blank">
                        下载报表
                    </a>
                </div>
                <div class="col-md-2">
                </div>

            </div>
        </form>
        {{--图表信息--}}
        <div class="row margin-top-10">
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备状态占比（个）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="baobei_pie_div" style="width: 100%;height: 300px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备到访趋势图（个）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="daofang_trend_bar_div" style="width: 100%;height: 300px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备单-确认结算数占比（个）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="can_jiesuan_pie_div" style="width: 100%;height: 240px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备单-佣金支付数占比（个）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="pay_zhongjie_pie_div" style="width: 100%;height: 240px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备单-确认佣金金额（元）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="yongjin_canjiesuan_pie_div" style="width: 100%;height: 240px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">报备单-支付佣金金额（元）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="yongjin_payzhongjie_pie_div" style="width: 100%;height: 240px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>

        {{--佣金趋势--}}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title font-size-14">佣金趋势（元）</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div id="yongjin_trend_div" style="width: 100%;height: 240px;background: white;">

                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
@endsection


@section('script')
    <script type="application/javascript">

        //统计信息
        var baobei_stmt = {!!$baobei_stmt!!};
        var daofang_trend = {!! $daofang_trend !!};
        var jiesuan_stmt = {!!$jiesuan_stmt!!};
        var yongjin_stmt = {!!$yongjin_stmt!!};
        var yongjin_trend = {!!$yongjin_trend!!};

        //报备信息
        showBaoBeiPieChart();
        //到访趋势
        showDaofangTrendBarChart();
        //案场确认佣金
        showCanJieSuanPieChart();
        //中介结算
        showPayZhongJiePieChart();
        //佣金金额确认
        showYongjinCanJieSuanPieChart();
        //佣金计算金额
        showYongjinPayZhongJiePieChart();
        //显示佣金趋势
        showYongjinTrendLineChart();

        //展示报备图表
        function showBaoBeiBarChart() {
            var chart = echarts.init(document.getElementById('baobei_bar_div'));
            var option = {
                color: ['#3398DB'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: ['全部', '报备', '到访', '成交', '签约', '全款到账'],
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '',
                        type: 'bar',
                        barWidth: '40%',
                        data: [baobei_stmt[0].all, baobei_stmt[0].baobei_status0
                            , baobei_stmt[0].baobei_status1, baobei_stmt[0].baobei_status2
                            , baobei_stmt[0].baobei_status3, baobei_stmt[0].baobei_status4]
                    }
                ]
            };
            chart.setOption(option);
        }

        //展示报备图表
        function showBaoBeiPieChart() {
            var chart = echarts.init(document.getElementById('baobei_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['报备', '到访', '成交', '签约', '全款到账']
                },
                series: [
                    {
                        name: '报备单类型',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: baobei_stmt[0].baobei_status0, name: '报备'},
                            {value: baobei_stmt[0].baobei_status1, name: '到访'},
                            {value: baobei_stmt[0].baobei_status2, name: '成交'},
                            {value: baobei_stmt[0].baobei_status3, name: '签约'},
                            {value: baobei_stmt[0].baobei_status4, name: '全款到账'}
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //到访趋势
        function showDaofangTrendBarChart() {
            var chart = echarts.init(document.getElementById('daofang_trend_bar_div'));
            var date_arr = [];
            var date_value_arr = [];
            for (var i = 0; i < daofang_trend.length; i++) {
                date_arr.push(daofang_trend[i].tjdate);
                date_value_arr.push(daofang_trend[i].nums);
            }
            var option = {
                color: ['#3398DB'],
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: date_arr,
                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '',
                        type: 'bar',
                        barWidth: '40%',
                        data: date_value_arr
                    }
                ]
            };
            chart.setOption(option);
        }


        //展示能否结算
        function showCanJieSuanPieChart() {
            var chart = echarts.init(document.getElementById('can_jiesuan_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待确认', '已确认']
                },
                series: [
                    {
                        name: '案场是否确认',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: jiesuan_stmt[0].can_jiesuan_status0, name: '待确认'},
                            {value: jiesuan_stmt[0].can_jiesuan_status1, name: '已确认'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }


        //中介结算
        function showPayZhongJiePieChart() {
            var chart = echarts.init(document.getElementById('pay_zhongjie_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '是否向中介结算',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: jiesuan_stmt[0].pay_zhongjie_status0, name: '待结算'},
                            {value: jiesuan_stmt[0].pay_zhongjie_status1, name: '已结算'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        function showYongjinCanJieSuanPieChart() {
            var chart = echarts.init(document.getElementById('yongjin_canjiesuan_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '案场是否确认',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: yongjin_stmt[0].can_jiesuan_status0, name: '待确认'},
                            {value: yongjin_stmt[0].can_jiesuan_status1, name: '已确认'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //向中介支付
        function showYongjinPayZhongJiePieChart() {
            var chart = echarts.init(document.getElementById('yongjin_payzhongjie_pie_div'));
            var option = {
                title: {},
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data: ['待结算', '已结算']
                },
                series: [
                    {
                        name: '是否向中介结算',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data: [
                            {value: yongjin_stmt[0].pay_zhongjie_status0, name: '待结算'},
                            {value: yongjin_stmt[0].pay_zhongjie_status1, name: '已结算'},
                        ],
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            chart.setOption(option);
        }

        //展示佣金趋势
        function showYongjinTrendLineChart() {
            var chart = echarts.init(document.getElementById('yongjin_trend_div'));

            var date_arr = [];
            var shengcheng_yongjin_arr = [];
            var queren_kejiesuan_arr = [];
            var zhifu_zhongjie_arr = [];
            for (var i = 0; i < yongjin_trend[0].shengcheng_yongjin.length; i++) {
                date_arr.push(yongjin_trend[0].shengcheng_yongjin[i].tjdate);
                shengcheng_yongjin_arr.push(yongjin_trend[0].shengcheng_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].shengcheng_yongjin[i].yongjin);
                queren_kejiesuan_arr.push(yongjin_trend[0].queren_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].queren_yongjin[i].yongjin);
                zhifu_zhongjie_arr.push(yongjin_trend[0].zhifu_yongjin[i].yongjin == null ? 0 : yongjin_trend[0].zhifu_yongjin[i].yongjin);
            }

            var option = {
                title: {
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['产生佣金', '确认可结算', '支付中介']
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
                        name: '产生佣金',
                        type: 'line',
                        stack: '日生成量',
                        data: shengcheng_yongjin_arr
                    },
                    {
                        name: '确认可结算',
                        type: 'line',
                        stack: '日生成量',
                        data: queren_kejiesuan_arr
                    },
                    {
                        name: '支付中介',
                        type: 'line',
                        stack: '日生成量',
                        data: zhifu_zhongjie_arr
                    }
                ]
            };
            chart.setOption(option);
        }


    </script>
@endsection