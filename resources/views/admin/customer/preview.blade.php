@extends('layouts.admin')

@section('body')
    <div>
        <table class="layui-table" lay-size="sm" lay-skin="line">
            <tbody>
                <tr>
                    <td>顾客ID</td>
                    <td>{{ $data->fid }}</td>
                </tr>
                <tr>
                    <td>微信昵称</td>
                    <td>{{ $data->nickname }}</td>
                </tr>
                <tr>
                    <td>姓名</td>
                    <td>{{ $data->realname }}</td>
                </tr>
                <tr>
                    <td>联系方式</td>
                    <td>{{ $data->telephone }}</td>
                </tr>
                <tr>
                    <td>性别</td>
                    @switch($data->sex) 
                    
                    @case(1)
                        <td>男</td>
                        @break;
                    @case(2)
                        <td>女</td>
                        @break
                    @endswitch
                </tr>
                <tr>
                    <td>是否关注公众号</td>
                    <td>{{ $data->unionid }}</td>
                </tr>
                <tr>
                    <td>关注时间</td>
                    <td></td>
                </tr>
                <tr>
                    <td>关注渠道</td>
                    <td></td>
                </tr>
                <tr>
                    <td>省份</td>
                    <td>{{ $data->province }}</td>
                </tr>
                <tr>
                    <td>城市</td>
                    <td>{{ $data->city }}</td>
                </tr>
                <tr>
                    <td>国家</td>
                    <td>{{ $data->country }}</td>
                </tr>
                <tr>
                    <td>最后访问</td>
                    <td>{{ $data->logined_at }}</td>
                </tr>
                <tr>
                    <td>注册时间</td>
                    <td>{{ $data->created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection