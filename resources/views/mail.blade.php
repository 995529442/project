@extends('layouts.app')
@section('content')  
<style>
  img{margin-left:120px;}
</style>       
<form class="layui-form" action="{{ route('saveMail') }}" method="post"  onsubmit="return check_submit();">
    <input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}"> 
    <div class="layui-form-item">
      <label class="layui-form-label">授权码：</label>
      <div class="layui-input-block">
        <input type="text" name="password" id="password" autocomplete="off" class="layui-input" value="@if(count($mail_list) > 0){{$mail_list[0]->password}}@endif" style="display:inline-block;width:20%;">
      </div>
    </div>
    @if(count($mail_list) > 0) 
      <div class="layui-form-item" id="name_div">
  	    <label class="layui-form-label">邮箱账号：</label>
        @foreach($mail_list as $v)
          <input type="hidden" value="{{$v->id}}" name="mail_id[]">
    	    <div class="layui-input-block">
    	      <input type="text" name="name[]" id="name" autocomplete="off" class="layui-input" value="{{$v->name}}" style="display:inline-block;width:20%;">

            <div class="layui-inline">
                <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="width:36px;height:36px;background-color:#FF6600;" onclick="minu_time(this);">-</button>
                <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="width:36px;height:36px;" onclick="add_time();">+</button>
            </div>
    	    </div>
        @endforeach
      </div>
    @else
      <div class="layui-form-item" id="name_div">
        <label class="layui-form-label">邮箱账号：</label>
        <div class="layui-input-block">
          <input type="text" name="name[]" id="name" autocomplete="off" class="layui-input" value="" style="display:inline-block;width:20%;">

          <div class="layui-inline">
              <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="width:36px;height:36px;" onclick="add_time();">+</button>
          </div>
        </div>
      </div>
    @endif      
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button type="submit" class="layui-btn" lay-filter="demo1">保存</button>
    </div>
  </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
    //减去时间段
    function minu_time(obj){
      var num = 0;
      $("input[name='name[]']").each(function(){
         num++;
      })

      if(num == 1){
        layer.msg('请至少输入一个邮箱账号', {icon: 2},1500);
      }else{
        $(obj).parent().parent().remove();
      }
    }

    //加上时间段
    function add_time(){
      var innerHtml = "";
          innerHtml += '<div class="layui-input-block">';
          innerHtml += '  <input type="text" name="name[]" id="name" autocomplete="off" class="layui-input" value="" style="display:inline-block;width:20%;">';
          innerHtml += '  <div class="layui-inline">';
          innerHtml += '      <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="width:36px;height:36px;background-color:#FF6600;" onclick="minu_time(this);">-</button>';
          innerHtml += '      <button type="button" class="layui-btn layui-btn-normal layui-btn-xs" style="width:36px;height:36px;" onclick="add_time();">+</button>';
          innerHtml += '  </div>';
          innerHtml += '</div>';
      $("#name_div").append(innerHtml);  
    }

    function check_submit(){
      var password = $("#password").val();
      var is_mail_null = 0;
      var is_mail_check = 0;

      if(password == ""){
        layer.msg('授权码不能为空', {icon: 2},1500);
        return false;
      }
      $("input[name='name[]']").each(function(){
        var mail_name = $(this).val();

        if(mail_name == ""){
          $is_mail_null = 1;
        }

        if(!(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(mail_name))){
          is_mail_check = 1;
        }
      }) 

      if(is_mail_null == 1){
          layer.msg('邮箱账号不能为空', {icon: 2},1500);
          return false;
      }

      if(is_mail_check == 1){
          layer.msg('邮箱账号格式不正确', {icon: 2},1500);
          return false;
      }    
    }
</script>
@endsection