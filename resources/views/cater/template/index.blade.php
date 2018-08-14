@extends('layouts.app')
@section('content')   
<style>
  th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
    <div class="demoTable formn">
        <a class="layui-btn layui-btn-normal button" href="{{ route('cater.template.addTemplate') }}">新增</a>
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
             @foreach($temp_list as $v)
                <tr>
                   <td>{{$v->id}}</td>
                   <td>{{$v->template_id}}</td>
                   <td>
                    @if($v->type == 1)
                      支付通知
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
                     <a class="layui-btn layui-btn-normal layui-btn-sm" href="{{ route('cater.template.addTemplate') }}?template_id={{$v->id}}">编辑</a>
                     <button class="layui-btn layui-btn-danger layui-btn-sm" onclick="del({{$v->id}})">删除</button>
                   </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
    <div id="page">
       {{$temp_list->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
 function del(temp_id){
      layer.confirm('是否删除此模板', {
      btn: ['是','否'] //按钮
    }, function(){
      $.ajax({  
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
            url: "{{ route('cater.template.delTemplate') }}",  
            data: {temp_id:temp_id},  
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

</script>
@endsection