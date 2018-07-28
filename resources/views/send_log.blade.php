@extends('layouts.app')
@section('content')   
<style>
  th,td,#page{text-align:center;}
</style>     
<div>
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <td style="width:5%;">序号</td>
                <td style="width:10%;">发送对象</td>
                <td style="width:10%;">发送内容</td>
                <td style="width:10%;">发送状态</td>                
                <td style="width:10%;">发送时间</td> 
                <td style="width:10%;">备注</td>                                
            </tr>
            </thead>
            <tbody>
             @foreach($send_log_list as $v)
                <tr>
                   <td>{{$v->id}}</td>
                   <td>{{$v->send_to}}</td>
                   <td>{{$v->content}}</td>
                   <td>
                    @if($v->is_success == 1)
                      成功
                    @else
                      失败
                    @endif
                   </td>
                   <td>
                    @if($v->send_time)
                       {{date('Y-m-d H:i:s',$v->send_time)}}
                    @endif
                   </td>
                   <td>{{$v->remark}}</td>
                </tr>
             @endforeach
            </tbody>
        </table>
    </div>
    <div id="page">
       {{$send_log_list->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>

</script>
@endsection