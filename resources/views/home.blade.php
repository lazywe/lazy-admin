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
    <link href="{{ lazy_asset('css/bootstrap.min14ed.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/font-awesome.min93e3.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/animate.min.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/style.min862f.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <link href="{{ lazy_asset('css/plugins/toastr/toastr.min.css') }}?time={{config('lazy-admin.timestamp')}}" rel="stylesheet">
    <script>
        window.DEBUG = "{{ env('APP_DEBUG', false) }}";
        window.FormToken = "{{ csrf_token() }}";
    </script>
</head>

<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
    <div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="text-muted text-xs block">{{ lazyAdminUser()->name }}<b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li>
                                    <a href="javascript:;" class=" operation-confirm-btn" data-method="put" data-confirm-info="确认退出吗?" data-url="{{ route('lazy-admin.logout') }}"><i class="fa fa fa-sign-out"></i> 安全退出</a>
                                </li>
                            </ul>
                        </div>
                        <div class="logo-element">{{config('lazy-admin.logo')}}
                        </div>
                    </li>
                    <li>
                        <a class="J_menuItem j_home active" href="{{ route(config('lazy-admin.index')) }}" data-index="0">
                            <i class="fa fa-home"></i>
                            <span class="nav-label">{{config('lazy-admin.index-title')}}</span>
                        </a>
                    </li>
                    @foreach ($menus as $first)
                    <li>
                        <a class="{{count($first['son'])>0?'':'J_menuItem' }} {{md5(lazy_url($first['uri']))}}" href="{{ lazy_url($first['uri']) }}">
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
                                <a class="{{count($second['son'])>0?'':'J_menuItem' }} {{md5(lazy_url($second['uri']))}}" href="{{ lazy_url($second['uri']) }}">
                                    <span class="nav-label">{{$second['title']}}</span>
                                    @if (count($second['son']) >0)
                                    <span class="fa arrow"></span>
                                    @endif
                                </a>
                                @if (count($second['son']) >0)
                                <ul class="nav nav-third-level">
                                    @foreach ($second['son'] as $third)
                                    <li>
                                        <a class="J_menuItem {{md5(lazy_url($third['uri']))}}" href="{{ lazy_url($third['uri']) }}">{{$third['title']}}</a>
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
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown hidden-xs">
                            <a class="right-sidebar-toggle" aria-expanded="false">
                                <i class="fa fa-tasks"></i> 主题
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row content-tabs">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="{{ route(config('lazy-admin.index')) }}">{{config('lazy-admin.index-title')}}</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">菜单操作<span class="caret"></span>
                    </button>
                    <ul role="menu" class="dropdown-menu dropdown-menu-right">
                        <li class="J_tabShowActive"><a>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                        </li>
                        <li class="J_tabRefresh"><a>刷新当前选项卡</a>
                        </li>
                    </ul>
                </div>
                <a href="javascript:;" class="roll-nav roll-right J_tabExit operation-confirm-btn" data-method="put" data-confirm-info="确认退出吗?" data-url="{{ route('lazy-admin.logout') }}"><i class="fa fa fa-sign-out"></i> 退出</a>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="{{ route(config('lazy-admin.index')) }}" data-id="{{ route(config('lazy-admin.index')) }}" frameborder="0" seamless></iframe>
            </div>
            <div class="footer">
                <div class="pull-right">&copy; 2019-2029
                </div>
            </div>
        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> 主题设置</h3>
                            <small><i class="fa fa-tim"></i> 你可以从这里选择和预览主题的布局和样式，这些设置会被保存在本地，下次打开的时候会直接应用这些设置。</small>
                        </div>
                        <div class="skin-setttings">
                            <div class="title">主题设置</div>
                            <div class="setings-item">
                                <span>收起左侧菜单</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="collapsemenu">
                                        <label class="onoffswitch-label" for="collapsemenu">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>固定顶部</span>
                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="fixednavbar" class="onoffswitch-checkbox" id="fixednavbar">
                                        <label class="onoffswitch-label" for="fixednavbar">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="setings-item">
                                <span>
                                    固定宽度
                                </span>

                                <div class="switch">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="boxedlayout" class="onoffswitch-checkbox" id="boxedlayout">
                                        <label class="onoffswitch-label" for="boxedlayout">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="title">皮肤选择</div>
                            <div class="setings-item default-skin nb">
                                <span class="skin-name ">
                                    <a href="#" class="s-skin-0">
                                        默认皮肤
                                    </a>
                                </span>
                            </div>
                            <div class="setings-item blue-skin nb">
                                <span class="skin-name ">
                                    <a href="#" class="s-skin-1">
                                        蓝色主题
                                    </a>
                                </span>
                            </div>
                            <div class="setings-item yellow-skin nb">
                                <span class="skin-name ">
                                    <a href="#" class="s-skin-3">
                                        黄色/紫色主题
                                    </a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="{{ lazy_asset('js/jquery.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/bootstrap.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/metisMenu/jquery.metisMenu.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/layer/layer.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/hplus.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/contabs.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ lazy_asset('js/plugins/toastr/toastr.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/md5.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/base64.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/vconsole.min.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
    <script src="{{ lazy_asset('js/main.js') }}?time={{config('lazy-admin.timestamp')}}"></script>
</body>
</html>
<script>
    $(function(){
        /** 跳转hash */
        setTimeout(() => {
            try {
                var base64Code = (window.location.hash).substr(1);
                if (base64Code == "") {
                    return false;
                }
                var url =  $.base64.decode(base64Code);
                var currentUrl = window.location.protocol + "//" +window.location.host + window.location.pathname
                if (currentUrl == url) {
                    return false;
                }
                var hash = $.md5(url);
                document.location.hash = "";
                var menuDom = $('.'+hash);
                if (menuDom.length>0) {
                    menuDom.find('span').trigger('click');
                    menuDom.parents('ul').siblings("a").trigger('click')
                } else {
                    var tdom = '<li><a class="J_menuItem '+hash+'" style="display:none;" href="'+url+'" data-index="998">\
                                    <span class="nav-label">上一次打开</span>\
                                </a></li>';
                    $("#side-menu").append(tdom);
                    var tmenuDom = $('.'+hash);
                    tmenuDom.find('span').trigger('click');
                    tmenuDom.remove();
                }
            } catch (error) {
                console.log(error);
            }
        }, 200);
    })
</script>
