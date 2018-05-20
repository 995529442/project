@extends('layouts.app')
@section('content')  
<style>
  img{margin-left:120px;}
</style>       
<form class="layui-form" action="" method="post" onsubmit="return false;">
	<input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}">
  <input type="hidden" name="id" id="id" class="tag_token" value="{{$system['id']}}">  
    <div class="layui-form-item">
	    <label class="layui-form-label">appid：</label>
	    <div class="layui-input-block">
	      <input type="text" name="appid" id="appid" autocomplete="off" class="layui-input" value="{{$system['appid']}}" style="width:40%;">
	    </div>
    </div>

    <div class="layui-form-item">
      <label class="layui-form-label">appsecret：</label>
      <div class="layui-input-block">
        <input type="text" name="appsecret" id="appsecret" autocomplete="off" class="layui-input" value="{{$system['appsecret']}}" style="width:40%;">
      </div>
    </div> 

    <div class="layui-form-item">
      <label class="layui-form-label">商户号：</label>
      <div class="layui-input-block">
        <input type="text" name="mch_id" id="mch_id" autocomplete="off" class="layui-input" value="{{$system['mch_id']}}" style="width:40%;">
      </div>
    </div>  

    <div class="layui-form-item">    
      <label class="layui-form-label">apiclient_cert：</label>
      <div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-sm" id="preview_cert_id">上传</button>
        <input type="hidden" class="layui-btn" name="apiclient_cert" id="apiclient_cert" value="{{$system['apiclient_cert']}}">
        <div class="layui-upload-list">
          <p id="apiclient_cert_p">{{$system['apiclient_cert']}}</p>
        </div>
      </div>  
    </div>
    
    <div class="layui-form-item">    
      <label class="layui-form-label">apiclient_key：</label>
      <div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-sm" id="preview_key_id">上传</button>
        <input type="hidden" class="layui-btn" name="apiclient_key" id="apiclient_key" value="{{$system['apiclient_key']}}">
        <div class="layui-upload-list">
          <p id="apiclient_key_p">{{$system['apiclient_key']}}</p>
        </div>
      </div>  
    </div>

    <div class="layui-form-item">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" onclick="on_submit();">保存</button>
        <button type="button" class="layui-btn layui-btn">下载代码包</button>
      </div>
    </div>
</form>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
layui.use(['layer','form','upload','element'], function(){
    var form = layui.form
    ,layer = layui.layer
    ,element = layui.element
    ,upload = layui.upload;

    //上传图片
    var tag_token = $(".tag_token").val();

    upload.render({
      elem: '#preview_cert_id'
      ,url: '{{ route("cater.system.upload") }}'
      ,accept: 'file' //普通文件
      ,exts: 'pem' //只允许上传证书
      ,size: 1024 //限制文件大小，单位 KB
      ,data:{'_token':tag_token}
      ,done: function(res){
        if(res.status){
          $("#apiclient_cert").val(res.message)
          $('#apiclient_cert_p').html(res.message);           
        }else{
           layer.alert("上传失败",{icon:2});
        }
      }
      ,error: function(res){
         console.log(res)
      }
    });

    upload.render({
      elem: '#preview_key_id'
      ,url: '{{ route("cater.system.upload") }}'
      ,accept: 'file' //普通文件
      ,exts: 'pem' //只允许上传证书
      ,size: 1024 //限制文件大小，单位 KB
      ,data:{'_token':tag_token}
      ,done: function(res){
        if(res.status){
          $("#apiclient_key").val(res.message)
          $('#apiclient_key_p').html(res.message);           
        }else{
           layer.alert("上传失败",{icon:2});
        }
      }
      ,error: function(res){
         console.log(res)
      }
    });
  });

  //提交
  function on_submit(){
    var id = $("#id").val();
    var appid = $("#appid").val();
    var appsecret = $("#appsecret").val();
    var mch_id = $("#mch_id").val();
    var apiclient_cert = $("#apiclient_cert").val();
    var apiclient_key = $("#apiclient_key").val();

    if(appid == "" || appid == null){
      layer.alert("appid不能为空",{icon:2});
      return;
    }

    if(appsecret == "" || appsecret == null){
      layer.alert("appsecret不能为空",{icon:2});
      return;
    }

    if(mch_id == "" || mch_id == null){
      layer.alert("商户号不能为空",{icon:2});
      return;
    }

    if(apiclient_cert == "" || apiclient_cert == null){
      layer.alert("请上传apiclient_cert",{icon:2});
      return;
    }

    if(apiclient_key == "" || apiclient_key == null){
      layer.alert("请上传apiclient_key",{icon:2});
      return;
    }

    $.ajax({  
        type: "POST",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
        url: "{{ route('cater.system.saveSystem') }}",  
        data: {
          id:id,
          appid:appid,
          appsecret:appsecret,
          mch_id:mch_id,
          apiclient_cert:apiclient_cert,
          apiclient_key:apiclient_key
        },  
        dataType: "json",  
        success: function(res){
            if(res.errcode == 1){
              layer.alert("成功",{icon:1},function(){
                location.reload();
              })
            }else{
              layer.alert(res.errmsg);
            }
        }  
    });
  } 
</script>
@endsection