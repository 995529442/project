@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style>     
<blockquote class="layui-elem-quote layui-text">
  商品信息
</blockquote>
<div style="padding: 15px;">
      <form method="get" action="{{ route('cater.goods.index') }}">
        {{csrf_field()}}
        <div class="demoTable formn">
            商品名称：
            <div class="layui-inline">
               <input type="text" name="good_name" id="good_name" autocomplete="off" class="layui-input" value="{{$good_name}}">
            </div>           
            <button type="submit" class="layui-btn layui-btn-normal button">查询</button>
            <a href="{{ route('cater.goods.add_goods') }}" class="layui-btn layui-btn-normal button">新增</a>
        </div>          
    </form>
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <td style="width:5%;">编号</td>
                <td style="width:15%;">商品名称</td>
                <td style="width:10%;">缩略图</td>
                <td style="width:10%;">原价</td>
                <td style="width:10%;">现价</td>
                <td style="width:20%;">介绍</td>
                <td style="width:15%;">商品状态</td>
                <td style="width:20%;">操作</td>
            </tr>
            </thead>
            <tbody>
                @foreach($goods_info as $v)
                    <tr>                  
                        <td>{{$v->id}}</td>
                        <td>{{$v->good_name}}</td>
                        <td>{{$v->thumb_img}}</td>
                        <td>{{$v->original_price}}</td>
                        <td>{{$v->now_price}}</td>
                        <td>{{$v->introduce}}</td>
                        <td>
                            @if($v->is_hot == 1)
                               <button class="layui-btn layui-btn-normal layui-btn-sm">热卖</button>
                            @else
                               <button class="layui-btn layui-btn-primary layui-btn-sm">热卖</button>
                            @endif
                            @if($v->is_new == 1)
                               <button class="layui-btn layui-btn-warm layui-btn-sm">上新</button>
                            @else
                               <button class="layui-btn layui-btn-primary layui-btn-sm">上新</button>
                            @endif
                            @if($v->is_recommend == 1)
                               <button class="layui-btn layui-btn-danger layui-btn-sm">推荐</button>
                            @else
                               <button class="layui-btn layui-btn-primary layui-btn-sm">推荐</button>
                            @endif                            
                        </td>                        
                        <td>
                        	<a href="{{ route('cater.goods.add_goods') }}?goods_id={{$v->id}}" class="layui-btn layui-btn-normal button">编辑</a>
                        	<button class="layui-btn layui-btn-danger button" onclick="good_operate({{$v->id}},'del')">删除</button>
                        </td>                   
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div> 
    <div id="page">
        {{$goods_info->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
</script>
@endsection