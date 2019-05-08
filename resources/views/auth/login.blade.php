<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config("lazy-admin.name") }}</title>
    <meta name="keywords" content="{{ config("lazy-admin.name") }}">
    <meta name="description" content="{{ config("lazy-admin.name") }}">
    <link rel="shortcut icon" href="{{ lazy_asset('img/favicon.ico') }}">
    <link href="{{ lazy_asset('css/bootstrap.min14ed.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/font-awesome.min93e3.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/style.min862f.css') }}" rel="stylesheet">
    <link href="{{ lazy_asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
      if (window.top !== window.self) { 
        window.top.location = window.location;
      } 
      window.DEBUG = "{{ env('APP_DEBUG', false) }}"; 
      window.FormToken = "{{ csrf_token() }}"; 
    </script>
</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">{{ config("lazy-admin.logo") }}</h1>
            </div>
            <h3>登录</h3>
            <form class="m-t" role="form" method="POST" action="{{route('lazy-admin.logindo')}}">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="邮箱" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="密码" required="">
                </div>
                <button type="button" class="btn btn-primary block full-width m-b btn-submit">登 录</button>
            </form>
        </div>
    </div>
    <script src="{{ lazy_asset('js/jquery.min.js') }}"></script>
    <script src="{{ lazy_asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ lazy_asset('js/main.js') }}"></script>
</body>
</html>
