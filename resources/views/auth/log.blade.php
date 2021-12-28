
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>日志列表</h5>
            </div>
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户</th>
                                <th>method</th>
                                <th>菜单地址</th>
                                <th width="30%">参数</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $v)
                            <tr>
                                <td>{{ $v['id'] }}</td>
                                <td>
                                    @if(!empty($v->user->name))
                                    {{ $v->user->name??'-'}}【{{$v->user_id}}】
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $v['method'] }}</td>
                                <td>
                                    @if(!empty($v['uri']))
                                        {{ $v['uri'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <pre style="white-space: pre-wrap;">{{$v['params']?json_encode($v['params'], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT):'-'}}</pre>
                                </td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>{{ $v['updated_at'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($list) > 0)
                    {{$list->links()}}
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function(){
            set_active_menu("{{md5(route('lazy-admin.auth.log'))}}")
        })
    </script>
@endpush