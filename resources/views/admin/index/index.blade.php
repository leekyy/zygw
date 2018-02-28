@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <small>综合统计</small>
        </h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3">

            </div>
            <div class="col-md-3">

            </div>
        </div>
    </section>
@endsection


@section('script')
    <script type="application/javascript">

        //统计信息
        var $baobei_stmt = {!!$baobei_stmt!!};
        var $jiesuan_stmt = {!!$jiesuan_stmt!!};
        var $yongjin_stmt = {!!$yongjin_stmt!!};

    </script>
@endsection