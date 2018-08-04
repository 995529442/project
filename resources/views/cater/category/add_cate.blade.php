@extends('layouts.app')
@section('content')       
<blockquote class="layui-elem-quote layui-text">
  新增分类
</blockquote>
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="cate_id" id="cate_id" value="{{$cate_info['id']}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">分类名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="cate_name" id="cate_name" value="{{$cate_info['cate_name']}}" autocomplete="off" class="layui-input" style="width:60%">
	    </div>
	</div>  
    <div class="layui-form-item">
	    <label class="layui-form-label">排序：</label>
	    <div class="layui-input-block">
	      <input type="number" name="sort" id="sort" value="{{$cate_info['sort']}}" autocomplete="off" class="layui-input" style="width:60%">
	    </div>
	</div> 
    <div class="layui-form-item">
	    <div class="layui-input-block">
	      <button class="layui-btn layui-btn-normal" onclick="put_submit();">提交</button>
	      <button class="layui-btn layui-btn-primary" onclick="javascript:window.parent.location.reload();window.parent.layer.closeAll();">取消</button>
	    </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
	function put_submit(){
		var cate_id = $("#cate_id").val();
		var cate_name = $("#cate_name").val();
		var sort = $("#sort").val();

		if(cate_name == "" || cate_name == null){
			layer.msg('分类名称不能为空', {icon: 2},1500); 
			return;
		}
		if(cate_name.length > 20){
			layer.msg('分类名称长度不能大于20个字符', {icon: 2},1500); 
			return;
		}
		if(sort == "" || sort == null){
			layer.msg('排序不能为空', {icon: 2},1500); 
			return;
		}
		if(sort < 0){
			layer.msg('排序不能小于0', {icon: 2},1500); 
			return;
		}

		$.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('cater.category.save_cate') }}",  
          data: {cate_id:cate_id,cate_name:cate_name,sort:sort},  
          dataType: "json",  
          success: function(data){
             if(data.errcode == 1){
             	layer.msg(data.errmsg, {icon: 1},function(){
             		window.parent.layer.closeAll();
             		window.parent.location.reload();
             	}); 
             }else{
             	layer.msg(data.errmsg, {icon: 2},1500); 
             }
          }  
        }); 
	}
</script>
@endsection