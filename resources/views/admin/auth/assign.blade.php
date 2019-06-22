@extends('layouts.admin')

@section('body')
<div style="height: 500px; overflow-y:scroll;">
    <div class="layui-collapse">
      <div class="layui-colla-item">
        <h2 class="layui-colla-title">菜单</h2>
        <div class="layui-colla-content layui-show">
            <div class="eleTree" id="menu"></div>
        </div>
      </div>
      <div class="layui-colla-item">
        <h2 class="layui-colla-title">操作</h2>
        <div class="layui-colla-content">
            <div class="eleTree" id="api"></div>
        </div>
      </div>
      <div class="layui-colla-item">
        <h2 class="layui-colla-title">页面路由</h2>
        <div class="layui-colla-content">
            <div class="eleTree" id="page"></div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        var menu = null
        var page = null
        var api = null
        var $ = null
        var layer;
        layui.use(['eleTree', 'element', 'layer'], function () {
            var eleTree = layui.eleTree;
            $ = layui.$
            larer = layui.layer
            menu = eleTree.render({
                elem: '#menu',
                data: @json($Menu),
                defaultCheckedKeys: @json($Assign),
                showCheckbox: true,
            });
            
            page = eleTree.render({
                elem: '#page',
                data: @json($Page),
                defaultCheckedKeys: @json($Assign),
                showCheckbox: true,
            });
            
            api = eleTree.render({
                elem: '#api',
                data: @json($Action),
                defaultCheckedKeys: @json($Assign),
                showCheckbox: true,
            });
        });
        
        function assign()
        {
            let falg = false;
            let assign = [];
            menu.getChecked().map(i => { assign.push(i.id) });
            api.getChecked(true).map(i => { assign.push(i.id) });
            page.getChecked(true).map(i => { assign.push(i.id) });
            $.ajax({
                type: 'POST',
                url: '/api/admin/rm/assign',
                contentType: "application/json",
                dataType: "json",
                async: false,
                data: JSON.stringify({
                    id: "{{ $id }}",
                    assign: assign
                }),
                success: function (response) {
                    if (response.code === 100000) {
                        layer.msg('授权成功', { icon: 1, time: 1000 });
                        falg = true;
                    } else {
                        layer.msg(response.message, { icon: 2, time: 2000 });
                    }
                }
            })
            return falg;
        }
    </script> 
@endsection

@section('css')
    <link rel="stylesheet" href="/layuiadmin/style/eleTree.css" type="text/css" />
@endsection