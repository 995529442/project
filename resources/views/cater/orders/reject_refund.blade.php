@extends('layouts.app')
@section('content')  
<blockquote class="layui-elem-quote layui-text">
  拒绝退款页面
</blockquote>     
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="order_id" id="order_id" value="{{$order_id}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">拒绝原因：</label>
	    <div class="layui-input-block">
	      <input type="text" name="reject_reason" id="reject_reason" value="" autocomplete="off" class="layui-input" style="width:60%">
	    </div>
	</div>  
    <div class="layui-form-item">
	    <div class="layui-input-block">
	      <button class="layui-btn layui-btn-normal" onclick="put_submit();">提交</button>
	      <button class="layui-btn layui-btn-primary">取消</button>
	    </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
	function put_submit(){
		var order_id = $("#order_id").val();
		var reject_reason = $("#reject_reason").val();

		if(reject_reason == "" || reject_reason == null){
			layer.msg('拒绝退款原因不能为空', {icon: 2},1500); 
			return;
		}

		$.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('cater.orders.operate') }}",  
          data: {order_id:order_id,type:'reject_refund',reject_reason:reject_reason},  
          dataType: "json",  
          success: function(data){
             if(data.errcode == 1){
             	layer.msg(data.errmsg, {icon: 1},function(){
             		window.parent.location.reload();
             		window.parent.layui.closeAll();
             	}); 
             }else{
             	layer.msg(data.errmsg, {icon: 2},1500); 
             }
          }  
        }); 
	}
</script>
@endsection