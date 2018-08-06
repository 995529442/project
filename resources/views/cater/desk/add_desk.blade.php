@extends('layouts.app')
@section('content')       
<blockquote class="layui-elem-quote layui-text">
  新增餐桌
</blockquote>
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="desk_id" id="desk_id" value="{{$desk_id}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐桌名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="name" id="name" value="@if($desk_info != ''){{$desk_info->name}}@endif" autocomplete="off" class="layui-input" style="width:40%">
	    </div>
	</div>  
    <div class="layui-form-item">
	    <div class="layui-input-block">
	      <button class="layui-btn layui-btn-normal" onclick="put_submit();">提交</button>
	      <button class="layui-btn layui-btn-primary" onclick="javascript:history.go(-1);">取消</button>
	    </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
	function put_submit(){
		var desk_id = $("#desk_id").val();
		var name = $("#name").val();

		if(name == "" || name == null){
			layer.msg('分类名称不能为空', {icon: 2},1500); 
			return;
		}
		if(name.length > 20){
			layer.msg('分类名称长度不能大于20个字符', {icon: 2},1500); 
			return;
		}

		$.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('cater.desk.saveDesk') }}",  
          data: {desk_id:desk_id,name:name},  
          dataType: "json",  
          success: function(res){
             if(res.errcode > 0){ //成功
                 layer.msg("成功", {icon: 1},function(){
                    window.location.href="/cater/desk/home";
                });
             }else{
             	layer.msg(res.errmsg, {icon: 2},1500);
             }
          }  
        }); 
	}
</script>
@endsection