@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">重新设置交易信息</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">

            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!-- form start -->
                    <form id="form" action="{{URL::asset('/admin/baobei/resetDealInfo')}}" method="post"
                          class="form-horizontal"
                          onsubmit="return checkValid();">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group hidden">
                                <label for="id" class="col-sm-3 control-label">报备单id</label>
                                <div class="col-sm-9">
                                    <input id="id" name="id" type="text" class="form-control"
                                           placeholder="报备单id"
                                           value="{{ isset($data->id) ? $data->id : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="admin_name" class="col-sm-3 control-label">管理员姓名</label>
                                <div class="col-sm-9">
                                    <input id="admin_name" name="admin_name" type="text" class="form-control"
                                           value="{{ $admin->name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deal_huxing_id" class="col-sm-3 control-label">成交户型</label>
                                <div class="col-sm-9">
                                    <select id="deal_huxing_id" name="deal_huxing_id" class="form-control"
                                            value="">
                                        @foreach($huxings as $huxing)
                                            <option value="{{$huxing->id}}"
                                                    {{$huxing->id == $data->deal_huxing_id?"selected":""}}>{{$huxing->name}}
                                                -{{$huxing->yongjin_type=='0'?'按固定金额':'按千分比分润'}}
                                                {{$huxing->yongjin_value}}{{$huxing->yongjin_type=='0'?'元':'‰'}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deal_room" class="col-sm-3 control-label">成交房号</label>
                                <div class="col-sm-9">
                                    <input id="deal_room" name="deal_room" type="text" class="form-control"
                                           value="{{$data->deal_room}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deal_size" class="col-sm-3 control-label">成交面积（㎡）</label>
                                <div class="col-sm-9">
                                    <input id="deal_size" name="deal_size" type="text" class="form-control"
                                           value="{{$data->deal_size}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="deal_price" class="col-sm-3 control-label"> 成交金额（元）</label>
                                <div class="col-sm-9">
                                    <input id="deal_price" name="deal_price" type="text" class="form-control"
                                           value="{{$data->deal_price}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pay_way_id" class="col-sm-3 control-label">付款方式</label>
                                <div class="col-sm-9">
                                    <select id="pay_way_id" name="pay_way_id" class="form-control"
                                            value="">
                                        @foreach($pay_ways as $pay_way)
                                            <option value="{{$pay_way->id}}"
                                                    {{$pay_way->id == $data->pay_way_id?"selected":""}} data-type="">{{$pay_way->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="yongjin" class="col-sm-3 control-label"> 成交佣金（元）</label>
                                <div class="col-sm-9">
                                    <input id="yongjin" name="yongjin" type="text" class="form-control"
                                           value="{{$data->yongjin}}" disabled>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-danger btn-block btn-flat">修改交易信息将影响佣金</button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <!-- /.box -->

            </div>
            <!--/.col (right) -->
            <div class="col-md-4">
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
    <script src="{{ URL::asset('js/md5.js') }}"></script>
    <script type="application/javascript">

        /
        合规校验
        /
        function checkValid() {
            //合规校验
            var deal_room = $("#deal_room").val();
            if (judgeIsNullStr(deal_room)) {
                $("#deal_room").focus();
                return false;
            }
            var deal_price = $("#deal_price").val();
            if (judgeIsNullStr(deal_price)) {
                $("#deal_price").focus();
                return false;
            }
            var deal_size = $("#deal_size").val();
            if (judgeIsNullStr(deal_size)) {
                $("#deal_size").focus();
                return false;
            }
            return true;
        }

    </script>

@endsection