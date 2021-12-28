
@extends('lazy-view::layout')

@push('css')
<link href="{{ lazy_asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>修改管理员</h5>

                <div class="ibox-tools">
                    <button type="button" class="btn btn-xs btn-danger history-back">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" action="{{ route('lazy-admin.user.updatedo') }}">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" value="{{ $data->name }}" placeholder="请输入名称"> <span class="help-block m-b-none">名称不可重复</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">真实姓名：</label>
                        <div class="col-sm-9">
                            <input type="text" name="real_name" class="form-control" value="{{ $data->real_name }}" placeholder="请输入真实姓名"> <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email：</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" class="form-control" value="{{ $data->email }}" placeholder="请输入Email"> <span class="help-block m-b-none">邮箱用于登录</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">密码：</label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" placeholder="请输入密码"><span class="help-block m-b-none">密码最少6位</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色：</label>
                        <div class="col-sm-9">
                            @foreach($list as $v)
                            <label style="min-width:15%;">
                                <input type="checkbox" name="role[]" @if($data->hasRole($v->name)) checked @endif value="{{$v->name}}" class="i-checks">{{$v->title}}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 col-sm-offset-3">
                            <button class="btn btn-primary btn-submit" type="button">保存</button>
                            <button class="btn btn-white" type="reset">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
     </div>
</div>
@endsection

@push('scripts')
<script src="{{ lazy_asset('js/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        set_active_menu("{{md5(route('lazy-admin.user.index'))}}")
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>
@endpush
