@extends('layouts.app')
@section('content')
    <style>
        th, td, #page {
            text-align: center;
        }
    </style>
    <blockquote class="layui-elem-quote layui-text">
        用户列表 <a style="float:right;margin-top:-8px;" href="{{ route('cater.users.index') }}"
                class="layui-btn layui-btn-primary">返回</a>
    </blockquote>
    <div style="padding-left: 15px;padding-right: 15px;margin-top:-10px;">
        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <td style="width:5%;">编号</td>
                    <td style="width:9%;">操作人</td>
                    <td style="width:6%;">操作对象</td>
                    <td style="width:12%;">备注</td>
                </tr>
                </thead>
                <tbody>
                @foreach($log_list as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->operate_from}}</td>
                        <td>{{$v->operate_to}}</td>
                        <td>{{$v->remark}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="page">
            {{$log_list->links()}}
        </div>
    </div>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
    <script>
    </script>
@endsection