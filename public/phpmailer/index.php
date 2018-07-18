<?php
header("Content-Type:text/html;charset=utf-8");
require_once("./functions.php");
$flag = sendMail('995529442@qq.com','萌宝贝你爱我吗',
    '<span style="color:skyblue;">欢迎加入大叔的怀抱</span><br/><span style="color:skyblue;">欢迎加入大叔的怀抱</span><br/><span style="color:skyblue;">欢迎加入大叔的怀抱</span><br/>');
if($flag){
    echo "发送邮件成功！";
}else{
    echo "发送邮件失败！";
}
?>