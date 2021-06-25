
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>菜单管理</h5>
            </div>
            <div class="ibox-content">
                <div class="row">
                    @lazy_can('admin-menu-create')
                    <div class="col-sm-3 width-auto">
                        <button
                            type="submit"
                            class="btn btn-sm btn-primary"
                            onclick="location.href='{{ route('lazy-admin.menu.create') }}'"
                        >
                         添加
                        </button>
                    </div>
                    @end_lazy_can
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>序号</th>
                                <th>菜单名称</th>
                                <th>菜单地址</th>
                                <th>icon</th>
                                <th>创建时间</th>
                                <th>修改时间</th>
                                <th width="12%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list as $k => $v)
                            <tr>
                                <td>{{ $v['order'] }}</td>
                                <td>
                                    {!!str_repeat("|---", $v['level'])!!}{{ $v['title'] }}
                                </td>
                                <td>
                                    @if(!empty($v['uri']))
                                        {{ $v['uri'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($v['icon']))
                                    <i class="fa {{ $v['icon'] }}"></i>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $v['created_at'] }}</td>
                                <td>{{ $v['updated_at'] }}</td>
                                <td>

                                    @lazy_can('admin-menu-update')
                                    <a class="btn btn-sm btn-primary" href="{{ route('lazy-admin.menu.update', ['id'=>$v['id']]) }}">
                                        <i class="fa fa-lg fa-edit"></i>&nbsp;修改
                                    </a>
                                    @end_lazy_can

                                    @lazy_can('admin-menu-delete')
                                    <a href="javascript:;" class="btn btn-sm btn-danger operation-confirm-btn" data-method="delete" data-url="{{ route('lazy-admin.menu.delete', ['id'=>$v['id']]) }}" data-confirm-info="确认删除吗？">
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
