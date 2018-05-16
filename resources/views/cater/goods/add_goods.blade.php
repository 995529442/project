@extends('layouts.app')
@section('content')  
<style>
  img{margin-left:120px;}
</style>       
<blockquote class="layui-elem-quote layui-text">
  餐厅信息
</blockquote>
<form class="layui-form" action="" method="post" enctype="multipart/form-data" onsubmit="return check_submit();">
	<input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}"> 
	<input type="hidden" name="shop_id" value="">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐厅名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="name" id="name" autocomplete="off" class="layui-input" value="" style="width:40%;">
	    </div>
    </div>   
    
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="submit" class="layui-btn" lay-filter="demo1">提交</button>
      <button type="reset" class="layui-btn layui-btn-primary">取消</button>
    </div>
  </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
</script>
@endsection