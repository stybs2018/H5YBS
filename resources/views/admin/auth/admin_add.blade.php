@extends('layouts.admin')

@section('body')
    <div class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">账号</label>
            <div class="layui-input-block">
                <input type="text" id="username" name="username" class="layui-input" lay-verify="required|username" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">管理组</label>
            <div class="layui-input-block">
                <select name="role" id="role" lay-filter="role" lay-verify="required">
                    {!! $role !!}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input type="text" id="nickname" name="nickname" class="layui-input" lay-verify="required" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" id="password" name="password" class="layui-input" lay-verify="required|password" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" name="telephone" id="telephone" class="layui-input" lay-verify="telephone"/>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <select name="status" lay-filter="status">
                    <option value="1">启用</option>
                    <option value="0">禁用</option>
                </select>
            </div>
        </div>
         <div class="layui-form-item" style="display: none">
            <div class="layui-input-block">
                <button id="submit" class="layui-btn" lay-submit lay-filter="*">立即提交</button>
            </div>
          </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        var $ = null;
        var falg = false;
        layui.use('form', function () {
            $ = layui.$;
            var form = layui.form;
            
            // 表单校验
            form.verify({
                username: function (value, item) {
                    if (value.length < 6) {
                        return '账号长度必须大于6位';
                    }
                }
                ,password: [/^[\S]{6,12}$/,'密码必须6到12位，且不能出现空格']
                , telephone: function (value, item) {
                    let reg=/^[1][3,4,5,7,8][0-9]{9}$/;
                    if (value.length > 0 && !reg.test(value)) {
                        return '请输入正确的手机号'
                    }
                }
            })
            
            // 表单提交
            form.on('submit(*)', function(data){
                $.ajax({
                    type: 'POST',
                    url: '/api/admin/admin',
                    contentType:"application/json;charset=utf-8",
                    async: false,
                    data: JSON.stringify(data.field),
                    success: function (response) {
                        if (response.code == 3001) {
                            layer.msg('创建成功', { icon: 1, time: 500 })
                            falg = true;
                        } else {
                            layer.msg(response.message, { icon: 5, time: 500 })
                        }
                    }
                })
                return false; 
            });
        });
        
        function create()
        {
            $('#submit').click();
        
            return falg;
        }
    </script>
@endsection
