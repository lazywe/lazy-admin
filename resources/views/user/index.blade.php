
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>管理员</h5>
            </div>
            <div class="ibox-content">
                <form action="{{route('lazy-admin.user.index')}}">
                <div class="row">
                    @lazy_can('admin-user-create')
                    <div class="col-sm-2 width-auto">
                        <button type="button"  class="btn btn-sm btn-primary" onclick="location.href='{{ route('lazy-admin.user.create') }}'"> 添加</button>
                    </div>
                    @end_lazy_can
                    <div class="col-sm-2 m-b-xs">
                        <input type="text" name="name" value="{{app('request')->name??''}}" placeholder="请输入名称" class="form-control">
                    </div>
                    <div class="col-sm-2 m-b-xs">
                        <input type="text" name="real_name" value="{{app('request')->real_name??''}}" placeholder="请输入真实姓名" class="form-control">
                    </div>
                    <div class="col-sm-2 m-b-xs">
                        <input type="text" name="email"  value="{{app('request')->email??''}}" placeholder="请输入邮箱" class="form-control">
                    </div>
                    <div class="col-sm-2 width-auto">
                        <div class="btn-group btn-group-sm">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                            </button>
                            <button type="button" onclick="location.href='{{route('lazy-admin.user.index')}}'" class="btn btn-warning">
                                <i class="fa fa-undo"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>真实姓名</th>
                                <th>邮箱</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th width="15%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $v)
                            <tr>
                                <td>{{ $v->id }}</td>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->real_name }}</td>
                                <td>{{ $v->email }}</td>
                                <td>{{ $v->created_at }}</td>
                                <td>{{ $v->updated_at }}</td>
                                <td>
                                        @lazy_can('admin-user-update')
                                        <a class="btn btn-sm btn-primary" href="{{ route('lazy-admin.user.update', ['id'=>$v->id]) }}">
                                            <i class="fa fa-lg fa-edit"></i>&nbsp;修改
                                        </a>
                                        @end_lazy_can
                                    @if($v->id !=1 )
                                        @lazy_can('admin-user-delete')
                                        <a href="javascript:;" class="btn btn-sm btn-danger operation-confirm-btn" data-method="delete" data-url="{{ route('lazy-admin.user.delete', ['id'=>$v->id]) }}" data-confirm-info="确认删除吗？">
                                            <i class="fa fa-lg fa-trash"></i>&nbsp;删除
                                        </a>
                                        @end_lazy_can
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $list->appends(request()->except('s'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        $(function(){
            set_active_menu("{{md5(route('lazy-admin.user.index'))}}")
        })
    </script>
@endpush
