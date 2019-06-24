@extends('layouts.app')

@section('title', '预约中心')

@section('body')
<div class="box">
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label class="weui-label">姓名</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="name" type="text" placeholder="请输入您的姓名">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label class="weui-label">年龄</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="age" type="number" pattern="[0-9]*" placeholder="请输入您的年龄">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd"><label for="" class="weui-label">性别</label></div>
            <div class="weui-cell__bd">
                <input class="weui-input" id='sex' type="text" value="男">
            </div>
        </div>
    </div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">相似症状</label>
            </div>
            <div class="weui-cell__bd">
                <div class="weui-input" id='desc1' disabled="true" type="text">点击选择</div>
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">症状描述</label>
            </div>
            <div class="weui-cell__bd">
                <textarea class="weui-textarea" id="desc2" placeholder="描述您的症状, 方便牙博士为您预约合适的医生" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="weui-cells weui-cells_form">
        <div class="weui-cell weui-cell_select weui-cell_select-before">
            <div class="weui-cell__hd">
                <select class="weui-select" name="select2">
                    <option value="1">+86</option>
                </select>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" id="telephone" type="number" pattern="[0-9]*" placeholder="请输入联系方式">
            </div>
        </div>
        <div class="weui-cell">
            <div class="weui-cell__hd">
                <label for="" class="weui-label">预约时间</label>
            </div>
            <div class="weui-cell__bd">
                <input class="weui-input" id='yytime' type="text" value="">
            </div>
        </div>
        <div class="weui-cell weui-cell_switch">
            <div class="weui-cell__bd">是否初次预约</div>
            <div class="weui-cell__ft">
                <input class="weui-switch" id="isft" type="checkbox" checked="checked">
            </div>
        </div>
    </div>
    <div class="weui-btn-area">
        <a class="weui-btn weui-btn_primary" href="javascript:" id="reserve">预约</a>
    </div>
    <div class="weui-cells__tips">
        成功提交预约申请后牙博士将会联系您, 确认安排预约时间, 最终以微信的形式通知到您。 
    </div>
</div>
<div class="weui-footer">
  <p class="weui-footer__text">Copyright © 2019 汕头牙博士口腔门诊部</p>
</div>
@endsection

@section('js')
    <script type="text/javascript">
        var isft = true;
        $("#yytime").datetimePicker();
        $("#sex").picker({
            title: "请选择您的性别",
            cols: [
                {
                    textAlign: 'center',
                    values: ['男', '女']
                }
              ]
        });
        $('#desc1').click(function () {
           $.modal({
                title: '相似症状',
                text: '<div id="desc1-box" class="weui-grids"><a class="weui-grid js_grid"><img src="/img/reserve/1.jpg" value="龅牙" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/2.jpg" value="地包天" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/3.jpg" style="margin-right: 0" value="完美脸型" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/4.jpg" value="牙齿不齐" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/5.jpg" value="牙齿开颌" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/6.jpg" style="margin-right: 0" value="牙齿缺损" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/7.jpg" value="牙齿稀疏" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/8.jpg" value="牙周炎" alt=""></a><a class="weui-grid js_grid"><img src="/img/reserve/9.jpg" style="margin-right: 0" value="蛀牙" alt=""></a></div>',
                buttons: []
            }); 
        });
        $('#age').change(function () {
           if ($(this).val() > 90) {
                $(this).val('')
           } 
        });
        $('#isft').click(function() {
           isft = !isft; 
        });
        $(document).on('click', '#desc1-box a', function () {
            $('#desc1').text($(this).children().attr('value'));
            $.closeModal(); 
        });
        $('#reserve').click(function () {
            let params = {};
            params.name = $('#name').val();
            params.age = $('#age').val();
            params.sex = $('#sex').val();
            params.desc1 = $('#desc1').text();
            params.desc2 = $('#desc2').text();
            params.telephone = $('#telephone').val();
            params.time = $('#yytime').val();
            params.isft = isft;
            
            if (params.desc1 === '点击选择') {
                params.desc1 = '';
            } 
            
            if (params.name.length < 1) {
                $.alert('请填写您的姓名');
                return false;
            }
            
            if (params.telephone.length < 1) {
                $.alert('请填写您的联系方式');
                return false;
            } else {
                let reg = /^[1][3,4,5,7,8][0-9]{9}$/;
                if (!reg.test(params.telephone)) {
                    $.alert('请输入正确的手机号码');
                    return false;
                }
            }
            
            $.ajax({
                type: 'POST',
                url: '/reserve',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType:"application/json;charset=utf-8",
                data: JSON.stringify(params),
                success: function (response) {
                    if (response.code === 3003) {
                        $.alert(response.message, function () {
                            window.location.href = '/my/reserve';
                        })
                    } else if (response.code === 3005) {
                        $.alert(response.message, function () {
                            window.location.href = '/my/reserve';
                        })
                    } else {
                        $.alert(response.message, function () {
                            window.location.href = 'tel:0754-88896666'; 
                        });
                    }
                }
            })    
        
        });
    </script>
@endsection

@section('css')
    <style type="text/css">
    
        .box {
            padding: 0;
            margin: 0;
        }
        
        .weui-footer {
            padding-top: 90px;
            padding-bottom: 10px;
        }
        
        .weui-footer p {
            font-size: .7rem;
        }
        
        #desc1-box:before {
            border: none!important;
        }
        
        #desc1-box:after {
            border: none!important;
        }
        
        #desc1-box a {
            width: 32.5%;
            padding: 0!important;
            margin-right: 2px;
        }
        
        #desc1-box a:before {
            border: none!important;
        }
        
        #desc1-box a:after {
            border: none!important;
        }
        
        #desc1-box img {
            width: 100%!important;
            padding: 0!important;
        }
        
    </style>
@endsection