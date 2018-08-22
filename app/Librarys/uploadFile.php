<?php
/**
 * Date: 2018/7/18
 * Time: 13:38
 * 上传文件
 */

namespace App\Librarys;

class uploadFile
{
    /**
     * 上传图片
     * @return array
     */
    public static function uploadImg($admin_id, $file, $upload_path)
    {
        $result = array(
            "errcode" => -1,
            "errmsg" => "上传失败",
            "path" => ""
        );
        //上传图片具体操作
        $file_name = $file['file']['name'];
        $file_tmp = $file["file"]["tmp_name"];
        $file_error = $file["file"]["error"];
        $file_size = $file["file"]["size"];

        if ($file_error > 0) { // 出错
            $result['errmsg'] = $file_error;
        } elseif ($file_size > 1048576) { // 文件太大了
            $result['errmsg'] = "上传文件不能大于1MB";
        } else {
            $date = date('Ymd');
            $file_name_arr = explode('.', $file_name);
            $new_file_name = date('YmdHis') . '.' . $file_name_arr[1];
            $path = "upload/" . $admin_id . $upload_path;
            $file_path = $path . $new_file_name;
            if (file_exists($file_path)) {
                $result['errmsg'] = "此文件已经存在啦";
            } else {
                //TODO 判断当前的目录是否存在，若不存在就新建一个!
                if (!is_dir($path)) {
                    mkdir($path, 0755, true);
                }
                $upload_result = move_uploaded_file($file_tmp, $file_path);
                //此函数只支持 HTTP POST 上传的文件
                if ($upload_result) {
                    $result['errcode'] = 1;
                    $result['errmsg'] = "文件上传成功";
                    $result['path'] = "/" . $file_path;
                } else {
                    $result['errmsg'] = "文件上传失败，请稍后再尝试";
                }
            }
        }
        return $result;
    }
}