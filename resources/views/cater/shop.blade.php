@extends('layouts.app')
@section('content')         
<blockquote class="layui-elem-quote layui-text">
  餐厅信息
</blockquote>
 
<form class="layui-form" action="" method="post" enctype="multipart/form-data" onsubmit="return check_submit();">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="shop_id" value="{{$shops_info['id']}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐厅名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="name" autocomplete="off" class="layui-input" value="{{$shops_info['name']}}" style="width:40%;">
	    </div>
    </div>   
    
    <div class="layui-form-item">    
      <label class="layui-form-label">营业时间：</label>
      <div class="layui-input-block">
        <input type="text" name="begin_time" id="begin_time" value="{{$shops_info['begin_time']}}" autocomplete="off" class="layui-input" style="display:inline-block;width:27%;" readonly="readonly">
        <span>至</span>
         <input type="text" name="end_time" id="end_time" value="{{$shops_info['end_time']}}" autocomplete="off" class="layui-input" style="display:inline-block;width:27%;" readonly="readonly">
      </div>  
    </div>

    <div class="layui-form-item">
	    <label class="layui-form-label">营业状态：</label>
	    <div class="layui-input-block">
	       <input type="radio" name="status" value="1" title="营业" @if($shops_info['status'] == 1) checked="checked" @endif />
	       <input type="radio" name="status" value="2" title="打烊" @if($shops_info['status'] == 2) checked="checked" @endif />
	    </div>
    </div>
    <div class="layui-form-item">
	    <label class="layui-form-label">联系地址：</label>
	    <div class="layui-input-inline">
	      <select name="province" id="province" lay-filter="province">     
	        <option value="0">请选择省</option>
	        @foreach($provinces as $v)
               <option value="{{$v->provinceid}}" @if($shops_info['province_id'] == $v->provinceid) selected="selected" @endif>{{$v->province}}</option>
	        @endforeach
	      </select>
	    </div>
	    <div class="layui-input-inline" id="city_display" style="display: none;">
	      <select name="city" id="city" lay-filter="city">
	        <option value="0">请选择市</option>
	      </select>
	    </div>
	    <div class="layui-input-inline" id="area_display" style="display: none;">
	      <select name="area" id="area" lay-filter="area">
	        <option value="0">请选择县/区</option>
	      </select>
	    </div>
    </div>

    <div class="layui-form-item">
	    <label class="layui-form-label">详细地址：</label>
	    <div class="layui-input-block">
	      <input type="text" name="address" autocomplete="off" class="layui-input" value="{{$shops_info['address']}}" style="width:40%;">
	    </div>
	</div> 
    
    <div class="layui-form-item">    
      <label class="layui-form-label">经度：</label>
      <div class="layui-input-block">
        <input type="text" name="longitude" id="longitude" value="{{$shops_info['longitude']}}" autocomplete="off" class="layui-input" style="display:inline-block;width:27%;" readonly="readonly">
        <span>纬度：</span>
         <input type="text" name="latitude" id="latitude" value="{{$shops_info['latitude']}}" autocomplete="off" class="layui-input" style="display:inline-block;width:27%;" readonly="readonly">
      </div>  
    </div>

    <div class="layui-form-item">
	    <label class="layui-form-label">联系电话：</label>
	    <div class="layui-input-block">
	      <input type="text" name="phone" autocomplete="off" class="layui-input" value="{{$shops_info['phone']}}" style="width:40%;">
	    </div>
	</div>  
    
    <div class="layui-form-item layui-form-text">
	    <label class="layui-form-label">餐厅介绍：</label>
	    <div class="layui-input-block">
	      <textarea placeholder="请输入内容" class="layui-textarea">{{$shops_info['introduce']}}</textarea>
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
	layui.use(['layer','form','laydate','element'], function(){
	  var form = layui.form
	  ,layer = layui.layer
	  ,laydate = layui.laydate
	  ,element = layui.element;
	  
	  //日期
	  laydate.render({
	    elem: '#begin_time'
	    ,type: 'time'
	  });
	  laydate.render({
	    elem: '#end_time'
	    ,type: 'time'
	  }); 

	  form.on('select(province)', function (data) {  
	    var provinceid = data.value;  

	    if(provinceid > 0){ //选择省份，遍历城市
	        $.ajax({  
              type: "POST",
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
              url: "{{ route('cater.getAddress') }}",  
              data: {provinceid:provinceid,type:2},  
              dataType: "json",  
              success: function(data){
              console.log(data) 
                  if(data != "" && data != null){
                  	  var innerHtml = '<option value="0">请选择市</option>';
                      for(var k=0;k<data.length;k++){
                         innerHtml += '<option value="'+data[k]['cityid']+'">'+data[k]['city']+'</option>';
                      }
                      $("#city").html(innerHtml);
                  	  $("#city_display").show();
                  }
              }  
	        }); 
	    }
	    form.render(null,'city');   
	});  

	});

	//提交判断
	function check_submit(){
		var province = $("#province option:selected").val();
		console.log(province)
		return false;
	}
</script>
@endsection