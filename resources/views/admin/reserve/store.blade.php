@extends('layouts.admin')

@section('body')
    <table id="table" lay-filter="customer"></table>
    <script type="text/html" id="toolbar">
        <div class="layui-form" style="float: left" lay-filter="statusbox">
          <input type="radio" name="status" value="1" title="未处理" checked >
          <input type="radio" name="status" value="2" title="已处理">
        </div>  
        <div style="margin-left: 50px; float: left"> 
            <input id="search" class="layui-input" />
        </div>
        <button id="search-btn" style="display: none" lay-event="search"></button>
    </script>
    <script type="text/html" id="rowbar">
      <div class="layui-btn-container">
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">预约信息</button>
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs" lay-event="finish">确认</button>
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs">消息通知</button>
      </div>
    </script>
    <script type="text/html" id="rowbar2">
      <div class="layui-btn-container">
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">预约信息</button>
      </div>
    </script>
@endsection

@section('js')
    <script type="text/javascript">
        var $ = null;
        layui.use(['table', 'form'], function () {
            var table = layui.table;
            var form = layui.form;
            var status = 1;
            $ = layui.$;
           
            var cols = ['', [[ //表头
                    { field: 'rid', title: '预约号', align: 'center', width: 150 },
                    { field: 'fid', title: '用户ID', align: 'center', width: 100 },
                    { field: 'username', title: '姓名', align: 'center' },
                    { field: 'telephone', title: '联系方式', align: 'center', width: 120 },
                    { title: '初次预约', width: 100, align: 'center', templet: function (d) {
                        return d.isft ? '是' : '否';    
                    }},
                    { field: 'rtime', title: '预约时间', width: 180, align: 'center' },
                    { field: 'updated_at', title: '确认时间', width: 180, align: 'center' },
                    { title: '状态', width: 80, align: 'center', templet: function (d) {
                        switch (d.status) {
                            case 1: 
                                return '未处理';
                            case 2: 
                                return '已处理';
                            case 3: 
                                return '已取消';
                        }
                    }},
                    { title: '', width: 200, align: 'center', toolbar: '#rowbar' }
            ]], [[ //表头
                    { field: 'rid', title: '预约号', align: 'center', width: 150 },
                    { field: 'fid', title: '用户ID', align: 'center', width: 100 },
                    { field: 'username', title: '姓名', align: 'center' },
                    { field: 'telephone', title: '联系方式', align: 'center', width: 120 },
                    { title: '初次预约', width: 100, align: 'center', templet: function (d) {
                        return d.isft ? '是' : '否';    
                    }},
                    { field: 'rtime', title: '预约时间', width: 180, align: 'center' },
                    { field: 'updated_at', title: '确认时间', width: 180, align: 'center' },
                    { title: '状态', width: 80, align: 'center', templet: function (d) {
                        switch (d.status) {
                            case 1: 
                                return '未处理';
                            case 2: 
                                return '已处理';
                            case 3: 
                                return '已取消';
                        }
                    }},
                    { title: '', width: 100, align: 'center', toolbar: '#rowbar2' }
            ]]]
           
            var config = {
                elem: '#table',
                toolbar: '#toolbar',
                defaultToolbar: [],
                height: 600,
                url: '/api/admin/customer/reserve?',
                page: true,
                cols: cols[status]
            };
            //  实例化表格
            var tableIns = table.render(config);
            
            $(document).on('change', '#search', function () {
                $('#search-btn').click()
            });
            
            form.on('radio()', function(data){
                status = data.value;
                $('#search-btn').click()
                form.val('statusbox',{
                    status: data.value
                })
            });  
            
            table.on('toolbar', function (obj) {
                switch (obj.event) {
                    case 'search': {
                        let key = $('#search').val()
                        if (key.length < 1) {
                            config.url = '/api/admin/customer/reserve?status=' + status;
                        } else {
                            config.url = '/api/admin/customer/reserve?search=' + key + '&status=' + status;
                        }
                        config.cols = cols[status]
                        tableIns.reload(config)
                        $('#search').val(key)
                        break;
                    }
                }
            })
            
            table.on('tool', function (obj) {
                let event = obj.event;
                let data = obj.data;
                switch (event) {
                    case 'edit': {
                        layer.open({
                            title: '[预约号]' + data.rid,
                            type: 2,
                            area: ['400px', '580px'],
                            content: "/{{ env('ADMIN_PREFIX', '_admin') }}/customer/reserve?action=edit&rid=" + data.rid,
                            btn: ['保存'],
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
                    
                    case 'finish': {
                        layer.confirm('是否确认预约['+ data.rid +']?', {icon: 3, title:'提示'}, function(index){
                            $.ajax({
                                type: 'PUT',
                                url: '/api/admin/customer/reserve?action=finish',
                                contentType:"application/json;charset=utf-8",
                                async: false,
                                data: JSON.stringify({
                                    rid: data.rid,
                                    fid: data.fid
                                }),
                                success: function (response) {
                                    layer.msg(response.message)
                                }
                            })
                            layer.close(index);
                        });
                        break;
                    }
                }
            });
        });
    </script>
@endsection

@section('css')
    <style type="text/css">
        #search {
            width: 200px;
            float: left;
            height: 30px;
        }
    </style>
@endsection