@extends('layouts.app')
@section('content')
    <style>
        img {
            margin-left: 120px;
        }
    </style>
    <form class="layui-form" action="{{ route('saveSms') }}" method="post" onsubmit="return check_submit();">
        <input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}">
        <input type="hidden" value="@if($sms_list){{$sms_list->id}}@endif" name="sms_id">
        <div class="layui-form-item">
            <label class="layui-form-label">accountsid：</label>
            <div class="layui-input-block">
                <input type="text" name="accountsid" id="accountsid" autocomplete="off" class="layui-input"
                       value="@if($sms_list){{$sms_list->accountsid}}@endif" style="display:inline-block;width:30%;">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">appid：</label>
            <div class="layui-input-block">
                <input type="text" name="appid" id="appid" autocomplete="off" class="layui-input"
                       value="@if($sms_list){{$sms_list->appid}}@endif" style="display:inline-block;width:30%;">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">token：</label>
            <div class="layui-input-block">
                <input type="text" name="token" id="token" autocomplete="off" class="layui-input"
                       value="@if($sms_list){{$sms_list->token}}@endif" style="display:inline-block;width:30%;">
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
        function check_submit() {
            var accountsid = $("#accountsid").val();
            var appid = $("#appid").val();
            var token = $("#token").val();

            if (accountsid == "") {
                layer.msg('accountsid不能为空', {icon: 2}, 1500);
                return false;
            }
            if (appid == "") {
                layer.msg('appid不能为空', {icon: 2}, 1500);
                return false;
            }
            if (token == "") {
                layer.msg('token不能为空', {icon: 2}, 1500);
                return false;
            }
        }
    </script>
@endsection