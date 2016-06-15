<?php

$dir = __DIR__;
require_once($dir . "/qiniu_sdk/autoload.php");

// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;

class QiniuBridgeImpl implements IBridge
{
    private $auth = null;
    private $uploadMgr = null;
    private $bucket = "";

    private static $accessKey = "t8JG8h3M-V0rzD3YMZOmTPlYZjoQjPyGmdXKmLEF";
    private static $secretKey = "da913H74FBHesSafZsp8zCKKUPVGL1BAWmbXGob3";

    public function __construct()
    {
        // 构建鉴权对象
        $this->auth = new Auth(self::$accessKey, self::$secretKey);

        // 初始化 UploadManager 对象并进行文件的上传
        $this->uploadMgr = new UploadManager();
    }

    /**
     * @brief 指定文件的存储空间。
     * 
     * @param $space 
     * 
     * @return 
     */
    public function init($space)
    {
        $this->bucket = $space;
    }

    /** 
     * @brief 向七牛云上传文件
     * 
     * @param $filePath 要上传的文件路径
     * @param $key 上传到七牛后保存的文件路径。为null的话则自动生成文件名
     * 
     * @return 
     */
    public function uploadFile($filePath, $key=null)
    {
        // 生成上传 Token
        $token = $this->auth->uploadToken($this->bucket);

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $this->uploadMgr->putFile($token, $key, $filePath);

        if ($err !== null) {
            return array(-1, $err);
        } else {
            return array(0, $ret);
        }
    }

    /** 
     * @brief 向云端上传文本内容
     * 
     * @param $content 要上传的文本内容，可以是二进制内容
     * @param $key  上传后的文件名
     * 
     * @return 
     */
    public function uploadContent($content, $key=null)
    {
        if (!$content) {
            return array(-1, "content can not be empty");
        }

        $md5 = md5($content . ":" . microtime());
        $tmp_file = "/tmp/" . $md5;

        if (file_put_contents($tmp_file, $content) === false) {
            return array(-1, "fail to create tmp file");
        }

        $result = $this->uploadFile($tmp_file, $key);

        // 删除临时文件
        @unlink($tmp_file);

        return $result;
    }
}
