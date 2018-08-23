<?php
/**
 * Date: 2018/7/18
 * Time: 14:52
 * 发送邮件
 */

namespace App\Librarys;

date_default_timezone_set('PRC');

use DB;

class Mail
{
	/**
	 * 发送邮件
	 * 暂时支持QQ邮箱
	 * @param $admin_id 商家ID
	 * @param $to 接收邮箱
	 * @param $title 主题
	 * @param $content 发件内容
	 * @return array
	 */
	public static function sendMail($admin_id, $to, $title, $content)
	{

		//引入PHPMailer的核心文件 使用require_once包含避免出现PHPMailer类重复定义的警告
		require_once('lib/phpmailer/class.phpmailer.php');
		require_once('lib/phpmailer/class.smtp.php');

		$result = array(
			"errcode" => -1,
			"errmsg" => "发送失败"
		);
		//获取配置
		if ($admin_id) {
			$mail_info = DB::table("mail")->where(["admin_id" => $admin_id, "type" => 1])->first();

			if ($mail_info) {
				$mail = new \PHPMailer();//实例化PHPMailer核心类
				//     $mail->SMTPDebug = 1;//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
				$mail->isSMTP();//使用smtp鉴权方式发送邮件
				$mail->SMTPAuth = true;//smtp需要鉴权 这个必须是true
				$mail->Host = 'smtp.qq.com';//链接qq域名邮箱的服务器地址
				$mail->SMTPSecure = 'ssl';//设置使用ssl加密方式登录鉴权
				$mail->Port = 465;//设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
				$mail->CharSet = 'UTF-8';//设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
				$mail->FromName = '邮件通知';//设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
				$mail->Username = $mail_info->name;//smtp登录的账号 这里填入字符串格式的qq号即可
				$mail->Password = $mail_info->password;//smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）【非常重要：在网页上登陆邮箱后在设置中去获取此授权码】
				$mail->From = $mail_info->name;//设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
				$mail->isHTML(true);//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
				$mail->addAddress($to);//设置收件人邮箱地址
				$mail->Subject = $title;//添加该邮件的主题
				$mail->Body = $content;//添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
				//简单的判断与提示信息
				$is_success = 0;
				$remark = '发送失败';

				if ($mail->send()) {
					$is_success = 1;
					$remark = '发送成功';
					$result['errcode'] = 1;
					$result['errmsg'] = "发送成功";
				}
				//记录日志
				DB::table("send_log")->insert([
					'admin_id' => $admin_id,
					'send_to' => $to,
					'content' => $content,
					'is_success' => $is_success,
					'remark' => $remark,
					'send_time' => time()
				]);
			} else {
				$result['errmsg'] = "该商家还没设置邮件配置";
			}
		} else {
			$result['errmsg'] = "无效商家";
		}

		return $result;
	}
}