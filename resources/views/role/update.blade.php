
@extends('lazy-view::layout')

@push('css')
<link href="{{ lazy_asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加角色</h5>

                <div class="ibox-tools">
                    <button type="button" class="btn btn-xs btn-danger history-back">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" action="{{ route('lazy-admin.role.updatedo') }}">
                    <input type="hidden" name="id" value="{{ $data->id }}"> 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{ $data->title }}" class="form-control" placeholder="请输入名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标识</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control" placeholder="请输入标识">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">权限：</label>
                        <div class="col-sm-9">
                            @foreach($list as $v)
                            <label style="min-width:15%;">
                                <input type="checkbox" @if($data->hasPermissionTo($v->name)) checked @endif name="permission[]" value="{{$v->name}}" class="i-checks">{{$v->title}}
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
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        set_active_menu("{{md5(route('lazy-admin.role.index'))}}")
    });
</script>
@endpush