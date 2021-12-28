@extends('lazy-view::layout')

@section("head-title")
{{config('lazy-admin.index-title')}}
@endsection

@section('content')
<b>Hello! Lazy Admin</b>
@endsection
@push('scripts')
    <script>
        $(function(){
            set_active_menu("{{md5(route('lazy-admin.home'))}}")
        })
    </script>
@endpush
