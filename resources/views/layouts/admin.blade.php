<!DOCTYPE html>
<html>
    <head>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
        @yield('css')
    </head>
    <body class="layui-layout-body" style="padding: 15px; overflow-y: auto;">
        @section('body')
        
        @show
        <script src="/layuiadmin/layui/layui.js?1"></script>
        @yield('js')
    </body>
</html>