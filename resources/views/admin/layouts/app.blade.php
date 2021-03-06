<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>置业顾问 | 管理后台</title>
    <link href="{{ URL::asset('img/favor.ico') }}" rel="shortcut icon" type="image/x-icon"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ URL::asset('bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('dist/css/AdminLTE.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ URL::asset('plugins/iCheck/all.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ URL::asset('dist/css/skins/_all-skins.min.css') }}">
    <!-- Date Picker -->
{{--<link rel="stylesheet"--}}
{{--href="{{ URL::asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">--}}
<!-- Daterange picker -->
{{--<link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">--}}
<!-- common -->
    <link rel="stylesheet" href="{{ URL::asset('css/common.css') }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src=" https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>


        /*按钮大小*/
        .opt-btn-size {
            width: 28px !important;
            height: 28px !important;
        }

        .opt-btn-i-size {
            font-size: 1.4rem !important;
            line-height: 26px !important;
        }

        /*模态框距离顶部边距*/
        . {
            margin-top: 150px;
        }

        .-m {
            margin-top: 20px;
        }

        /*操作th的最大宽度*/
        .opt-th-width {
            min-width: 140px !important;
            width: 140px !important;
            max-width: 140px !important;
        }

        .opt-th-width-l {
            min-width: 180px !important;
            width: 180px !important;
            max-width: 180px !important;
        }

        .opt-th-width-ll {
            min-width: 280px !important;
            max-width: 280px !important;
        }

        .opt-th-width-m {
            min-width: 80px !important;
            max-width: 80px !important;
        }

        .con-th-width-m {
            min-width: 140px !important;
            max-width: 140px !important;
        }

        .con-th-width-mm {
            min-width: 180px !important;
            max-width: 180px !important;
        }

        .con-th-width-l {
            min-width: 280px !important;
            max-width: 280px !important;
        }

        .con-th-width-ll {
            min-width: 360px !important;
            max-width: 360px !important;
        }

    </style>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


    <header class="main-header">
        <!-- Logo -->
        <a href="{{url('/admin/index')}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>置业</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>置业顾问信息平台</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only"></span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ $admin->avatar ? $admin->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim': URL::asset('/img/default_headicon.png')}}"
                                 class="user-image" alt="User Image">
                            <span class="hidden-xs">{{$admin->name}} {{$admin->role == "0" ? "普通管理员":"超级管理员"}}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ $admin->avatar ? $admin->avatar.'?imageView2/1/w/200/h/200/interlace/1/q/75|imageslim': URL::asset('/img/default_headicon.png')}}"
                                     class="img-circle" alt="User Image">

                                <p>
                                    {{$admin->role == "0" ? "普通管理员":"超级管理员"}}
                                    <small>Tel:{{$admin->phonenum}}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="{{url('/admin/admin/changePassword')}}"
                                           class="btn btn-default btn-flat">修改密码</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="{{url('/admin/admin/editInfo')}}"
                                           class="btn btn-default btn-flat">修改资料</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="{{url('/admin/admin/logout')}}"
                                           class="btn btn-default btn-flat">注销</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-info"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>


    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">功能面板</li>
                <li class="active">
                    <a href="{{url('/admin/dashboard/index')}}">
                        <i class="fa fa-bar-chart"></i> <span>业务概览</span>
                    </a>
                </li>
                <li class="">
                    <a href="{{url('/admin/admin/index')}}">
                        <i class="fa fa-user-md"></i><span>管理员管理</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/admin/ad/index')}}">
                        <i class="fa fa-photo"></i>
                        <span>轮播管理</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-send-o"></i> <span>案场/中介</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('admin/userUp/index')}}"><i class="fa fa-circle-o"></i>升级审核</a></li>
                        <li><a href="{{url('admin/acfzr/index')}}"><i class="fa fa-circle-o"></i>案场负责人管理</a></li>
                        <li><a href="{{url('admin/zhongjie/index')}}"><i class="fa fa-circle-o"></i>中介人员管理</a></li>
                        <li><a href="{{url('admin/zhongjie/paiming')}}"><i class="fa fa-circle-o"></i>中介排名</a></li>
                        <li><a href="{{url('admin/userQD/index')}}"><i class="fa fa-circle-o"></i>签到明细</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa  fa-folder-o"></i>
                        <span>楼盘管理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('admin/house/index')}}"><i class="fa fa-circle-o"></i>楼盘管理</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-smile-o"></i>
                        <span>客户管理</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('admin/client/index')}}"><i class="fa fa-circle-o"></i>客户管理</a></li>
                        <li><a href="{{url('admin/baobei/index')}}"><i class="fa fa-circle-o"></i>报备查询</a></li>
                    </ul>
                </li>
                {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                {{--<i class="fa  fa-calendar-plus-o"></i>--}}
                {{--<span>签到管理</span>--}}
                {{--<span class="pull-right-container">--}}
                {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                {{--<li><a href="{{url('admin/userQD/index')}}"><i class="fa fa-circle-o"></i>签到明细</a></li>--}}
                {{--<li><a href="{{url('admin/userQD/stmt')}}"><i class="fa fa-circle-o"></i>综合报表</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-money"></i>
                        <span>积分兑换</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('admin/goods/index')}}"><i class="fa fa-circle-o"></i>商品管理</a></li>
                        <li><a href="{{url('admin/goodsexchange/index')}}"><i class="fa fa-circle-o"></i>兑换订单</a></li>
                        {{--                        <li><a href="{{url('admin/goodsexchange/stmt')}}"><i class="fa fa-circle-o"></i>综合报表</a></li>--}}
                    </ul>
                </li>
                {{--<li class="treeview">--}}
                {{--<a href="#">--}}
                {{--<i class="fa fa-file-text-o"></i>--}}
                {{--<span>规则管理</span>--}}
                {{--<span class="pull-right-container">--}}
                {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</span>--}}
                {{--</a>--}}
                {{--<ul class="treeview-menu">--}}
                {{--<li><a href="{{url('admin/rule/index')}}"><i class="fa fa-circle-o"></i>积分兑换规则</a></li>--}}
                {{--<li><a href="{{url('/admin/hezuo/index')}}"><i class="fa fa-circle-o"></i>合作细则</a></li>--}}
                {{--<li><a href="{{url('admin/whitebook/index')}}"><i class="fa fa-circle-o"></i>行业白皮书</a></li>--}}
                {{--</ul>--}}
                {{--</li>--}}

                <li>
                    <a href="{{url('/admin/tw/index')}}">
                        <i class="fa fa-photo"></i>
                        <span>规则管理</span>
                    </a>
                </li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-database"></i>
                        <span>配置数据</span>
                        <span class="pull-right-container">
                          <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{url('/admin/system/index')}}"><i class="fa fa-circle-o"></i>积分规则
                                <small class="label bg-red pull-right">！</small>
                            </a>
                        </li>
                        <li><a href="{{url('/admin/houseLabel/index')}}"><i class="fa fa-circle-o"></i>楼盘标签管理
                                <small class="label bg-red pull-right">！</small>
                            </a>
                        </li>
                        <li><a href="{{url('/admin/houseType/index')}}"><i class="fa fa-circle-o"></i>楼盘类型管理
                                <small class="label bg-red pull-right">！</small>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')

    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>版本</b> 1.3.1
        </div>
        <strong>Copyright &copy; 2017-2018 <a href="http://www.puh3.net.cn/" target="_blank">置业顾问</a>.</strong> All
        rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Tab panes -->
        <div class="tab-content">
            <!--基本信息-->
            <div class="row">
                <div class="col-xs-12 text-center margin-top-10">
                    该系统提供商为
                </div>
                <div class="col-xs-12 text-center margin-top-15 ">
                    沈阳艺萨艺术发展有限公司
                </div>
                <div class="col-xs-12 text-center margin-top-15">
                    问题反馈：kf@isart.me
                </div>
            </div>
            <div class="margin-top-10 border-bottom">
            </div>
            <!--常用问题解答-->
            <div class="row">
                <div class="col-xs-12 margin-top-10 text-oneline">
                    FAQ
                </div>
                <div class="col-xs-12 margin-top-10 text-oneline">
                    <a href="#">置业顾问功能简述</a>
                </div>
                <div class="col-xs-12 margin-top-10 text-oneline">
                    <a href="#">关于如何建立患者病历及病历如何查看</a>
                </div>
            </div>
        </div>
    </aside>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ URL::asset('bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- daterangepicker -->
{{--<script src="{{ URL::asset('bower_components/moment/min/moment.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>--}}
<!-- datepicker -->
{{--<script src="{{ URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}
<!-- Bootstrap WYSIHTML5 -->
{{--<script src="{{ URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>--}}
<!-- Slimscroll -->
<script src="{{ URL::asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('dist/js/adminlte.min.js') }}"></script>
<!-- iCheck 1.0.1 -->
<script src="{{ URL::asset('plugins/iCheck/icheck.min.js') }}"></script>
<!-- 七牛 -->
<script src="{{ URL::asset('js/qiniu.js') }}"></script>
<script src="{{ URL::asset('js/plupload/plupload.full.min.js') }}"></script>
<script src="{{ URL::asset('js/plupload/moxie.js') }}"></script>
<!-- common -->
<script src="{{ URL::asset('js/common.js') }}"></script>
<!-- common -->
<script src="{{ URL::asset('js/echarts.common.min.js') }}"></script>
<!-- doT -->
<script src="{{ URL::asset('js/dateFormateTool.js') }}"></script>
<!-- doT -->
<script src="{{ URL::asset('js/doT.min.js') }}"></script>

</body>
</html>
@yield('script')


