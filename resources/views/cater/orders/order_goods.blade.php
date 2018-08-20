@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style> 
<blockquote class="layui-elem-quote layui-text">
  订单商品详情  <button style="float:right;margin-top:-8px;" onclick="javascript:history.go(-1);" class="layui-btn layui-btn-primary">返回</button>
</blockquote>    
<div style="padding-left: 15px;padding-right: 15px;margin-top:-10px;">
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <tr>
                <td style="background-color: #f5f5f5;width:10%;">订单号</td>
                <td style="width:40%;">{{$order_detail->batchcode}}</td>
                <td style="background-color: #f5f5f5;width:10%;"">订单类型</td>
                <td style="width:40%;">
                    @if($order_detail->type == 1)
                      外卖
                    @elseif($order_detail->type == 2)
                      点餐
                    @endif
                </td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">用户</td>
                <td>{{$order_detail->weixin_name}}</td>
                <td style="background-color: #f5f5f5;">联系方式</td>
                <td>{{$order_detail->phone}}</td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">支付状态</td>
                <td>
                  @if($order_detail->pay_type == 0)
                    未支付
                  @elseif($order_detail->pay_type == 1)
                    已支付
                  @endif
                </td>
                <td style="background-color: #f5f5f5;">支付时间</td>
                <td>
                  @if($order_detail->pay_time != "")
                    {{date("Y-m-d H:i:s",$order_detail->pay_time)}}
                  @endif
                </td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">配送时间</td>
                <td>                  
                  @if($order_detail->shipping_time != "")
                    {{date("Y-m-d H:i:s",$order_detail->shipping_time)}}
                  @endif
                </td>
                <td style="background-color: #f5f5f5;">配送完成时间</td>
                <td>
                  @if($order_detail->shipping_con_time != "")
                    {{date("Y-m-d H:i:s",$order_detail->shipping_con_time)}}
                  @endif
                </td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">完成订单时间</td>
                <td>
                  @if($order_detail->confirm_time != "")
                    {{date("Y-m-d H:i:s",$order_detail->confirm_time)}}
                  @endif
                </td>
                <td style="background-color: #f5f5f5;">失效时间</td>
                <td>
                  @if($order_detail->recovery_time != "")
                    {{date("Y-m-d H:i:s",$order_detail->recovery_time)}}
                  @endif
                </td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">订单状态</td>
                <td>
                  @if($order_detail->status == -1)
                    已取消
                  @elseif($order_detail->status == 0)
                    待付款
                  @elseif($order_detail->status == 1)
                    待接单
                  @elseif($order_detail->status == 2)
                    已接单
                  @elseif($order_detail->status == 3)
                    配送中
                  @elseif($order_detail->status == 4)
                    配送完成
                  @elseif($order_detail->status == 5)
                    已完成
                  @elseif($order_detail->status == 6)
                    退款中
                  @elseif($order_detail->status == 7)
                    已退款
                  @elseif($order_detail->status == 8)
                    拒绝退款
                  @elseif($order_detail->status == 9)
                    已拒单                           
                  @endif
                </td>
                <td style="background-color: #f5f5f5;">配送费</td>
                <td>{{$order_detail->shipping_fee}}</td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">包装费</td>
                <td>{{$order_detail->package_fee}}</td>
                <td style="background-color: #f5f5f5;">总价</td>
                <td>{{$order_detail->total_money}}</td>                                   
            </tr>
            <tr>
                <td style="background-color: #f5f5f5;">支付金额</td>
                <td>{{$order_detail->real_pay}}</td>
                <td style="background-color: #f5f5f5;">总数量</td>
                <td>{{$order_detail->total_num}}</td>                                   
            </tr>   
            <tr>
                <td style="background-color: #f5f5f5;">留言</td>
                <td>{{$order_detail->remark}}</td>
                <td style="background-color: #f5f5f5;">拒绝退款原因</td>
                <td>{{$order_detail->reject_reason}}</td>                                   
            </tr>                                   
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <td style="width:5%;">编号</td>
                <td style="width:35%;">商品名称</td>
                <td style="width:20%;">单价</td>
                <td style="width:20%;">数量</td>                
                <td style="width:20%;">总价</td>
            </tr>
            </thead>
            <tbody>
              @foreach($order_goods as $v)
                <tr>
                    <td>{{$v->id}}</td>  
                    <td>{{$v->good_name}}</td>
                    <td>{{$v->price}}</td>
                    <td>{{$v->number}}</td>
                    <td>{{$v->total_price}}</td>                
                </tr>
              @endforeach
            </tbody>
        </table>
    </div>        
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
layui.use('form', function(){
    var form = layui.form; 
    form.render();
});   
</script>
@endsection