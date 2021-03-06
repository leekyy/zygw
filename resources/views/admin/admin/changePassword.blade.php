@extends('admin.layouts.app')

@section('content')
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6">
                <ol class="breadcrumb" style="float: none;background: none;">
                    <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                    <li class="active">修改密码</li>
                </ol>
            </div>
            <div class="col-lg-6 text-right">

            </div>
        </div>
    </section>
    <section class="content">
        <form id="form" action="{{URL::asset('/admin/admin/changePassword')}}" method="post" class="form-horizontal"
              onsubmit="return checkValid();">
            {{csrf_field()}}
            <div class="row hidden">
                <div class="col-sm-5 text-right">
                    管理员id
                </div>
                <div class="col-sm-7">
                    <input id="admin_id" name="admin_id" type="text" value="{{$admin->id}}">
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-sm-5 text-right">
                    请再次输入密码
                </div>
                <div class="col-sm-7">
                    <input id="password" name="password" type="password">
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-sm-5 text-right">
                    请再次输入密码
                </div>
                <div class="col-sm-7">
                    <input id="password2" name="password2" type="password">
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary margin-right-10">
                        保存密码
                    </button>
                    <button type="reset" class="btn btn-primary" onclick="clickReset();">
                        重置
                    </button>
                </div>
            </div>
        </form>

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

        /*
         * 校验密码一致性
         */
        function checkValid() {
            var password = $("#password").val();
            var password2 = $("#password2").val();
            //合规校验
            if (judgeIsNullStr(password)) {
                $("#password").focus();
                return false;
            }
            if (password !== password2) {
                $("#password2").focus();
                $("#tipModalBody").html('<p>两次输入的密码不同，请确认</p>');
                $("#tipModal").modal('show');
                return false;
            }
            $("#password").val(hex_md5(password));
            $("#password2").val(hex_md5(password2));
            return true;
        }


        /*
         * 重置密码
         */
        function clickReset() {
            $("#password").val("");
            $("#password2").val("");
        }
    </script>

@endsection