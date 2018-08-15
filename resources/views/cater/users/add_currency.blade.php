@extends('layouts.app')
@section('content')       
<blockquote class="layui-elem-quote layui-text">
  新增分类
</blockquote>
<form class="layui-form" action="" method="post" onsubmit="return false;">
    <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">充值金额：</label>
	    <div class="layui-input-block">
	      <input type="text" name="money" id="money" value="" oninput="clearNoNum(this)" autocomplete="off" class="layui-input" style="width:60%">
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
		var user_id = $("#user_id").val();
		var money = $("#money").val();

		if(money == "" || money == null){
			layer.msg('充值金额不能为空', {icon: 2},1500); 
			return;
		}

		// $.ajax({  
  //         type: "POST",
  //         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
  //         url: "{{ route('cater.category.save_cate') }}",  
  //         data: {cate_id:cate_id,cate_name:cate_name,sort:sort},  
  //         dataType: "json",  
  //         success: function(data){
  //            if(data.errcode == 1){
  //            	layer.msg(data.errmsg, {icon: 1},function(){
  //            		window.parent.layer.closeAll();
  //            		window.parent.location.reload();
  //            	}); 
  //            }else{
  //            	layer.msg(data.errmsg, {icon: 2},1500); 
  //            }
  //         }  
  //       }); 
	}

	function clearNoNum(obj){ 
      obj.value = obj.value.replace(/[^\d.]/g,"");  //清除“数字”和“.”以外的字符  
      obj.value = obj.value.replace(/\.{2,}/g,"."); //只保留第一个. 清除多余的  
      obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$","."); 
      obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');//只能输入两个小数  
      if(obj.value.indexOf(".")< 0 && obj.value !=""){//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额 
          obj.value= parseFloat(obj.value); 
      } 
    } 
</script>
@endsection