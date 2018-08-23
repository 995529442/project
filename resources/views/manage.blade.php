@extends('layouts.app')
@section('content')
    <style>
        th, td, #page {
            text-align: center;
        }
    </style>
    <div>
        <form method="get" action="{{ route('manage') }}">
            {{csrf_field()}}
            <div class="demoTable formn">
                管理员名称：
                <div class="layui-inline">
                    <input type="text" name="username" id="username" autocomplete="off" class="layui-input"
                           value="{{$username}}">
                </div>
                <button type="submit" class="layui-btn layui-btn-normal button">查询</button>
                <button type="button" class="layui-btn layui-btn-normal button" onclick="add_admin();">新增</button>
            </div>
        </form>
        <div class="layui-form">
            <table class="layui-table" lay-size="sm">
                <thead>
                <tr style="background-color: #f5f5f5;">
                    <td style="width:10%;">编号</td>
                    <td style="width:35%;">用户名</td>
                    <td style="width:35%;">拥有模块</td>
                    <td style="width:20%;">操作</td>
                </tr>
                </thead>
                <tbody>
                @foreach($manage_info as $v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->username}}</td>
                        <td>{{$v->admin_module_name}}</td>
                        <td>
                            <a href="{{ route('module',['admin_id'=>$v->id]) }}"
                               class="layui-btn layui-btn-normal layui-btn-sm">分配模块</a>
                            <button class="layui-btn layui-btn-danger layui-btn-sm" onclick="reset({{$v->id}})">重置密码
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="page">

        </div>
    </div>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
    <script>
        //新增管理员
        function add_admin() {
            layer.open({
                type: 2,
                title: false,
                shadeClose: false,
                shade: 0.1,
                area: ['500px', '200px'],
                content: '{{ route("add_admin")}}',
                end: function () {

                }
            });
        }

        //重置密码
        function reset(id) {
            layer.confirm('是否重置该用户密码为888888？', {
                btn: ['是', '否'] //按钮
            }, function () {
                $.ajax({
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('reset_admin') }}",
                    data: {admin_id: id},
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