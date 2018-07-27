@extends('layouts.app')
@section('content')   
<style>
  th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
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
                     <button class="layui-btn layui-btn-warm layui-btn-sm" onclick="del()">发送测试</button>
                     <button class="layui-btn layui-btn-normal layui-btn-sm">编辑</button>
                     <button class="layui-btn layui-btn-danger layui-btn-sm" onclick="del()">删除</button>
                   </td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>      
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
 
</script>
@endsection