@extends('layouts.app')
@section('content')   
<style>
  th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
    <div class="demoTable formn">
        <a class="layui-btn layui-btn-normal button" href="{{ route('addSmsTemplate') }}">新增</a>
    </div>
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <td style="width:5%;">序号</td>
                <td style="width:10%;">模板ID</td>
                <td style="width:10%;">类型</td>
                <td style="width:10%;">是否启用</td>                
                <td style="width:10%;">操作</td>                                 
            </tr>
            </thead>
            <tbody>
             @foreach($sms_tem_list as $v)
                <tr>
                   <td>{{$v->id}}</td>
                   <td>{{$v->template_id}}</td>
                   <td>
                    @if($v->type == 1)
                      验证通知
                    @elseif($v->type == 2)
                      下单通知
                    @endif
                  </td>
                   <td>
                    @if($v->is_on == 1)
                      开启
                    @else
                      关闭
                    @endif
                  </td>
                   <td>
                     <button class="layui-btn layui-btn-warm layui-btn-sm" onclick="test({{$v->type}})">发送测试</button>
                     <a class="layui-btn layui-btn-normal layui-btn-sm" href="{{ route('addSmsTemplate') }}?sms_template_id={{$v->id}}">编辑</a>
                     <button class="layui-btn layui-btn-danger layui-btn-sm" onclick="del({{$v->id}})">删除</button>
                   </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
    <div id="page">
       {{$sms_tem_list->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
 function del(sms_template_id){
      layer.confirm('是否删除此模板', {
      btn: ['是','否'] //按钮
    }, function(){
      $.ajax({  
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
            url: "{{ route('delSmsTemplate') }}",  
            data: {sms_template_id:sms_template_id},  
            dataType: "json",  
            success: function(data){
               if(data.errcode == 1){
                  layer.msg('删除成功', {icon: 1},function(){
                      location.reload();
                  }); 
               }else{
                  layer.msg(data.errmsg, {icon: 2},1500); 
               }
            }  
          }); 
    }, function(){

    });
 }

   //测试短息
  function test(type){
    layer.open({
        type: 2,
        title: false,
        shadeClose: false,
        shade: 0.1,
        area: ['500px', '25%'],
        content: '{{ route("testSms")}}?type='+type,
        end: function(){

          }
      }); 
  }
</script>
@endsection