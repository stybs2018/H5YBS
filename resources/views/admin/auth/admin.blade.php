@extends('layouts.admin')

@section('body')
    <table id="table" lay-filter="admin"></table>
    <script type="text/html" id="toolbar">
      <div class="layui-btn-container">
        <button type="button" class="layui-btn layui-btn layui-btn-sm" lay-event="create">创建</button>
      </div>
    </script>
    <script type="text/html" id="rowbar">
      <div class="layui-btn-container">
        <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-sm" lay-event="password">修改密码</button>
        <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-sm" lay-event="update">更新</button>
        <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-sm" lay-event="delete">删除</button>
      </div>
    </script>
@endsection

@section('js')
    <script type="text/javascript">
        var $ = null;
        layui.use('table', function () {
           var table = layui.table;
           $ = layui.$;
           
           var config = {
                elem: '#table',
                toolbar: '#toolbar',
                defaultToolbar: [],
                height: 600,
                url: '/api/admin/admin',
                page: true,
                cols: [[ //表头
                    { field: 'id', title: 'ID', align: 'center', width: 50 },
                    { field: 'username', title: '账号' },
                    { field: 'nickname', title: '名称' },
                    { field: 'rolename', title: '管理组', align: 'center' },
                    { field: 'status', title: '状态', align: 'center', width: 100, templet: function (d) {
                        return d.status == 1 ? '正常' : '禁用'   
                    }},
                    { field: 'logined_at', title: '最后登录', width: 180, align: 'center' },
                    { title: '操作', align: 'center', width: 210, toolbar: '#rowbar' }
                ]]
            };
            //  实例化表格
            var tableIns = table.render(config);
            
            table.on('toolbar', function (obj) {
                switch (obj.event) {
                    case 'create': {
                        layer.open({
                            title: '创建管理员',
                            type: 2,
                            area: ['800px', '600px'],
                            content: "/{{ env('ADMIN_PREFIX', '_admin') }}/admin?action=create",
                            btn: ['创建'],
                            shadeClose: true,
                            yes: function (index, layero) {
                                var body = layer.getChildFrame('body', index);
                                var iframeWin = window[layero.find('iframe')[0]['name']];
                                if (iframeWin.create()) {
                                    layer.close(index);
                                    tableIns.reload(config)
                                }
                            }
                        })
                        break;
                    }
                }
            })
            
            table.on('tool', function (obj) {
                let event = obj.event;
                let data = obj.data;
                
                switch (event) {
                    case 'delete': {
                        layer.confirm('是否删除这个管理员', { title: '提示' },function (index) {
                            $.ajax({
                                type: 'DELETE',
                                url: '/api/admin/admin',
                                contentType: "application/json",
                                dataType: "json",
                                async: false,
                                data: JSON.stringify({
                                    role: [data.id]
                                }),
                                success: function (response) {
                                    if (response.code === 3001) {
                                        layer.msg('删除成功', { icon: 1, time: 1000 });
                                        tableIns.reload(config)
                                    } else {
                                        layer.msg(response.message, { icon: 2 });
                                    }
                                }
                            })
                            layer.close(index)
                        });
                        break;
                    }
                    
                    case 'password': {
                         layer.open({
                            title: '修改密码',
                            type: 2,
                            area: ['300px', '320px'],
                            content: "/{{ env('ADMIN_PREFIX', '_admin') }}/admin?action=update&id="+data.id,
                            btn: ['确定'],
                            shadeClose: true,
                            yes: function (index, layero) {
                                var body = layer.getChildFrame('body', index);
                                var iframeWin = window[layero.find('iframe')[0]['name']];
                                if (iframeWin.update()) {
                                    layer.close(index);
                                    tableIns.reload(config)
                                }
                            }
                        })
                        break;
                    }
                    
                    case 'update': {
                        layer.open({
                            title: '更新管理员',
                            type: 2,
                            area: ['800px', '600px'],
                            content: "/{{ env('ADMIN_PREFIX', '_admin') }}/admin?action=update&id="+data.id,
                            btn: ['更新'],
                            shadeClose: true,
                            yes: function (index, layero) {
                                var body = layer.getChildFrame('body', index);
                                var iframeWin = window[layero.find('iframe')[0]['name']];
                                if (iframeWin.update()) {
                                    layer.close(index);
                                    tableIns.reload(config)
                                }
                            }
                        })
                        break;
                    }
                }
            });
        });
    </script>
@endsection