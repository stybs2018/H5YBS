@extends('layouts.admin')

@section('body')
    <div class="layui-form" lay-filter="role">
        <input type="text" name="id" hidden/>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" id="name" name="name" placeholder=""  class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <select id="status" name="status" lay-filter="status">
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var form = null;
        var $ = null;
        layui.use('form', function () {
            form = layui.form;
            $ = layui.$;
            
            form.val('role', @json($data));
        });
        
        function update()
        {
            let falg = false
            $.ajax({
                url: '/api/admin/admin/role',
                type: 'POST',
                contentType:"application/json;charset=utf-8",
                async: false,
                data: JSON.stringify({
                    name: $('#name').val(),
                    status: $('#status option:selected').val()
                }),
                success: function (response) {
                    if (response.code == 3001) {
                        layer.msg('创建成功', { icon: 1, time: 500 })
                        falg = true;
                    } else {
                        layer.msg(response.message, { icon: 5, time: 500 })
                    }
                }
            })
            return falg;
        }
    </script>
@endsection

@section('css')
    <style type="text/css">
        .layui-form-label {
            width: 40px;
            text-align: center;
        }
        
        .layui-input-block {
            margin-left: 70px;
        }
    </style>
@endsection