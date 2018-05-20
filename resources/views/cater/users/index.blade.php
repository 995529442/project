@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
    <form method="get" action="{{ route('cater.users.index') }}">
        {{csrf_field()}}
        <div class="demoTable formn">
            微信名称：
            <div class="layui-inline">
               <input type="text" name="weixin_name" id="weixin_name" autocomplete="off" class="layui-input" value="{{$weixin_name}}">
            </div>
            联系方式：
            <div class="layui-inline">
               <input type="text" name="mobile" id="mobile" autocomplete="off" class="layui-input" value="{{$mobile}}">
            </div>
            性别：
            <div class="layui-inline">
              <select name="sex" id="sex" class="layui-input" style="width:150px;">
                  <option value="0">全部</option>
                  <option value="1" @if($sex ==1 ) selected @endif>男</option>
                  <option value="2" @if($sex ==2 ) selected @endif>女</option>
              </select>
            </div>           
            <button type="submit" class="layui-btn layui-btn-normal button">查询</button>
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
                <td style="width:6%;">联系方式</td>
                <td style="width:4%;">性别</td>
                <td style="width:13%;">用户地址</td>
                <td style="width:8%;">完成订单总数量</td>                
                <td style="width:8%;">订单总数量</td>
                <td style="width:8%;">完成订单总金额</td>                 
            </tr>
            </thead>
            <tbody>
              @foreach($user_info as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->weixin_name}}</td>
                    <td><img src='{{$v->headimgurl}}' style="width:50px;height:50px;" /></td> 
                    <td>{{$v->openid}}</td>                               
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
</script>
@endsection