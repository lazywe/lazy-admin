
@extends('lazy-view::layout')

@push('css')
<link href="{{ lazy_asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<style>
    .rol-line {
        display: flex;
        width: 100%;
        margin-top:5px;
    }

    .col-line {
        display: flex;
        width: auto;
        margin-top:5px;
    }
</style>
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
                    <div class="row"  id="line-dom" style="display: flex;flex-wrap: wrap;margin: 0;">
                        @foreach($list as $v)
                            <div class="{{$v['level']==2?"col-line":"rol-line"}} form-group-btn" data-level="{{$v['level']}}" data-id="{{$v->id}}" data-pid="{{$v->parent_id}}" data-next="{{$v['next_count']}}">
                                <label>
                                    {!!str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $v['level'])!!}
                                    <input type="checkbox" name="menu_ids[]" value="{{$v->id}}" {{in_array($role->name, explode(',', $v->roles)) ? 'checked':''}}  class="i-checks">
                                    {{ $v['title'] }}
                                </label>
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

        // 选择
        $(".i-checks").on('ifClicked', function(event) {
            var pid = $(this).parents('.form-group-btn').attr("data-pid");
            if (pid > 0) {
                // 选中所有的父
                $.select(pid);
            }
            var id = $(this).parents('.form-group-btn').attr("data-id");
            // 选中所有的子
            $.selectSon(id)
        });

        // 取消选择
        $(".i-checks").on('ifUnchecked', function(event) {
            var id = $(this).parents('.form-group-btn').attr("data-id");
            $.unselect(id);
        });

        // 递归选中父
        $.select = function(pid) {
            $("div[data-id="+pid+"]").each(function(){
                $(this).find('.i-checks').iCheck('check');
                var pid = $(this).attr("data-pid");
                if (pid > 0) {
                    $.select(pid);
                }
            })
        }

        // 递归选中所有的子
        $.selectSon = function(pid) {
            $("div[data-pid="+pid+"]").each(function(){
                $(this).find('.i-checks').iCheck('check');
                var pid = $(this).attr("data-id");
                if (pid > 0) {
                    $.selectSon(pid);
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
        set_active_menu("{{md5(route('lazy-admin.role.index'))}}")
    });
</script>
@endpush