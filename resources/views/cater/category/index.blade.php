@extends('layouts.app')
@section('content')   
<style>
	th,td,#page{text-align:center;}
</style>     
<div style="padding: 15px;">
     <form method="get" action="{{ route('cater.category.index') }}">
        {{csrf_field()}}
        <div class="demoTable formn">
            分类名称：
            <div class="layui-inline">
               <input type="text" name="cate_name" id="cate_name" autocomplete="off" class="layui-input" value="{{$cate_name}}">
            </div>           
            <button type="submit" class="layui-btn layui-btn-normal button">查询</button>
            <button type="button" class="layui-btn layui-btn-normal button" onclick="add_cate();">新增</button>
        </div>          
    </form>
    <div class="layui-form">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr style="background-color: #f5f5f5;">
                <td style="width:10%;">编号</td>
                <td style="width:40%;">分类名称</td>
                <td style="width:20%;">排序</td>
                <td style="width:30%;">操作</td>
            </tr>
            </thead>
            <tbody>
            	@foreach($category_info as $v)
	                <tr>                  
	                    <td>{{$v->id}}</td>
	                    <td>{{$v->cate_name}}</td>
	                    <td>{{$v->sort}}</td>
	                    <td>
	                    	<button class="layui-btn layui-btn-normal layui-btn-sm" onclick="category_operate({{$v->id}},'edit')">编辑</button>
	                    	<button class="layui-btn layui-btn-danger layui-btn-sm" onclick="category_operate({{$v->id}},'del')">删除</button>
	                    </td>                   
	                </tr>
                @endforeach
            </tbody>
        </table>
    </div> 
    <div id="page">
        {{$category_info->links()}}
    </div>       
</div>
<script type="text/javascript" src="/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/assets/common/layui/layui.all.js"></script>
<script>
    //新增分类
    function add_cate(){
    	layer.open({
          type: 2,
          title: false,
          shadeClose: false,
          shade: 0.1,
          area: ['500px', '265px'],
          content: '{{ route("cater.category.add_cate")}}',
          end: function(){

            }
        }); 
    }
	//分类操作
	function category_operate(id,type){
      var cate_id = id;

	    if(type == 'del'){ 
	        layer.confirm('是否确定删除此分类', {
			  btn: ['确定','取消'] //按钮
			}, function(){
			   	$.ajax({  
		          type: "POST",
		          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},  
		          url: "{{ route('cater.category.operate') }}",  
		          data: {cate_id:cate_id,type:type},  
		          dataType: "json",  
		          success: function(data){
		          	console.log(data)
		             if(data.errcode == 1){
		             	layer.msg(data.errmsg, {icon: 1},function(){
		             		location.reload();
		             	});
		             }else{
		             	layer.msg(data.errmsg, {icon: 2},1500);
		             }
		          }  
		        }); 
			}, function(){

			});
	    }else if(type == 'edit'){
	    	layer.open({
	          type: 2,
	          title: false,
	          shadeClose: false,
	          shade: 0.1,
	          area: ['500px', '265px'],
	          content: '{{ route("cater.category.add_cate")}}?cate_id='+cate_id,
	          end: function(){

	            }
	        });
	    } 
	}
</script>
@endsection