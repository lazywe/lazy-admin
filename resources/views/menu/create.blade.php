
@extends('lazy-view::layout')

@push('css')
<link href="{{ lazy_asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加菜单</h5>
                <div class="ibox-tools">
                    <button type="button" class="btn btn-xs btn-danger history-back">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" action="{{ route('lazy-admin.menu.createdo') }}">
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font class="text-danger">* </font>菜单名称：</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" class="form-control" placeholder="请输入菜单名称"> <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label"><font class="text-danger">* </font>上级菜单：</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="parent_id">
                                <option value="0">根菜单</option>
                                @foreach($list as $k => $v)
                                    <option value="{{$v['id']}}">{!!str_repeat("&nbsp;&nbsp;", $v['level']+1)!!}{{ $v['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font class="text-danger">* </font>菜单地址：</label>
                        <div class="col-sm-9">
                            <input type="text" name="uri" class="form-control" placeholder="请输入菜单地址"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">icon：</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="icon" placeholder="请输入icon, 例如：fa-home"><span class="help-block m-b-none">立即前往，<a href="http://fontawesome.dashgame.com/#new" target="_blank">fontawesome</a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><font class="text-danger">* </font>序号：</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="order" placeholder="请输入序号，值越大越考前">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色：</label>
                        <div class="col-sm-9">
                            @foreach($roleList as $v)
                            <label class="checkbox-inline" style="min-width:15%;">
                                <input type="checkbox" name="roles[]" value="{{$v->name}}" class="i-checks">{{$v->title}}
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
    });
</script>
@endpush
