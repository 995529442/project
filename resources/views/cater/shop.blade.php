@extends('layouts.app')
@section('content')         
<blockquote class="layui-elem-quote layui-text">
  餐厅信息
</blockquote>
<form class="layui-form" action="{{ route('cater.saveShop') }}" method="post" enctype="multipart/form-data" onsubmit="return check_submit();">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="shop_id" value="{{$shops_info['id']}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐厅名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="name" id="name" autocomplete="off" class="layui-input" value="{{$shops_info['name']}}" style="width:40%;">
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
            @foreach($provinces as $v)
                <option value="{{$v->id}}" @if($shops_info['province_id'] == $v->id) selected @endif>{{$v->name}}</option>
            @endforeach
          </select>
	    </div>
	    <div class="layui-input-inline" id="city_display">
	      <select name="cityid" id="cityid" lay-filter="cityid">
            <option value="">请选择市</option>
            @if($cities != "")
               @foreach($cities as $v)
                 <option value="{{$v->id}}" @if($shops_info['city_id'] == $v->id) selected @endif>{{$v->name}}</option>
               @endforeach
            @endif
          </select>
	    </div>
	    <div class="layui-input-inline" id="area_display">
	       <select name="areaid" id="areaid" lay-filter="areaid">
             <option value="">请选择县/区</option>
             @if($countris != "")
               @foreach($countris as $v)
                 <option value="{{$v->id}}" @if($shops_info['area_id'] == $v->id) selected @endif>{{$v->name}}</option>
               @endforeach
            @endif
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
	      <input type="text" name="phone" id="phone" autocomplete="off" class="layui-input" value="{{$shops_info['phone']}}" style="width:40%;">
	    </div>
	</div>  
    
    <div class="layui-form-item layui-form-text">
	    <label class="layui-form-label">餐厅介绍：</label>
	    <div class="layui-input-block">
	      <textarea placeholder="请输入内容" name="introduce" class="layui-textarea">{{$shops_info['introduce']}}</textarea>
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
      
      //获取城市
      form.on('select(provid)', function (data) {  
	    var provid = data.value;  
	     
	    //重置县区
        $("#areaid").html('<option value="0">请选择县/区</option>');

	    if(provid > 0){ //选择省份，遍历城市
	        $.ajax({  
              type: "POST",
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
              url: "{{ route('cater.getAddress') }}",  
              data: {provid:provid},  
              dataType: "json",  
              success: function(data){
                if(data != "" && data != null){
              	  var innerHtml = '<option value="0">请选择市</option>';
              	  for(var key in data){
              	  	 innerHtml += '<option value="'+data[key]['id']+'">'+data[key]['name']+'</option>';
              	  }
                  $("#cityid").html(innerHtml);
                  
                   form.render('select');
                } 
              }  
	        }); 
	    }  
	  });

	  //获取县区
	  form.on('select(cityid)', function (data) {  
	    var cityid = data.value;  
	    if(cityid > 0){ //选择省份，遍历城市
	        $.ajax({  
              type: "POST",
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
              url: "{{ route('cater.getAddress') }}",  
              data: {cityid:cityid},  
              dataType: "json",  
              success: function(data){
                if(data != "" && data != null){
              	  var innerHtml = '<option value="0">请选择县/区</option>';
              	  for(var key in data){
              	  	 innerHtml += '<option value="'+data[key]['id']+'">'+data[key]['name']+'</option>';
              	  }
                  $("#areaid").html(innerHtml);
                  
                  form.render('select');
                } 
              }  
	        }); 
	    }  
	  }); 
	});     
	//提交判断
	function check_submit(){
		var name       = $("#name").val();
		var begin_time = $("#begin_time").val();
		var end_time   = $("#end_time").val();
		var provid     = $("#provid").val();
		var cityid     = $("#cityid").val();
		var areaid     = $("#areaid").val();
		var address    = $("#address").val();
		var longitude  = $("#longitude").val();
		var latitude   = $("#latitude").val();
		var latitude   = $("#latitude").val();
		var phone      = $("#phone").val();

        if(name == "" || name == null){
        	layer.alert('餐厅名称不能为空', {icon: 2});
            return false;
        }
        if(begin_time == "" || begin_time == null){
        	layer.alert('营业开始时间不能为空', {icon: 2});
            return false;
        }
        if(end_time == "" || end_time == null){
        	layer.alert('营业结束时间不能为空', {icon: 2});
            return false;
        }
        if(provid == "" || provid == null){
        	layer.alert('请选择省', {icon: 2});
            return false;
        }
        if(cityid == "" || cityid == null){
        	layer.alert('请选择市', {icon: 2});
            return false;
        }
        if(areaid == "" || areaid == null){
        	layer.alert('请选择县/区', {icon: 2});
            return false;
        }
        if(address == "" || address == null){
        	layer.alert('详细地址不能为空', {icon: 2});
            return false;
        }
        if(longitude == "" || longitude == null){
        	layer.alert('经度不能为空', {icon: 2});
            return false;
        }
        if(latitude == "" || latitude == null){
        	layer.alert('纬度不能为空', {icon: 2});
            return false;
        }
        if(phone == "" || phone == null){
        	layer.alert('联系方式不能为空', {icon: 2});
            return false;
        }
        if(!(/^1[34578]\d{9}$/.test(phone))){ 
	        layer.alert('联系方式有误，请重填', {icon: 2}); 
	        return false; 
	    } 
	}

	//地址解析
	function open_map(){
		var province = $("#provid option:selected");
		var city = $("#cityid option:selected");
		var area = $("#areaid option:selected");
		var address = $("#address").val();

	    if(province.val() == "" || province.val() == null){
	    	layer.alert('请选择省份', {icon: 2});
            return;
	    }
	    if(city.val() == "" || city.val() == null){
	    	layer.alert('请选择城市', {icon: 2});
            return;
	    }
	    if(area.val() == "" || area.val() == null){
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
          area: ['600px', '430px'],
          content: 'map?province='+province.text()+"&city="+city.text()+"&area="+area.text()+"&address="+address,
          end: function(){

            }
        });        
	}
</script>
@endsection