@extends('layouts.app')
@section('content')  
<style>
  img{margin-left:120px;}
</style>       
<form class="layui-form" action="" method="post">
	<input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}"> 
	<input type="hidden" name="admin_id" id="admin_id" value="{{$admin_id}}">
    <div class="layui-form-item" pane="">
      <label class="layui-form-label">模块名称：</label>
      <div class="layui-input-block">
        @foreach($module as $v)
          <input type="checkbox" name="module[]" lay-skin="primary" value="{{$v->module_code}}" @if($v->is_checked ==1) checked @endif title="{{$v->module_name}}">
        @endforeach
      </div>
    </div>

    <div class="layui-form-item">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" onclick="check_form()">提交</button>
        <button type="button" class="layui-btn layui-btn-primary" onclick="javascript:history.go(-1);">取消</button>
      </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
  function check_form(){
    var module = "";
    var admin_id = $("#admin_id").val();

    $("input[type='checkbox']:checked").each(function(){
      module += $(this).val()+",";
    });
    
    if(module == "" || module == null){
      layer.msg("请至少选取一个",{icon:2},1500);
      return;
    }
    $.ajax({  
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
        url: "{{ route('saveModule') }}",  
        data: {admin_id:admin_id,module:module},  
        dataType: "json",  
        success: function(data){
           if(data.errcode == 1){
              layer.msg(data.errmsg, {icon: 1},function(){
                  location.href = '/Index/manage';
              }); 
           }else{
              layer.msg(data.errmsg, {icon: 2},1500); 
           }
        }  
      }); 
  }
</script>
@endsection