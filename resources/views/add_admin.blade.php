@extends('layouts.app')
@section('content')       
<blockquote class="layui-elem-quote layui-text">
  新增管理员
</blockquote>
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <div class="layui-form-item">
	    <label class="layui-form-label">用户名：</label>
	    <div class="layui-input-block">
	      <input type="text" name="username" id="username" value="" autocomplete="off" class="layui-input" style="width:60%">
	    </div>
	</div>   
    <div class="layui-form-item">
	    <div class="layui-input-block">
	      <button class="layui-btn layui-btn-normal" onclick="put_submit();">提交</button>
	      <button class="layui-btn layui-btn-primary" onclick="javascript:window.parent.layer.closeAll();">取消</button>
	    </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
	function put_submit(){
		var username = $("#username").val();

		if(username == "" || username == null){
			layer.msg('用户名不能为空', {icon: 2},1500); 
			return;
		}
		if(username.length > 20){
			layer.msg('用户名长度不能大于20个字符', {icon: 2},1500); 
			return;
		}

		$.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('save_admin') }}",  
          data: {username:username},  
          dataType: "json",  
          success: function(data){
          	console.log(data)
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