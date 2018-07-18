<?php
/**
 * Date: 2018/7/18
 * Time: 14:52
 * 发送邮件
 */

namespace App\Librarys;

class Sms
{   
    /**
     * 发送短信
     * @return array
     */
    public static function sendSms($admin_id,$type,$templateid,$param,$mobile)
    {
		//载入ucpass类
		require_once('lib/Ucpaas.class.php');

		//初始化必填
		//填写在开发者控制台首页上的Account Sid
		$options['accountsid']='1d7110403b4f8b77609883ec0d0d86fc';
		//填写在开发者控制台首页上的Auth Token
		$options['token']='10563632a0be8ae15fe99247ca3d1d6b';

		//初始化 $options必填
		$ucpass = new \Ucpaas($options);

		$appid = "ff041bdac59e422891427f9d4919181b";	//应用的ID，可在开发者控制台内的短信产品下查看
		$templateid = "291494";    //可在后台短信产品→选择接入的应用→短信模板-模板ID，查看该模板ID
		$param = '3114019562857455751'; //多个参数使用英文逗号隔开（如：param=“a,b,c”），如为参数则留空
		$mobile = '13580730392';
		$uid = "";

		//70字内（含70字）计一条，超过70字，按67字/条计费，超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。

		echo $ucpass->SendSms($appid,$templateid,$param,$mobile,$uid);
    }
}