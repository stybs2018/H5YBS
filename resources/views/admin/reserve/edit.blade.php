@extends('layouts.admin')

@section('body')
    <div class="layui-form" lay-filter="*">
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
              <input type="text" name="username" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">年龄</label>
            <div class="layui-input-block">
              <input type="number" name="age" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
              <select name="sex">
                <option value="男">男</option>
                <option value="女">女</option>
              </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">相似症状</label>
            <div class="layui-input-block">
              <input type="text" name="desc1" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">症状描述</label>
            <div class="layui-input-block">
                <textarea name="desc2" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">预约时间</label>
            <div class="layui-input-block">
                <input type="text" name="rtime" class="layui-input" id="yytime">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系方式</label>
            <div class="layui-input-block">
              <input type="text" name="telephone" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item" style="display: none">
            <div class="layui-input-block">
              <button class="layui-btn" lay-submit lay-filter="*" id="submit">立即提交</button>
            </div>
          </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var $ = null;
        var falg = false;
        layui.use(['form', 'laydate'], function () {
            $ = layui.$;
            var form = layui.form;
            var laydate = layui.laydate;
            
            laydate.render({
                elem: '#yytime',
                type: 'datetime'
            });
            
            form.val('*', @json($data))
            
            form.on('submit(*)', function(data){
                $.ajax({
                    type: 'PUT',
                    url: '/api/admin/customer/reserve?id='+{{ $id }},
                    contentType:"application/json;charset=utf-8",
                    async: false,
                    data: JSON.stringify(data.field),
                    success: function (response) {
                        if (response.code == 3001) {
                            layer.msg('更新成功', { icon: 1, time: 500 })
                            falg = true;
                        } else {
                            layer.msg(response.message, { icon: 5, time: 500 })
                        }
                    }
                })
                return false;
            });
        });
        
        function update() {
            $('#submit').click();
            return falg;
        }
    </script>
@endsection

@section('css')
    <style type="text/css">
        .layui-form-label {
            width: 60px;
            text-align: right;
        }
        
        .layui-input-block {
            margin-left: 90px;
        }
    </style>
@endsection