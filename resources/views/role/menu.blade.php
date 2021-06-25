
@extends('lazy-view::layout')

@push('css')
<link href="{{ lazy_asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>权限菜单</h5>

                <div class="ibox-tools">
                    <button type="button" class="btn btn-xs btn-danger history-back">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" action="{{ route('lazy-admin.role.menudo') }}">
                    <input type="hidden" value="{{$role->name}}" name="role_name">
                    <div class="row">
                        @foreach($list as $v)
                        <div class="form-group" data-level="{{$v['level']}}" data-id="{{$v->id}}" data-pid="{{$v->parent_id}}">
                            <div class="col-sm-4"></div>
                            <div class="col-sm-8">
                                <label class="checkbox-inline" style="min-width:15%;">
                                    {!!str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $v['level'])!!}
                                    <input type="checkbox" name="menu_ids[]" value="{{$v->id}}" {{in_array($role->name, explode(',', $v->roles)) ? 'checked':''}}  class="i-checks">
                                    {{ $v['title'] }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <button class="btn btn-primary btn-submit" type="button">保存</button>
                            <a href="" class="btn btn-white">重置</a>
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

        $(".i-checks").on('ifChecked', function(event) {
            var pid = $(this).parents('.form-group').attr("data-pid");
            if (pid > 0) {
                $.select(pid);
            }
        });

        $(".i-checks").on('ifUnchecked', function(event) {
            var id = $(this).parents('.form-group').attr("data-id");
            $.unselect(id);
        });

        // 选中
        $.select = function(pid) {
            $("div[data-id="+pid+"]").each(function(){
                $(this).find('.i-checks').iCheck('check');
                var pid = $(this).attr("data-pid");
                if (pid > 0) {
                    $.select(pid);
                }
            })
        }

        // 取消
        $.unselect = function(id) {
            $("div[data-pid="+id+"]").each(function(){
                $(this).find('.i-checks').iCheck('uncheck');
                var id = $(this).attr("data-id");
                $.unselect(id);
            })
        }
    });
</script>
@endpush
