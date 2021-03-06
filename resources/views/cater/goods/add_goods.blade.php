@extends('layouts.app')
@section('content')
    <style>
        img {
            margin-left: 120px;
        }
    </style>
    <form class="layui-form" action="{{ route('cater.goods.save_goods') }}" method="post" enctype="multipart/form-data"
          onsubmit="return check_submit();">
        <input type="hidden" name="_token" class="tag_token" value="{{ csrf_token() }}">
        <input type="hidden" name="goods_id" value="{{$goods_info['id']}}">
        <div class="layui-form-item">
            <label class="layui-form-label">商品名称：</label>
            <div class="layui-input-block">
                <input type="text" name="good_name" id="good_name" autocomplete="off" class="layui-input"
                       value="{{$goods_info['good_name']}}" style="width:40%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">所属分类：</label>
            <div class="layui-input-inline">
                <select name="cate_id" id="cate_id" lay-filter="cate_id">
                    <option value="">请选择分类</option>
                    @foreach($cate_info as $v)
                        <option value="{{$v->cate_id}}"
                                @if($goods_info['cate_id'] == $v->cate_id) selected @endif>{{$v->cate_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否热卖：</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="is_hot_box" @if($goods_info['is_hot'] == 1) checked @endif id="is_hot_box"
                       lay-skin="switch" lay-text="ON|OFF" lay-filter="is_hot_box">
                <input type="hidden" name="is_hot" id="is_hot" value="{{$goods_info['is_hot']}}">
            </div>
            <label class="layui-form-label">是否新品：</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="is_new_box" @if($goods_info['is_new'] == 1) checked @endif id="is_new_box"
                       lay-skin="switch" lay-text="ON|OFF" lay-filter="is_new_box">
                <input type="hidden" name="is_new" id="is_new" value="{{$goods_info['is_new']}}">
            </div>
            <label class="layui-form-label">是否推荐：</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="is_recommend_box" @if($goods_info['is_recommend'] == 1) checked
                       @endif id="is_recommend_box" lay-skin="switch" lay-text="ON|OFF" lay-filter="is_recommend_box">
                <input type="hidden" name="is_recommend" id="is_recommend" value="{{$goods_info['is_recommend']}}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">缩略图：</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-sm" id="preview_thumb_id"
                        style="display:inline-block;">上传图片
                </button>
                <span>(建议：图片尺寸100px*100px,图片大小不能大于1M)</span>
                <input type="hidden" class="layui-btn" name="thumb_img" id="thumb_img"
                       value="{{$goods_info['thumb_img']}}">
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="preview_thumb"
                         @if($goods_info['thumb_img'] != "") src="{{$goods_info['show_thumb_img']}}"
                         style="width:100px;height:100px;" @endif>
                    <p id="demoText"></p>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">展示图：</label>
            <div class="layui-upload">
                <button type="button" class="layui-btn layui-btn-sm" id="figure_img">多图片上传</button>
                <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;margin-left: 120px;">
                    预览图：
                    <div class="layui-upload-list" id="preview_figure">
                        <ul>
                            @if($figure_img != "")
                                @foreach($figure_img as $v)
                                    <li style="display:inline-block;">
                                        <input type="hidden" name="figure_img_id[]" value="{{$v->id}}">
                                        <input type="hidden" name="figure_img[]" value="{{$v->img_path}}">
                                        <img style="width:150px;height:100px;" src="{{$v->img_path}}" alt=""
                                             class="layui-upload-img">
                                        <div style="display:inline-block;position:relative;top:-40px;width:20px;border:1px solid #F73455;border-radius: 70%;cursor: pointer;">
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
            <label class="layui-form-label">是否上架：</label>
            <div class="layui-input-inline">
                <input type="checkbox" name="isout_box" @if($goods_info['isout'] == 2) checked @endif id="isout_box"
                       lay-skin="switch" lay-text="ON|OFF" lay-filter="isout_box">
                <input type="hidden" name="isout" id="isout" value="{{$goods_info['isout']}}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">原价：</label>
            <div class="layui-input-block">
                <input type="text" oninput="clearNoNum(this)" name="original_price" id="original_price"
                       autocomplete="off" class="layui-input" value="{{$goods_info['original_price']}}"
                       style="width:20%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">现价：</label>
            <div class="layui-input-block">
                <input type="text" oninput="clearNoNum(this)" name="now_price" id="now_price" autocomplete="off"
                       class="layui-input" value="{{$goods_info['now_price']}}" style="width:20%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">库存：</label>
            <div class="layui-input-block">
                <input type="number" oninput="clearNum(this)" name="storenum" id="storenum" autocomplete="off"
                       class="layui-input" value="{{$goods_info['storenum']}}" style="width:20%;">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">虚拟销量：</label>
            <div class="layui-input-block">
                <input type="number" oninput="clearNum(this)" name="virtual_sell_count" id="virtual_sell_count"
                       autocomplete="off" class="layui-input" value="{{$goods_info['virtual_sell_count']}}"
                       style="width:20%;">
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">商品介绍：</label>
            <div class="layui-input-block">
                <textarea placeholder="请输入内容" name="introduce"
                          class="layui-textarea">{{$goods_info['introduce']}}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="submit" class="layui-btn" lay-filter="demo1">提交</button>
                <button type="button" class="layui-btn layui-btn-primary" onclick="javascript:history.go(-1);">取消
                </button>
            </div>
        </div>
    </form>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
    <script>
        layui.use(['layer', 'form', 'upload', 'element'], function () {
            var form = layui.form
                , layer = layui.layer
                , element = layui.element
                , upload = layui.upload;

            //监听指定开关
            form.on('switch(is_hot_box)', function (data) {
                if (this.checked) {
                    $("#is_hot").val(1);
                } else {
                    $("#is_hot").val(0);
                }
            });

            form.on('switch(is_new_box)', function (data) {
                if (this.checked) {
                    $("#is_new").val(1);
                } else {
                    $("#is_new").val(0);
                }
            });

            form.on('switch(is_recommend_box)', function (data) {
                if (this.checked) {
                    $("#is_recommend").val(1);
                } else {
                    $("#is_recommend").val(0);
                }
            });

            form.on('switch(isout_box)', function (data) {
                if (this.checked) {
                    $("#isout").val(2);
                } else {
                    $("#isout").val(1);
                }
            });

            //上传图片
            var tag_token = $(".tag_token").val();

            var uploadInst = upload.render({
                elem: '#preview_thumb_id'
                , url: '{{ route("cater.goods.upload") }}'
                , accept: 'file' //普通文件
                , exts: 'jpg|png|gif|bmp|jpeg' //只允许上传图片文件
                , size: 1024 //限制文件大小，单位 KB
                , data: {'_token': tag_token}
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#preview_thumb').css('width', '100px'); //图片链接（base64）
                        $('#preview_thumb').css('height', '100px'); //图片链接（base64）
                        $('#preview_thumb').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
                    var errcode = res.errcode;

                    if (errcode == 1) {
                        $("#thumb_img").val(res.path);
                    }
                }
                , error: function (res) {
                    console.log(res)
                }
            });

            //多图片上传
            upload.render({
                elem: '#figure_img'
                , url: '{{ route("cater.goods.upload") }}'
                , multiple: true
                , accept: 'file' //普通文件
                , exts: 'jpg|png|gif|bmp|jpeg' //只允许上传图片文件
                , size: 1024 //限制文件大小，单位 KB
                , data: {'_token': tag_token, type: 'figure'}
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
                            layer.msg("菜品展示图最多为3张", {icon: 2}, 1500);
                        }
                    }
                    //上传完毕
                }
            });
        });

        function clearNoNum(obj) {
            obj.value = obj.value.replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
            obj.value = obj.value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
            obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
            obj.value = obj.value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');//只能输入两个小数
            if (obj.value.indexOf(".") < 0 && obj.value != "") {//以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
                obj.value = parseFloat(obj.value);
            }
        }

        function clearNum(obj) {
            obj.value = obj.value.replace(/[^\d]/g, "");  //清除“数字”和“.”以外的字符
            obj.value = obj.value.replace(/\./g, ""); //只保留第一个. 清除多余的
        }

        //提交表单
        function check_submit() {
            var good_name = $("#good_name").val();
            var cate_id = $("#cate_id").val();
            var is_hot = $("#is_hot").val();
            var is_new = $("#is_new").val();
            var is_recommend = $("#is_recommend").val();
            var thumb_img = $("#thumb_img").val();
            var original_price = $("#original_price").val();
            var now_price = $("#now_price").val();
            var introduce = $("#introduce").val();
            var storenum = $("#storenum").val();
            var virtual_sell_count = $("#virtual_sell_count").val();

            if (good_name == "" || good_name == null) {
                layer.msg('商品名称不能为空', {icon: 2}, 1500);
                return false;
            }
            if (good_name.length > 50) {
                layer.msg('商品名称不能大于50个字符', {icon: 2}, 1500);
                return false;
            }
            if (cate_id == "" || cate_id == null) {
                layer.msg('请先选择所属分类', {icon: 2}, 1500);
                return false;
            }
            if (thumb_img == "" || thumb_img == null) {
                layer.msg('缩略图不能为空', {icon: 2}, 1500);
                return false;
            }
            if (now_price == "" || now_price == null) {
                layer.msg('原价不能为空', {icon: 2}, 1500);
                return false;
            }
            if (now_price == "" || now_price == null) {
                layer.msg('现价不能为空', {icon: 2}, 1500);
                return false;
            }
            if (now_price == "" || now_price == null) {
                layer.msg('现价不能为空', {icon: 2}, 1500);
                return false;
            }
            if (storenum == "" || storenum == null) {
                layer.msg('库存不能为空', {icon: 2}, 1500);
                return false;
            }

            if (virtual_sell_count == "" || virtual_sell_count == null) {
                layer.msg('虚拟销量不能为空', {icon: 2}, 1500);
                return false;
            }
        }

        function del_figure_img(obj, img_id) {
            if (img_id > 0) {
                layer.confirm('确定要删除此图片？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    $.ajax({
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ route('cater.goods.delFigureImg') }}",
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