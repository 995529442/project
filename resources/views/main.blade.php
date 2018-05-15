<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LarryBlogCMS-Home</title>
  <meta name="renderer" content="webkit"> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> 
  <meta name="apple-mobile-web-app-status-bar-style" content="black"> 
  <meta name="apple-mobile-web-app-capable" content="yes">  
  <meta name="format-detection" content="telephone=no"> 
  <link rel="stylesheet" type="text/css" href="/assets/common/layui/css/layui.css" media="all">
  <link rel="stylesheet" type="text/css" href="/assets/common/bootstrap/css/bootstrap.css" media="all">
  <link rel="stylesheet" type="text/css" href="/assets/common/global.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/main.css" media="all">
</head>
<body>
<section class="larry-wrapper">
    <!-- overview -->
  <div class="row state-overview">
    <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
      <section class="panel">
        <div class="symbol userblue layui-anim layui-anim-rotate"> <i class="iconpx-users"></i>
        </div>
        <div class="value">
          <a href="#">
            <h1 id="count1">10</h1>
          </a>
          <p>用户总量</p>
        </div>
      </section>
    </div>
    <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
      <section class="panel">
        <div class="symbol commred layui-anim layui-anim-rotate"> <i class="iconpx-user-add"></i>
        </div>
        <div class="value">
          <a href="#">
            <h1 id="count2">1</h1>
          </a>
          <p>今日注册用户</p>
        </div>
      </section>
    </div>
    <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
      <section class="panel">
        <div class="symbol articlegreen layui-anim layui-anim-rotate">
          <i class="iconpx-file-word-o"></i>
        </div>
        <div class="value">
          <a href="#">
            <h1 id="count3">50</h1>
          </a>
          <p>文章总数</p>
        </div>
      </section>
    </div>
    <div class="col-lg-3 col-sm-6 layui-anim layui-anim-up">
      <section class="panel">
        <div class="symbol rsswet layui-anim layui-anim-rotate">
          <i class="iconpx-check-circle"></i>
        </div>
        <div class="value">
          <a href="#">
            <h1 id="count4">0</h1>
          </a>
          <p>待审文章总数</p>
        </div>
      </section>
    </div>
  </div>
  <!-- overview end -->
</section>

<script type="text/javascript" src="/assets/common/layui/layui.js"></script>
<script type="text/javascript">
  layui.use(['jquery','layer','element'],function(){
    window.jQuery = window.$ = layui.jquery;
    window.layer = layui.layer;
        window.element = layui.element();

       $('.panel .tools .iconpx-chevron-down').click(function(){
           var el = $(this).parents(".panel").children(".panel-body");
           if($(this).hasClass("iconpx-chevron-down")){
               $(this).removeClass("iconpx-chevron-down").addClass("iconpx-chevron-up");
               el.slideUp(200);
           }else{
               $(this).removeClass("iconpx-chevron-up").addClass("iconpx-chevron-down");
               el.slideDown(200);
           }
       })

  });
</script>
<script type="text/javascript" src="/assets/jsplug/echarts.min.js"></script>
<script type="text/javascript" src="/assets/js/main.js"></script>
</body>
</html>