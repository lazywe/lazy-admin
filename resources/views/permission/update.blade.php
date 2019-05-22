
@extends('lazy-view::layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>添加权限</h5>

                <div class="ibox-tools">
                    <button type="button" class="btn btn-xs btn-danger history-back">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

            </div>
            <div class="ibox-content">
                <form method="post" class="form-horizontal" action="{{ route('lazy-admin.permission.updatedo') }}">
                    <input type="hidden" name="id" value="{{ $data->id }}"> 
                    <div class="form-group">
                        <label class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-9">
                            <input type="text" name="title" value="{{ $data->title }}" class="form-control" placeholder="请输入名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">标识：</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control" placeholder="请输入标识">
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