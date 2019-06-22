<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>登录 - 牙博士</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="/layuiadmin/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/admin.css" media="all">
  <link rel="stylesheet" href="/layuiadmin/style/login.css" media="all">
</head>

<body>

  <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
    <div class="layadmin-user-login-main">
      <div class="layadmin-user-login-box layadmin-user-login-header">
        <h2>牙博士</h2>
        <p>汕头牙博士H5平台后台管理系统</p>
      </div>
      <div class="layadmin-user-login-box layadmin-user-login-body layui-form" lay-filter="loginform">
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
          <input type="text" name="username" id="LAY-user-login-username" lay-verify="required" placeholder="用户名"
            class="layui-input">
        </div>
        <div class="layui-form-item">
          <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
          <input type="password" name="password" id="LAY-user-login-password" lay-verify="required" placeholder="密码"
            class="layui-input">
        </div>
        <div class="layui-form-item">
          <div class="layui-row">
            <div class="layui-col-xs7">
              <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                for="LAY-user-login-vercode"></label>
              <input type="text" name="vercode" id="LAY-user-login-vercode" lay-verify="required" placeholder="验证码"
                class="layui-input">
            </div>
            <div class="layui-col-xs5">
              <div style="margin-left: 10px;">
                <img src="/{{ env('ADMIN_PREFIX', 'admin') }}/captcha?_token={{ csrf_token() }}" class="layadmin-user-login-codeimg" id="vercode">
              </div>
            </div>
          </div>
        </div>
        <div class="layui-form-item">
          <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="LAY-user-login-submit">登 录</button>
        </div>
      </div>
    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>© 2018 <a>汕头牙博士口腔企划部</a></p>
    </div>
  </div>

  <script src="/layuiadmin/layui/layui.js"></script>
  <script type="text/javascript" src="/js/lib.js"></script>
  <script>
    layui.config({
      base: '/layuiadmin/' 
    }).extend({
      index: 'lib/index' 
    }).use(['index', 'form'], function () {
      var $ = layui.$
        , form = layui.form

      form.render();

      $('#vercode').click(() => {
        $('#vercode').attr('src', "/{{ env('ADMIN_PREFIX', 'admin') }}/captcha?_token={{ csrf_token() }}&"+Math.random())
      })

      //提交
      form.on('submit(LAY-user-login-submit)', function (obj) {
        $.ajax({
          url: '/api/admin/login?_token={{ csrf_token() }}',
          type: 'POST',
          contentType:"application/json;charset=utf-8",
          data: JSON.stringify(obj.field),
          success: function (response) {
            if (response.code == 2000) {
              layer.msg(response.message, { icon: 1, time: 1000 }, function () {
                PostForm("/{{ env('ADMIN_PREFIX', 'admin') }}/login", "{{ csrf_token() }}", {
                  access_token: response.access_token
                })
              })
            } else {
              layer.msg(response.message, { icon: 2, time: 1000 }, function () {
                form.val('loginform', { vercode: null })
                $('#vercode').attr('src', "/{{ env('ADMIN_PREFIX', 'admin') }}/captcha?_token={{ csrf_token() }}&"+Math.random())
              })
            }
          }
        })
      });
    });
  </script>
</body>

</html>