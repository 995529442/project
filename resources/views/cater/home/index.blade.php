@extends('layouts.app')
@section('content')
    <style>
        img {
            margin-left: 120px;
        }
    </style>
    <form class="layui-form" action="{{ route('cater.home.save') }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}">
        <div class="layui-form-item">
            <label class="layui-form-label">商家展示图：</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-sm" id="figure_img">多图片上传</button>
                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;margin-left: 120px;">
                    预览图：
                    <div class="layui-upload-list" id="preview_figure">
                        <ul>
                            @if($home_info != "")
                                @foreach($home_info as $v)
                                    <li style="display:inline-block;">
                                        <input type="hidden" name="figure_img_id[]" value="{{$v->id}}">
                                        <input type="hidden" name="figure_img[]" value="{{$v->img_path}}">
                                        <img style="width:150px;height:100px;" src="{{$v->img_path}}" alt=""
                                             class="layui-upload-img">
                                        <div style="display:inline-block;position:relative;top:-40px;width:20px;border:1px solid #F73455;border-radius: 50%;cursor: pointer;">
                                            <p style="padding-left:4px;color:#F73455;"
                                               onclick="del_figure_img(this,{{$v->id}})">X</p></div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </blockquote>
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
        layui.use(['layer', 'upload', 'element'], function () {
            var layer = layui.layer
                , element = layui.element
                , upload = layui.upload;

            //多图片上传
            upload.render({
                elem: '#figure_img'
                , url: '{{ route("cater.home.upload") }}'
                , multiple: true
                , accept: 'file' //普通文件
                , exts: 'jpg|png|gif|bmp|jpeg' //只允许上传图片文件
                , size: 1024 //限制文件大小，单位 KB
                , data: {'_token': $(".tag_token").val(), type: 'figure'}
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {

                    });
                }
                , done: function (res) {
                    if (res.errcode == 1) {
                        lis = $("#preview_figure ul li").length;

                        if (lis < 3) {
                            $('#preview_figure ul').append('<li style="display:inline-block;"><input type="hidden" name="figure_img_id[]" value="0"><input type="hidden" name="figure_img[]" value="' + res.path + '"><img style="width:150px;height:100px;" src="' + res.path + '" alt="' + res.path + '" class="layui-upload-img"><div style="display:inline-block;position:relative;top:-40px;width:20px;border:1px solid #F73455;border-radius: 50%;cursor: pointer;"><p style="padding-left:4px;color:#F73455;" onclick="del_figure_img(this,0)">X</p></div></li>')
                        } else {
                            layer.msg("首页展示图最多为3张", {icon: 2}, 1500);
                        }
                    }
                    //上传完毕
                }
            });
        });

        function del_figure_img(obj, img_id) {
            if (img_id > 0) {
                layer.confirm('确定要删除此图片？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('cater.home.delFigureImg') }}",
                        data: {img_id: img_id},
                        dataType: "json",
                        success: function (res) {
                            if (res.errcode == 1) { //成功
                                $(obj).parent().parent().remove();
                            }

                            layer.closeAll();
                        }
                    });
                }, function () {

                });
            } else {
                layer.confirm('确定要删除此图片？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    $(obj).parent().parent().remove();
                    layer.closeAll();
                }, function () {

                });
            }
        }
    </script>
@endsection