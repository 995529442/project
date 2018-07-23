@extends('layouts.app')
@section('content')  
<style>
  img{margin-left:120px;}
</style>       
<form class="layui-form" action="{{ route('cater.shop.saveShop') }}" method="post" enctype="multipart/form-data" onsubmit="return check_submit();">
	<input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}"> 
	<input type="hidden" name="shop_id" value="{{$shops_info['id']}}">
    <div class="layui-form-item">
	    <label class="layui-form-label">餐厅名称：</label>
	    <div class="layui-input-block">
	      <input type="text" name="name" id="name" autocomplete="off" class="layui-input" value="{{$shops_info['name']}}" style="width:40%;">
	    </div>
    </div>   
    
    <div class="layui-form-item">
      <label class="layui-form-label">堂食：</label>
      <div class="layui-input-inline">
        <input type="checkbox" name="is_eat_in_box" @if($shops_info['is_eat_in'] == 2) checked @endif id="is_eat_in_box" lay-skin="switch" lay-text="ON|OFF" lay-filter="is_eat_in_box">
        <input type="hidden" name="is_eat_in" id="is_eat_in" value="{{$shops_info['is_eat_in']}}">
      </div>
       <label class="layui-form-label">外卖：</label>
      <div class="layui-input-inline">
        <input type="checkbox" name="is_take_out_box" @if($shops_info['is_take_out'] == 2) checked @endif id="is_take_out_box" lay-skin="switch" lay-text="ON|OFF" lay-filter="is_take_out_box">
        <input type="hidden" name="is_take_out" id="is_take_out" value="{{$shops_info['is_take_out']}}">
      </div>
    </div>  
    <div class="layui-form-item" id="take_out" @if($shops_info == "" || $shops_info['is_take_out'] == 1)style="display:none;" @endif>
      <label class="layui-form-label">配送费：</label>
      <div class="layui-input-inline">
        <input type="text" name="shipping_fee" id="shipping_fee" autocomplete="off" class="layui-input" value="{{$shops_info['shipping_fee']}}" oninput="clearNoNum(this)" style="width:80%;display:inline-block;margin-right:5px;">元
      </div>
      <label class="layui-form-label">包装费：</label>
      <div class="layui-input-inline">
        <input type="text" name="package_fee" id="package_fee" autocomplete="off" class="layui-input" value="{{$shops_info['package_fee']}}" oninput="clearNoNum(this)" style="width:80%;display:inline-block;margin-right:5px;">元
      </div>
      <label class="layui-form-label">配送范围：</label>
      <div class="layui-input-inline">
        <input type="text" name="delivery_km" id="delivery_km" autocomplete="off" class="layui-input" value="{{$shops_info['delivery_km']}}"" oninput="clearNoNum(this)" style="width:80%;display:inline-block;margin-right:5px;">公里
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
	       <input type="radio" name="status" value="1" title="营业" @if($shops_info['id'] > 0) @if($shops_info['status'] == 1) checked="checked" @endif @else checked="checked" @endif/>
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
	      <button onclick="open_map()" type="button" class="layui-btn layui-btn-sm" style="display:inline-block;margin-top:-5px;">搜索</button>
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
      <label class="layui-form-label">LOGO：</label>
      <div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-sm" id="preview_logo_id">上传图片</button>
        <input type="hidden" class="layui-btn" name="logo" id="logo" value="{{$shops_info['logo']}}">
        <div class="layui-upload-list">
          <img class="layui-upload-img" id="preview_logo" @if($shops_info['show_logo'] != "") src="{{$shops_info['show_logo']}}" style="width:100px;height:100px;" @endif>
          <p id="demoText"></p>
        </div>
      </div>  
    </div>
    
    <div class="layui-form-item">    
      <label class="layui-form-label">商家展示图：</label>
      <div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-sm" id="figure_img">多图片上传</button> 
        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;margin-left: 120px;">
          预览图：
          <div class="layui-upload-list" id="preview_figure">    
            <ul>       
              @if($figure_img != "")
                 @foreach($figure_img as $v)
                 <li style="display:inline-block;">
                    <input type="hidden" name="figure_img_id[]" value="{{$v->id}}">
                    <input type="hidden" name="figure_img[]" value="{{$v->img_path}}">
                    <img style="width:150px;height:100px;" src="{{$v->img_path}}" alt="" class="layui-upload-img">
                    <div style="display:inline-block;position:relative;top:-40px;width:20px;border:1px solid #F73455;border-radius: 50%;cursor: pointer;"><p style="padding-left:4px;color:#F73455;" onclick="del_figure_img(this,{{$v->id}})">X</p></div>
                  </li>
                 @endforeach
              @endif
            </ul>
          </div>
       </blockquote>
      </div> 
    </div>

    <div class="layui-form-item">
	    <label class="layui-form-label">联系电话：</label>
	    <div class="layui-input-block">
	      <input type="text" name="phone" id="phone" lay-verify="required|phone" autocomplete="off" class="layui-input" value="{{$shops_info['phone']}}" style="width:40%;">
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
      <button type="submit" class="layui-btn" lay-filter="demo1">保存</button>
    </div>
  </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
  layui.use(['layer','form','laydate','upload','element'], function(){
	  var form = layui.form
	  ,layer = layui.layer
	  ,laydate = layui.laydate
	  ,element = layui.element
    ,upload = layui.upload;
	  
	  //监听指定开关
    form.on('switch(is_eat_in_box)', function(data){
      if(this.checked){
        $("#is_eat_in").val(2);
      }else{
        $("#is_eat_in").val(1);
      }
    });
    
    form.on('switch(is_take_out_box)', function(data){
      if(this.checked){
        $("#is_take_out").val(2);
        $("#take_out").show();
      }else{
        $("#is_take_out").val(1);
        $("#take_out").hide();
      }
    });
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
              url: "{{ route('cater.shop.getAddress') }}",  
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
              url: "{{ route('cater.shop.getAddress') }}",  
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

    //上传图片
    var tag_token = $(".tag_token").val();

    var uploadInst = upload.render({
      elem: '#preview_logo_id'
      ,url: '{{ route("cater.shop.upload") }}'
      ,accept: 'file' //普通文件
      ,exts: 'jpg|png|gif|bmp|jpeg' //只允许上传图片文件
      ,size: 1024 //限制文件大小，单位 KB
      ,data:{'_token':tag_token}
      ,before: function(obj){
        //预读本地文件示例，不支持ie8
        obj.preview(function(index, file, result){
          $('#preview_logo').css('width', '100px'); //图片链接（base64）
          $('#preview_logo').css('height', '100px'); //图片链接（base64）
          $('#preview_logo').attr('src', result); //图片链接（base64）
        });
      }
      ,done: function(res){
        var errcode = res.errcode;

        if(errcode == 1){
           $("#logo").val(res.path);
        }
      }
      ,error: function(res){
         console.log(res)
      }
    });

      //多图片上传
      upload.render({
        elem: '#figure_img'
        ,url: '{{ route("cater.shop.upload") }}'
        ,multiple: true
        ,accept: 'file' //普通文件
        ,exts: 'jpg|png|gif|bmp|jpeg' //只允许上传图片文件
        ,size: 1024 //限制文件大小，单位 KB
        ,data:{'_token':tag_token,type:'figure'}
        ,before: function(obj){
          //预读本地文件示例，不支持ie8
          obj.preview(function(index, file, result){

          });
        }
        ,done: function(res){
          if(res.errcode == 1){
            lis = $("#preview_figure ul li").length;

            if(lis < 3){
              $('#preview_figure ul').append('<li style="display:inline-block;"><input type="hidden" name="figure_img_id[]" value="0"><input type="hidden" name="figure_img[]" value="'+res.path+'"><img style="width:150px;height:100px;" src="'+ res.path +'" alt="'+ res.path +'" class="layui-upload-img"><div style="display:inline-block;position:relative;top:-40px;width:20px;border:1px solid #F73455;border-radius: 50%;cursor: pointer;"><p style="padding-left:4px;color:#F73455;" onclick="del_figure_img(this,0)">X</p></div></li>')
            }else{
              layer.msg("首页展示图最多为3张",{icon:2},1500);
            }
          }
          //上传完毕
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
    var logo       = $("#logo").val();
    var is_eat_in  = $("#is_eat_in").val();
    var is_take_out= $("#is_take_out").val();
    var delivery_km= $("#delivery_km").val();

      if(name == "" || name == null){
      	layer.msg('餐厅名称不能为空', {icon: 2},1500);
          return false;
      }
      if(is_eat_in == 0 && is_take_out == 0){
        layer.msg('堂食和外卖请至少开启一个', {icon: 2},1500);
        return false;
      }
      if(is_take_out == 1 && delivery_km == ""){
        layer.msg('配送范围不能为空', {icon: 2},1500);
        return false;
      }
      if(begin_time == "" || begin_time == null){
      	layer.msg('营业开始时间不能为空', {icon: 2},1500);
          return false;
      }
      if(end_time == "" || end_time == null){
      	layer.msg('营业结束时间不能为空', {icon: 2},1500);
          return false;
      }
      if(provid == "" || provid == null){
      	layer.msg('请选择省', {icon: 2},1500);
          return false;
      }
      if(cityid == "" || cityid == null){
      	layer.msg('请选择市', {icon: 2},1500);
          return false;
      }
      if(areaid == "" || areaid == null){
      	layer.msg('请选择县/区', {icon: 2},1500);
          return false;
      }
      if(address == "" || address == null){
      	layer.msg('详细地址不能为空', {icon: 2},1500);
          return false;
      }
      if(longitude == "" || longitude == null){
      	layer.msg('经度不能为空', {icon: 2},1500);
          return false;
      }
      if(latitude == "" || latitude == null){
      	layer.msg('纬度不能为空', {icon: 2},1500);
          return false;
      }
      if(logo == "" || logo == null){
        layer.msg('请先上传餐厅LOGO', {icon: 2},1500);
          return false;
      }
      if(phone == "" || phone == null){
      	layer.msg('联系方式不能为空', {icon: 2},1500);
          return false;
      }
      if(!(/^1[34578]\d{9}$/.test(phone))){ 
        layer.msg('联系方式有误，请重填', {icon: 2},1500); 
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
	    	layer.msg('请选择省份', {icon: 2},1500);
            return;
	    }
	    if(city.val() == "" || city.val() == null){
	    	layer.msg('请选择城市', {icon: 2},1500);
            return;
	    }
	    if(area.val() == "" || area.val() == null){
	    	layer.msg('请选择县区', {icon: 2},1500);
            return;
	    }
	    if(address == "" || address == null){
	    	layer.msg('详细地址不能为空', {icon: 2},1500);
            return;
	    }

        layer.open({
          type: 2,
          title: false,
          shadeClose: false,
          shade: 0.1,
          area: ['700px', '550px'],
          content: 'map?province='+province.text()+"&city="+city.text()+"&area="+area.text()+"&address="+address,
          end: function(){

            }
        });        
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

  function del_figure_img(obj,img_id){
    if(img_id > 0){
      layer.confirm('确定要删除此图片？', {
        btn: ['确定','取消'] //按钮
      }, function(){
        $.ajax({  
          type: "POST",
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
          url: "{{ route('cater.shop.delFigureImg') }}",  
          data: {img_id:img_id},  
          dataType: "json",  
          success: function(res){
            if(res.errcode == 1){ //成功
              $(obj).parent().parent().remove();
            }

            layer.closeAll();
          }  
        }); 
      }, function(){

      });
    }else{
      layer.confirm('确定要删除此图片？', {
        btn: ['确定','取消'] //按钮
      }, function(){
          $(obj).parent().parent().remove();
          layer.closeAll();
      }, function(){

      });
    }
  }
</script>
@endsection