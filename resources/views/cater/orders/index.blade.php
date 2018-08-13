@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style>     
<div>
    <form class="layui-form" action="{{ route('cater.orders.index') }}" method="get">
         {{csrf_field()}}
        <div class="layui-form-item" style="display:inline-block;margin-left:-40px;">
          <label class="layui-form-label">订单号：</label>
          <div class="layui-input-block">
            <input type="text" name="batchcode" id="batchcode" autocomplete="off" class="layui-input" value="{{$batchcode}}">
          </div>
        </div>

        <div class="layui-form-item" style="display:inline-block;margin-left:-50px;">
          <label class="layui-form-label">用户：</label>
          <div class="layui-input-block">
             <input type="text" name="weixin_name" id="weixin_name" autocomplete="off" class="layui-input" value="{{$weixin_name}}">
          </div>
        </div>

        <div class="layui-form-item" style="display:inline-block;margin-left:-20px;">
          <label class="layui-form-label">联系方式：</label>
          <div class="layui-input-block">
            <input type="text" name="phone" id="phone" autocomplete="off" class="layui-input" value="{{$phone}}">
          </div>
        </div>

        <div class="layui-form-item" style="display:inline-block;margin-left:-20px;">
          <label class="layui-form-label">订单类型：</label>
          <div class="layui-input-block">
            <select name="type" id="type" lay-filter="type">
                  <option value="0">全部</option>
                  <option value="1" @if($type ==1 ) selected @endif>点餐</option>
                  <option value="2" @if($type ==2 ) selected @endif>外卖</option>
              </select>
          </div>
        </div>   

        <div class="layui-form-item" style="display:inline-block;margin-left:-20px;">
          <label class="layui-form-label">支付状态：</label>
          <div class="layui-input-block">
            <select name="pay_type" id="pay_type" lay-filter="pay_type">
                  <option value="-1">全部</option>
                  <option value="0" @if($pay_type ==0 ) selected @endif>未支付</option>
                  <option value="1" @if($pay_type ==1 ) selected @endif>已支付</option>
              </select>
          </div>
        </div> 

        <div class="layui-form-item" style="display:inline-block;margin-left:-20px;">
          <label class="layui-form-label">订单状态：</label>
          <div class="layui-input-block">
            <select name="status" id="status" lay-filter="status">
                  <option value="-2">全部</option>
                  <option value="-1" @if($status ==-1 ) selected @endif>已取消</option>
                  <option value="0" @if($status ==0 ) selected @endif>待付款</option>
                  <option value="1" @if($status ==1 ) selected @endif>待接单</option>
                  <option value="2" @if($status ==2 ) selected @endif>已接单</option>
                  <option value="3" @if($status ==3 ) selected @endif>配送中</option>
                  <option value="4" @if($status ==4 ) selected @endif>配送完成</option>
                  <option value="5" @if($status ==5 ) selected @endif>已完成</option>
                  <option value="6" @if($status ==6 ) selected @endif>申请退款</option>
                  <option value="7" @if($status ==7 ) selected @endif>已退款</option>
                  <option value="8" @if($status ==8 ) selected @endif>拒绝退款</option>
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
                <td style="width:10%;">订单号</td>
                <td style="width:10%;">用户</td>
                <td style="width:10%;">联系方式</td>                
                <td style="width:5%;">订单类型</td>                                 
                <td style="width:5%;">支付状态</td>
                <td style="width:5%;">订单状态</td>
                <td style="width:5%;">支付金额</td>
                <td style="width:5%;">数量</td>
                <td style="width:10%;">下单时间</td>
                <td style="width:10%;">留言</td>
                <td style="width:10%;">操作</td>                                 
            </tr>
            </thead>
            <tbody>
              @foreach($order_info as $v)
                <tr>
                    <td>{{$v->order_id}}</td>
                    <td><a href="{{ route('cater.orders.orderGoods',['order_id'=>$v->order_id]) }}" style="color:#1E9FFF;">{{$v->batchcode}}</a></td>
                    <td>{{$v->weixin_name}}</td>  
                    <td>{{$v->phone}}</td>
                    <td>
                        @if($v->type == 1)
                          点餐
                        @elseif($v->type == 2)
                          外卖
                        @endif
                    </td>                  
                    <td>
                        @if($v->pay_type == 0)
                          未支付
                        @elseif($v->pay_type == 1)
                          已支付
                        @endif
                    </td>
                    <td>
                        @if($v->status == -1)
                          已取消
                        @elseif($v->status == 0)
                          待付款
                        @elseif($v->status == 1)
                          待接单
                        @elseif($v->status == 2)
                          已接单
                        @elseif($v->status == 3)
                          配送中
                        @elseif($v->status == 4)
                          配送完成
                        @elseif($v->status == 5)
                          已完成
                        @elseif($v->status == 6)
                          申请退款
                        @elseif($v->status == 7)
                          已退款
                        @elseif($v->status == 8)
                          拒绝退款                           
                        @endif
                    </td>
                    <td>{{$v->real_pay}}</td>
                    <td>{{$v->total_num}}</td> 
                    <td>{{date("Y-m-d H:i:s",$v->create_time)}}</td>
                    <td>{{$v->remark}}</td>
                    <td>
                        @if($v->status == 0 && $v->pay_type == 0)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'cancel')">取消订单</button>
                        @elseif($v->status == 1 && $v->pay_type == 1)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'accept')">接单</button>
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'reject')">拒单</button>
                        @elseif($v->status == 2 && $v->pay_type == 1)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'send')">配送</button>
                        @elseif($v->status == 3 && $v->pay_type == 1)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'confirm_send')">配送完成</button>
                        @elseif($v->status == 4 && $v->pay_type == 1)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'confirm')">完成</button>
                        @elseif($v->status == 6 && $v->pay_type == 1)
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'confirm_refund')">确认退款</button>
                          <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->order_id}},'reject_refund')">拒绝退款</button>
                        @endif
                    </td>                   
                </tr>
              @endforeach
            </tbody>
        </table>
    </div> 
    <div id="page">
       {{$order_info->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
layui.use('form', function(){
    var form = layui.form; 
    form.render();
}); 

//订单操作
function operate(order_id,type){
    var msg = "";
    
    if(type == "accept"){
        msg = "确定要接单？";
    }else if(type == "reject"){
        msg = "确定要拒单，并返还金额给用户？";
    }else if(type == "send"){
        msg = "确定要配送？";
    }else if(type == "confirm_send"){
        msg = "确定要配送完成？";
    }else if(type == "confirm"){
        msg = "确定要完成？";
    }else if(type == "confirm_refund"){
        msg = "确定要退款？";
    }else if(type == "reject_refund"){
        console.log(order_id)
        layer.open({
          type: 2,
          title: false,
          shadeClose: false,
          shade: 0.1,
          area: ['500px', '265px'],
          content: '/cater/orders/reject_refund?order_id='+order_id,
          end: function(){

            }
        });
        return;
    }else if(type == "cancel"){
        msg = "确定要取消该订单？";
    }
    if(order_id > 0){ 
        layer.confirm(msg, {
          btn: ['确定','取消'] //按钮
        }, function(){
            $.ajax({  
              type: "POST",
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
              url: "{{ route('cater.orders.operate') }}",  
              data: {order_id:order_id,type:type},  
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
        }, function(){

        });
    }
}  
</script>
@endsection