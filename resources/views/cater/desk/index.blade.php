@extends('layouts.app')
@section('content')
    <style>
        th, td, #page {
            text-align: center;
        }
    </style>
    <div style="padding: 15px;">
        <div class="demoTable formn">
            <a class="layui-btn layui-btn-normal button" href="{{ route('cater.desk.addDesk') }}">新增</a>
        </div>
        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <td style="width:5%;">编号</td>
                    <td style="width:9%;">桌号</td>
                    <td style="width:9%;">二维码</td>
                    <td style="width:9%;">操作</td>
                </tr>
                </thead>
                <tbody>
                @foreach($desk_list as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->name}}</td>
                        <td><img src='{{$v->img_path}}'/></td>
                        <td>
                            <button class="layui-btn layui-btn-normal layui-btn-sm"
                                    onclick="operate({{$v->id}},'qr_code')">生成二维码
                            </button>
                            <a class="layui-btn layui-btn-normal layui-btn-sm"
                               href="{{ route('cater.desk.addDesk') }}?desk_id={{$v->id}}">编辑</a>
                            <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="operate({{$v->id}},'del')">
                                删除
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="page">
            {{$desk_list->links()}}
        </div>
    </div>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
    <script>
        function operate(desk_id, type) {
            var msg = ""
            if (type == 'qr_code') {
                msg = "确定生成二维码";
            } else {
                msg = "确定删除此餐桌";
            }
            layer.confirm(msg, {
                btn: ['确定', '取消'] //按钮
            }, function () {
                $.ajax({
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('cater.desk.operate') }}",
                    data: {desk_id: desk_id, type: type},
                    dataType: "json",
                    success: function (data) {
                        if (data.errcode == 1) {
                            layer.msg(data.errmsg, {icon: 1}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg(data.errmsg, {icon: 2}, 1500);
                        }
                    }
                });
            }, function () {

            });
        }

    </script>
@endsection