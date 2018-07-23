@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
      <form method="get" action="{{ route('cater.goods.index') }}">
        {{csrf_field()}}
        <div class="demoTable formn">
            商品名称：
            <div class="layui-inline">
               <input type="text" name="good_name" id="good_name" autocomplete="off" class="layui-input" value="{{$good_name}}">
            </div>
            商品状态：
            <div class="layui-inline">
              <select name="status" id="status" class="layui-input" style="width:150px;">
                  <option value="0">全部</option>
                  <option value="1" @if($status ==1 ) selected @endif>热卖</option>
                  <option value="2" @if($status ==2 ) selected @endif>新品</option>
                  <option value="3" @if($status ==3 ) selected @endif>推荐</option>
              </select>
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
                <td style="width:10%;">商品名称</td>
                <td style="width:10%;">所属分类</td>
                <td style="width:10%;">缩略图</td>
                <td style="width:5%;">库存</td>
                <td style="width:5%;">销量</td>
                <td style="width:5%;">原价</td>
                <td style="width:5%;">现价</td>
                <td style="width:10%;">介绍</td>
                <td style="width:5%;">上架状态</td>                
                <td style="width:10%;">商品状态</td>
                <td style="width:10%;">操作</td>
            </tr>
            </thead>
            <tbody>
                @foreach($goods_info as $v)
                    <tr>                  
                        <td>{{$v->id}}</td>
                        <td>{{$v->good_name}}</td>
                        <td>{{$v->cate_name}}</td>
                        <td><img style="width:80px;height:80px;" src="{{$v->thumb_img}}" /></td>
                        <td>{{$v->storenum}}</td>
                        <td>{{$v->sell_count}}</td>
                        <td>{{$v->original_price}}</td>
                        <td>{{$v->now_price}}</td>
                        <td>{{$v->introduce}}</td>
                        <td>
                          @if($v->isout == 1)
                            下架
                          @elseif($v->isout == 2)
                            上架
                          @endif
                        </td>
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
                        	<a href="{{ route('cater.goods.add_goods') }}?goods_id={{$v->id}}" class="layui-btn layui-btn-normal layui-btn-sm">编辑</a>
                        	<button class="layui-btn layui-btn-danger layui-btn-sm" onclick="del({{$v->id}})">删除</button>
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
    function del(id){
      $.ajax({  
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
        url: "{{ route('cater.goods.del_goods') }}",  
        data: {goods_id:id},  
        dataType: "json",  
        success: function(data){
           if(data.errcode == 1){
              layer.msg(data.errmsg, {icon: 1},function(){
                  location.reload();
              }); 
           }else{
              layer.msg(data.errmsg, {icon: 2},1500); 
           }
        }  
      }); 
    }
</script>
@endsection