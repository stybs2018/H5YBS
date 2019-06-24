@extends('layouts.admin')

@section('body')
    <table id="table" lay-filter="customer"></table>
    <script type="text/html" id="toolbar">
        <div> 
            <input id="search" class="layui-input" />
        </div>
        <button id="search-btn" style="display: none" lay-event="search"></button>
    </script>
    <script type="text/html" id="rowbar">
      <div class="layui-btn-container">
        <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs" lay-event="preview">详情</button>
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
                height: 800,
                url: '/api/admin/customer',
                page: true,
                cols: [[ //表头
                    { field: 'fid', title: 'ID', align: 'center', width: 200 },
                    { field: 'nickname', title: '微信昵称' },
                    { title: '微信头像', width: 90, align: 'center', templet: function (d) {
                        if (d.avatar != undefined) {
                            return "<img src="+d.avatar+" width=25 height=25 >"
                        }    
                    }},
                    { field: 'realname', title: '姓名', align: 'center' },
                    { field: 'telephone', title: '联系方式', align: 'center' },
                    { field: 'logined_at', title: '最后访问', width: 160, align: 'center' },
                    { title: '', width: 80, toolbar: '#rowbar', align: 'center' }
                  ]]
            };
            //  实例化表格
            var tableIns = table.render(config);
            
            $(document).on('change', '#search', function () {
                $('#search-btn').click()
            });
            
            table.on('toolbar', function (obj) {
                switch (obj.event) {
                    case 'search': {
                        let key = $('#search').val()
                        if (key.length < 1) {
                            config.url = '/api/admin/customer';
                        } else {
                            config.url = '/api/admin/customer?search=' + key
                        }
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
                    case 'preview': {
                        layer.open({
                            title: data.fid,
                            type: 2,
                            area: ['400px', '580px'],
                            content: "/{{ env('ADMIN_PREFIX', '_admin') }}/customer?action=preview&id=" + data.fid,
                            shadeClose: true
                        })
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