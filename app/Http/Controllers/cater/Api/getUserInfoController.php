<?php
/**
 * User: 35727
 * Date: 2018/7/19
 * Time: 13:41
 */

namespace App\Http\Controllers\Cater\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class getUserInfoController extends Controller
{
    /**
     * 获取用户信息
     * @param Request $request
     * @return string
     */
    public function getUsers(Request $request) {
      require_once(public_path('/wx/wxBizDataCrypt.php'));   //引入解密文件   

      $return = array(
         "errcode" => -1,
         "errmsg" => "失败"
      );
      $admin_id = (int)$request -> input("admin_id",0);
      $code = $request -> input("code","");
      $iv = $request -> input("iv","");
      $encrypted_data = $request -> input("encrypted_data","");

      if(!$code){
        $return['errmsg'] = "code不能为空";
        return json_encode($return);
      }

      //查找APPID和SECRET
      $system_info = DB::table("cater_system")->select(['appid','appsecret'])->where(["admin_id"=>$admin_id,"isvalid"=>true])->first();

      if(!$system_info){
        $return['errmsg'] = "商家还没设置小程序配置信息";
        return json_encode($return);        
      }else{
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$system_info->appid."&secret=".$system_info->appsecret."&js_code=".$code."&grant_type=authorization_code"; 

        $output = $this -> curl_https($url,"",0);  
        
        $result = json_decode($output,true);
 
        if(isset($result['session_key'])){
            $pc = new \WXBizDataCrypt($system_info->appid, $result['session_key']);
            $errCode = $pc->decryptData($encrypted_data, $iv, $data );

            if ($errCode != 0) {
              $return['errmsg'] = "解密失败";
              return json_encode($return); 
            }else{
                $user_info = json_decode($data,true); 

                //保存用户数据
                $user = DB::table("cater_users")->where(['admin_id'=>$admin_id,'openid'=>$user_info['openId'],'isvalid'=>true])->first();

                $insert_data = array(
                    "weixin_name" => $user_info['nickName'],
                    "province" => $user_info['province'],
                    "city" => $user_info['city'],
                    "openid" => $user_info['openId'],
                    "unionid" => $user_info['unionId'],
                    "headimgurl" => $user_info['avatarUrl'],
                    "sex" => $user_info['gender']
                );
                if(!$user){ //插入数据
                   $insert_data['admin_id'] = $admin_id;
                   $insert_data['isvalid'] = true;

                   DB::table("cater_users")->insert($insert_data);
                }else{  //更新用户数据
                   DB::table("cater_users")->whereId($user->id)->update($insert_data);
                }

                return $user_info;
            }

        }else{
          $return['errmsg'] = "code参数失效";
          return json_encode($return);             
        }
      }

    }

    /**
     * curl
     */
    public static function curl_https($url, $data=array(), $is_post =1, $timeout=30, $debug=false)
    {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
      curl_setopt($ch, CURLOPT_URL, $url);
      //curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

      if($is_post == 1){
        curl_setopt($ch, CURLOPT_POST, true);   
      }

      if($data){
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

      $response = curl_exec($ch);

      if($error=curl_error($ch)){
          die($error);
      }
      curl_close($ch);

      return $response;

  }
}