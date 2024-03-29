<!DOCTYPE html>
<html>

<head>
    <title>汕头牙博士</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" type="text/css" />
    <link rel="stylesheet" href="/css/admin/index.css?{{ time() }}" type="text/css" />
</head>

<body class="layui-layout-body">

    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header">
                <!-- 顶部 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="/" target="_blank" title="前台">
                            <i class="layui-icon layui-icon-website"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>
                        </a>
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right paddingRight40" lay-filter="layadmin-layout-right">
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect="">
                        <a href="javascript:;">
                            <cite>{{ $admin->nickname }}</cite>
                            <span class="layui-nav-more"></span>
                        </a>
                        <dl class="layui-nav-child layui-anim layui-anim-upbit" style="width: 120px; text-align: center;">
                            <dd><a lay-href="set/user/info.html">个人中心</a></dd>
                            <dd><a lay-href="set/user/password.html">修改密码</a></dd>
                            <hr>
                            <dd style="text-align: center;"><a href="/{{ env('ADMIN_PREFIX', '_admin') }}/logout">退出</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>

            <!-- 侧边 -->
            <div class="layui-side layui-side-menu">
                <div class="layui-side-scroll">
                    <div class="layui-logo" lay-href="{{ env('ADMIN_PREFIX', '_admin') }}/workbench">
                        <span>牙博士H5平台</span>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                        lay-filter="layadmin-system-side-menu">
                        {!! $menu !!}
                    </ul>
                </div>
            </div>

            <!-- 页面 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd id="closeThisTabs" layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a>
                                </dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li lay-id="{{ env('ADMIN_PREFIX', '_admin') }}/workbench"
                            lay-attr="{{ env('ADMIN_PREFIX', '_admin') }}/workbench" class="layui-this"><i
                                class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>


            <!-- 主体 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe src="{{ env('ADMIN_PREFIX', '_admin') }}/workbench" frameborder="0"
                        class="layadmin-iframe"></iframe>
                </div>
            </div>

            <!-- 移动设备下遮罩 -->
            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div>

    <script src="/layuiadmin/layui/layui.js"></script>
    <script>
        var $ = null;
        layui.config({
            base: '/layuiadmin/'
        }).extend({
            index: 'lib/index'
        }).use(['index'], function () {
            $ = layui.$;
            $('#logout').click(() => {
                layer.msg('登出成功', {
                    offset: '50px',
                    icon: 1,
                    time: 1000
                }, function () {
                    window.location.href = "/{{ env('ADMIN_PREFIX', '_admin') }}/logout";
                });
            });
        });
        function closeThisTabs() {
            $('#closeThisTabs').click();
        }
    </script>
</body>

</html>