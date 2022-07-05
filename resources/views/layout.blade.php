<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>{{config('lazy-admin.name')}}</title>
    <meta name="keywords" content="{{config('lazy-admin.name')}}">
    <meta name="description" content="{{config('lazy-admin.name')}}">
    <link rel="shortcut icon" href="{{ lazy_asset('img/favicon.ico') }}">

    <link href="{{ lazy_asset('css/bootstrap.min14ed.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/font-awesome.min93e3.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/animate.min.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/style.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/plugins/toastr/toastr.min.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    @stack('css')
    <script>
        window.DEBUG = "{{ config('app.debug') }}";
        window.FormToken = "{{ csrf_token() }}";
        window.Referer = "{!! app('url')->previous() !!}"
        window.AdminHome = "/{{ config('lazy-admin.prefix') }}"
        // 首次加载记录referer， 若刷新了页面用之前的referer
        // if (window.performance.navigation.type == 0) {
        //     sessionStorage.setItem("Referer", Referer)
        // } else {
        //     Referer = sessionStorage.getItem("Referer")
        // }
    </script>

</head>

<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">

                        <div class="dropdown profile-element"> <span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                                <span class="clear">
                                    <span class="block m-t-xs">
                                        <strong class="font-bold">
                                            {{ lazyAdminUser()->name }}
                                        </strong>
                                    </span>
                              <span class="text-muted text-xs block">{{ lazyAdminUser()->real_name }} <b class="caret"></b>
                            </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li>
                                    <a href="javascript:;"
                                        class=" operation-confirm-btn"
                                        data-method="put"
                                        data-confirm-info="确认退出吗?"
                                        data-url="{{ route('lazy-admin.logout') }}">
                                        <i class="fa fa fa-sign-out"></i> 安全退出
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            {{config('lazy-admin.logo')}}
                        </div>
                    </li>


                    <li>
                        <a class="{{md5(route(config('lazy-admin.index')))}}" href="{{ route(config('lazy-admin.index')) }}" data-index="0">
                            <i class="fa fa-home"></i>
                            <span class="nav-label">{{config('lazy-admin.index-title')}}</span>
                        </a>
                    </li>

                    @foreach (adminMenus() as $first)
                    <li>
                        <a class="{{md5(lazy_url($first['uri']))}}" href="{{ lazy_url($first['uri']) }}">
                            <i class="fa {{ $first['icon'] }}"></i>
                            <span class="nav-label">{{$first['title']}}</span>
                            @if (count($first['son'])>0)
                            <span class="fa arrow"></span>
                            @endif
                        </a>
                        @if (count($first['son']) >0)
                        <ul class="nav nav-second-level">
                            @foreach ($first['son'] as $second)
                            <li>
                                <a class="{{md5(lazy_url($second['uri']))}}" href="{{ lazy_url($second['uri']) }}">
                                   {{$second['title']}}
                                    @if (count($second['son']) >0)
                                    <span class="fa arrow"></span>
                                    @endif
                                </a>
                                @if (count($second['son']) >0)
                                <ul class="nav nav-third-level">
                                    @foreach ($second['son'] as $third)
                                    <li>
                                        <a class="{{md5(lazy_url($third['uri']))}}" href="{{ lazy_url($third['uri']) }}">{{$third['title']}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </nav>

        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="javascript:;"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <a href="javascript:;"
                                class=" operation-confirm-btn"
                                data-method="put"
                                data-confirm-info="确认退出吗?"
                                data-url="{{ route('lazy-admin.logout') }}">
                                <i class="fa fa fa-sign-out"></i> 退出
                            </a>
                        </li>
                    </ul>

                </nav>
            </div>

            {{-- 面包屑 --}}
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2 id="right-title">@yield('head-title')</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ route(config('lazy-admin.index')) }}">后台主页</a>
                        </li>
                        <li>
                            <strong id="right-title-two">@yield('head-title')</strong>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">
                {{-- 这里是内容 --}}
                @yield('content')
            </div>
            <div class="footer">
                <div>
                    <strong>{{config('lazy-admin.name')}}</strong>
                </div>
                {{-- 这里是footer --}}
            </div>
        </div>
    </div>
    </div>

    <!-- Mainly scripts -->
    <script src="{{ lazy_asset('js/jquery.min.js?v=2.1.4') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/bootstrap.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/metisMenu/jquery.metisMenu.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/layer/layer.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/hplus.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/toastr/toastr.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/md5.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/base64.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/vconsole.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/main.js') }}?time={{config('lazy-admin.timestamp')}}"></script>

    <!-- iCheck -->
    @stack('scripts')
    <script>
        $(function(){
            try {
                @hasSection('head-title')
                @else
                var titleHtml = $(".wrapper-content").find(".ibox .ibox-title h5");
                if (!titleHtml) {
                    title = "当前栏目"
                } else {
                    title = titleHtml.html();
                }
                $('#right-title').html(title);
                $('#right-title-two').html(title);
                @endif
                // 动态修改title
                var documentTitle = $("title").html()
                $("title").html($.trim($("#right-title").text() )+ "-" +  documentTitle)
            } catch (error) {
            }
        })
    </script>
</body>
</html>
