@extends('layouts.app')

@section('title', '我的预约')

@section('body')
    @if (count($data)) 
        <div class="box">
        <div class="weui-cells">
            @foreach ($data as $i)
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>&nbsp;&nbsp;预约号: &nbsp;&nbsp;{{ $i->rid }}</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;姓名: &nbsp;&nbsp;{{ $i->username }}</p>
                        <p>联系电话: &nbsp;&nbsp;{{ $i->telephone }}</p>
                        @if ($i->rtime != null)
                            <p>预约时间: &nbsp;&nbsp;{{ $i->rtime }}</p>
                        @endif
                        @if ($i->desc2 != null)
                            <p>症状描述:</p>
                            <textarea class="yydesc">
                                &nbsp;&nbsp;{{ $i->desc2 }}
                            </textarea>
                        @endif
                        @switch($i->status)
                            @case(1)
                                <p>预约状态:&nbsp;&nbsp;待确认</p>
                                @break
                            
                            @case(2)
                                <p>预约状态:&nbsp;&nbsp;已确认</p>
                                @break
                        @endswitch
                        <p>创建时间: &nbsp;&nbsp;{{ $i->created_at }}</p>
                        @if ($i->status == 1) 
                            <div class="weui-btn weui-btn_mini weui-btn_primary" id="cancelyy" yyid="{{ $i->rid }}" style="float: right">取消预约</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @else 
        <p class="noyy">无当前预约信息</p>
    @endif
@endsection

@section('css')
    <style type="text/css">
        .box {
            padding: 0 10px;
        }
        
        .yydesc {
            font-size: .8rem;
            color: #d3d3d3;
            width: 100%;
            border: 0;
            padding: 0;
            margin: 0;
        }
        
        .noyy {
            color: #d3d3d3;
            text-align: center;
            margin: 50%;
        }
    </style>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).on('click', '#cancelyy', () => {
            $.confirm("是否取消此预约["+ $('#cancelyy').attr('yyid') +"]", function() {
                $.ajax({
                    type: 'POST',
                    url: '/reserve/cancel',
                    contentType:"application/json;charset=utf-8",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $.alert(response.message);
                        window.location.reload();
                    }
                })  
            }, function() {
            //点击取消后的回调函数
            });
        });
    </script>
@endsection