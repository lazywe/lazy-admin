
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>角色管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    @lazy_can('admin-role-create')
                    <div class="col-sm-3 width-auto">
                        <button type="submit"  class="btn btn-sm btn-primary" onclick="location.href='{{ route('lazy-admin.role.create') }}'"> 添加</button>
                    </div>
                    @end_lazy_can
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>名称</th>
                                <th>标识</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th width="15%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $v)
                            <tr>
                                <td>{{ $v->id }}</td>
                                <td>{{ $v->title }}</td>
                                <td>{{ $v->name }}</td>
                                <td>{{ $v->created_at }}</td>
                                <td>{{ $v->updated_at }}</td>
                                <td>
                                    @lazy_can('admin-role-update')
                                    <a class="btn btn-sm btn-primary" href="{{ route('lazy-admin.role.update', ['id'=>$v->id]) }}">
                                        修改角色
                                    </a>
                                    <br/>
                                    @end_lazy_can

                                    @lazy_can('admin-role-menu')
                                    <a class="btn btn-sm btn-warning" href="{{ route('lazy-admin.role.menu', ['id'=>$v->id]) }}">
                                        角色菜单
                                    </a>
                                    <br/>
                                    @end_lazy_can

                                    @if($v->id !=1 )
                                        @lazy_can('admin-role-delete')
                                        <a href="javascript:;" class="btn btn-sm btn-danger operation-confirm-btn" data-method="delete" data-url="{{ route('lazy-admin.role.delete', ['id'=>$v->id]) }}" data-confirm-info="确认删除吗？">
                                            删除角色
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
            set_active_menu("{{md5(route('lazy-admin.role.index'))}}")
        })
    </script>
@endpush
