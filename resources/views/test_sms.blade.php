@extends('layouts.app')
@section('content')       
<blockquote class="layui-elem-quote layui-text">
  测试短信，请输入要测试的手机号码
</blockquote>
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="type" id="type" value="{{$type}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">手机号码：</label>
	    <div class="layui-input-block">
	      <input type="text" name="phone" id="phone" value="" autocomplete="off" class="layui-input" style="width:60%">
	    </div>
	</div>  
    <div class="layui-form-item">
	    <div class="layui-input-block">
	      <button class="layui-btn layui-btn-normal" onclick="put_submit();">提交</button>
	      <button class="layui-btn layui-btn-primary" onclick="jsvascript:window.parent.location.reload();window.parent.layui.closeAll();">取消</button>
	    </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
	function put_submit(){
		var phone = $("#phone").val();
        var type = $("#type").val();

		if(phone == "" || phone == null){
			layer.msg('手机号码不能为空', {icon: 2},1500); 
			return;
		}

		var index = layer.load(1, {
		  shade: [0.1,'#fff'] //0.1透明度的白色背景
		});

		$.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('testSms') }}",  
          data: {
          	phone:phone,
          	type:type
          },  
          dataType: "json",  
          success: function(data){
          	layer.close(index);
             if(data.errcode == 1){
             	layer.msg(data.errmsg, {icon: 1},function(){
             		window.parent.layer.closeAll();
             	}); 
             }else{
             	layer.msg(data.errmsg, {icon: 2},1500); 
             }
          }  
        }); 
	}
</script>
@endsection