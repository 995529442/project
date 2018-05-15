@extends('layouts.app')
@section('content')         
<blockquote class="layui-elem-quote layui-text">
  餐厅信息
</blockquote>
<script type="text/javascript">
    var defaults = {
        s1: 'provid',
        s2: 'cityid',
        s3: 'areaid',
        v1: null,
        v2: null,
        v3: null
    };
</script> 
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
	      <select name="provid" id="provid" lay-filter="provid">
            <option value="">请选择省</option>
          </select>
	    </div>
	    <div class="layui-input-inline" id="city_display">
	      <select name="cityid" id="cityid" lay-filter="cityid">
            <option value="">请选择市</option>
          </select>
	    </div>
	    <div class="layui-input-inline" id="area_display">
	       <select name="areaid" id="areaid" lay-filter="areaid">
             <option value="">请选择县/区</option>
          </select>
	    </div>
    </div>

    <div class="layui-form-item">
	    <label class="layui-form-label">详细地址：</label>
	    <div class="layui-input-block">
	      <input type="text" name="address" id="address" autocomplete="off" class="layui-input" value="{{$shops_info['address']}}" style="display:inline-block;width:40%;">
	      <button onclick="open_map()" class="layui-btn" style="display:inline-block;margin-top:-5px;">搜索</button>
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
<script type="text/javascript" src="/assets/common/layui/layui.js"></script>
<script type="text/javascript" src="/assets/js/data.js"></script>
<script type="text/javascript" src="/assets/js/province.js"></script>
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
	});

	//提交判断
	function check_submit(){
		var province = $("#province option:selected").val();
		return false;
	}

	//地址解析
	function open_map(){
		var province = $("#provid option:selected").text();
		var city = $("#cityid option:selected").text();
		var area = $("#areaid option:selected").text();
		var address = $("#address").val();

	    if(province == "" || province == null){
	    	layer.alert('请选择省份', {icon: 2});
            return;
	    }
	    if(city == "" || city == null){
	    	layer.alert('请选择城市', {icon: 2});
            return;
	    }
	    if(area == "" || area == null){
	    	layer.alert('请选择县区', {icon: 2});
            return;
	    }
	    if(address == "" || address == null){
	    	layer.alert('详细地址不能为空', {icon: 2});
            return;
	    }

        layer.open({
          type: 2,
          title: false,
          shadeClose: false,
          shade: 0.1,
          area: ['1000px', '600px'],
          content: 'map?province='+province+"&city="+city+"&area="+area+"&address="+address,
          end: function(){
              
            }
        });
	}
</script>
@endsection