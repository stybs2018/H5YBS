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
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs">预约信息</button>
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs">确认</button>
            <button type="button" class="layui-btn layui-btn layui-btn-normal layui-btn-xs">消息通知</button>
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
           
            var config = {
                elem: '#table',
                toolbar: '#toolbar',
                defaultToolbar: [],
                height: 600,
                url: '/api/admin/customer/reserve?',
                page: true,
                cols: [[ //表头
                    { field: 'rid', title: '预约号', align: 'center', width: 150 },
                    { field: 'fid', title: '用户ID', align: 'center', width: 180 },
                    { field: 'username', title: '姓名', align: 'center' },
                    { field: 'telephone', title: '联系方式', align: 'center', width: 120 },
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
                    { title: '', width: 200, toolbar: '#rowbar' }
                  ]]
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