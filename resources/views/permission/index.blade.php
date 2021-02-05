
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>权限管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    @lazy_can('admin-permission-create')
                    <div class="col-sm-3 width-auto">
                        <button type="submit"  class="btn btn-sm btn-primary" onclick="location.href='{{ route('lazy-admin.permission.create') }}'"> 添加</button>
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
                                    @lazy_can('admin-permission-update')
                                    <a class="btn btn-sm btn-primary" href="{{ route('lazy-admin.permission.update', ['id'=>$v->id]) }}">
                                        <i class="fa fa-lg fa-edit"></i>&nbsp;修改
                                    </a>
                                    @end_lazy_can
                                    @lazy_can('admin-permission-delete')
                                    <a href="javascript:;" class="btn btn-sm btn-danger operation-confirm-btn" data-method="delete" data-url="{{ route('lazy-admin.permission.delete', ['id'=>$v->id]) }}" data-confirm-info="确认删除吗？">
                                        <i class="fa fa-lg fa-trash"></i>&nbsp;删除
                                    </a>
                                    @end_lazy_can
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
