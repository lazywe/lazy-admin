<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>{{config('lazy-admin.name')}}</title>
    <meta name="keywords" content="{{config('lazy-admin.name')}}">
    <meta name="description" content="{{config('lazy-admin.name')}}">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link rel="shortcut icon" href="{{ lazy_asset('img/favicon.ico') }}">
    <link href="{{ lazy_asset('css/bootstrap.min14ed.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/font-awesome.min93e3.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/style.min862f.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    @stack('css')
    <script>
        window.DEBUG = "{{ env('APP_DEBUG', false) }}";
        window.FormToken = "{{ csrf_token() }}"; 
        window.Referer = "{{ app('url')->previous() }}"
        window.AdminHome = "{{ config('lazy-admin.prefix') }}"
    </script>
</head>
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        @yield('content')
    </div>
    <script src="{{ lazy_asset('js/jquery.min.js?v=2.1.4') }}"></script>
    <script src="{{ lazy_asset('js/bootstrap.min.js?v=3.3.6') }}"></script>
    <script src="{{ lazy_asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/layer/layer.min.js') }}"></script>
    <script src="{{ lazy_asset('js/hplus.min.js?v=4.1.0') }}"></script>
    <script src="{{ lazy_asset('js/contabs.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ lazy_asset('js/content.min.js') }}"></script>
    <script src="{{ lazy_asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>