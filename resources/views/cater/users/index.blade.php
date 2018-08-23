@extends('layouts.app')
@section('content')
    <style>
        th, td, #page {
            text-align: center;
        }
    </style>
    <div style="padding: 15px;">
        <form class="layui-form" action="{{ route('cater.users.index') }}" method="get">
            {{csrf_field()}}
            <div class="layui-form-item" style="display:inline-block;margin-left:-40px;">
                <label class="layui-form-label">微信名称：</label>
                <div class="layui-input-block">
                    <input type="text" name="weixin_name" id="weixin_name" autocomplete="off" class="layui-input"
                           value="{{$weixin_name}}">
                </div>
            </div>

            <div class="layui-form-item" style="display:inline-block;margin-left:-30px;">
                <label class="layui-form-label">联系方式：</label>
                <div class="layui-input-block">
                    <input type="text" name="mobile" id="mobile" autocomplete="off" class="layui-input"
                           value="{{$mobile}}">
                </div>
            </div>

            <div class="layui-form-item" style="display:inline-block;margin-left:-40px;">
                <label class="layui-form-label">性别：</label>
                <div class="layui-input-block">
                    <select name="sex" id="sex" lay-filter="sex">
                        <option value="0">全部</option>
                        <option value="1" @if($sex ==1 ) selected @endif>男</option>
                        <option value="2" @if($sex ==2 ) selected @endif>女</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item" style="display:inline-block;margin-left:-100px;">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-filter="demo1">查询</button>
                </div>
            </div>
        </form>
        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <td style="width:5%;">编号</td>
                    <td style="width:9%;">微信名称</td>
                    <td style="width:6%;">头像</td>
                    <td style="width:12%;">微信openid</td>
                    <td style="width:6%;">购物币金额</td>
                    <td style="width:6%;">联系方式</td>
                    <td style="width:4%;">性别</td>
                    <td style="width:13%;">用户地址</td>
                    <td style="width:8%;">完成订单总数量</td>
                    <td style="width:8%;">订单总数量</td>
                    <td style="width:8%;">完成订单总金额</td>
                    <td style="width:8%;">操作</td>
                </tr>
                </thead>
                <tbody>
                @foreach($user_info as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->weixin_name}}</td>
                        <td><img src='{{$v->headimgurl}}' style="width:50px;height:50px;"/></td>
                        <td>{{$v->openid}}</td>
                        <td><a href="{{ route('cater.users.currency_log') }}?user_id={{$v->id}}"
                               style="color:#1E9FFF;">{{$v->currency_money}}</a></td>
                        <td>{{$v->mobile}}</td>
                        <td>
                            @if($v->sex == 1)
                                男
                            @elseif($v->sex == 2)
                                女
                            @else
                                未知
                            @endif
                        </td>
                        <td>{{$v->province}}{{$v->city}}{{$v->country}}{{$v->address}}</td>
                        <td>{{$v->order_complete_num}}</td>
                        <td>{{$v->order_num}}</td>
                        <td>{{$v->total_money}}</td>
                        <td>
                            <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="add_currency({{$v->id}})">
                                购物币充值
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="page">
            {{$user_info->links()}}
        </div>
    </div>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
    <script>
        function add_currency(user_id) {
            layer.open({
                type: 2,
                title: false,
                shadeClose: false,
                shade: 0.1,
                area: ['500px', '25%'],
                content: '{{ route("cater.users.add_currency")}}?user_id=' + user_id,
                end: function () {

                }
            });
        }
    </script>
@endsection