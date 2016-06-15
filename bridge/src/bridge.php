<?php

$dir = __DIR__;
require_once($dir . "/qiniu_bridge.php");

class Bridge implements IBridge
{
    private $impl = null;

    public function __construct($space)
    {
        if (!$space) {
            throw new Exception("bridge space can not be empty");
        }

        $this->impl = new QiniuBridgeImpl();
        $this->impl->init($space);
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
        $this->impl->init($space);
    }

    /** 
     * @brief 向云端上传文件
     * 
     * @param $filePath 要上传的本地文件路径
     * @param $key  上传后的文件名
     * 
     * @return 
     */
    public function uploadFile($filePath, $key=null)
    {
        return $this->impl->uploadFile($filePath, $key);
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
        return $this->impl->uploadContent($content, $key);
    }
}

interface IBridge
{
    /**
     * @brief 指定文件的存储空间。
     * 
     * @param $space 
     * 
     * @return 
     */
    public function init($space);


    /** 
     * @brief 向云端上传文件
     * 
     * @param $filePath 要上传的本地文件路径
     * @param $key  上传后的文件名
     * 
     * @return 
     */
    public function uploadFile($filePath, $key=null);

    /** 
     * @brief 向云端上传文本内容
     * 
     * @param $content 要上传的文本内容，可以是二进制内容
     * @param $key  上传后的文件名
     * 
     * @return 
     */
    public function uploadContent($content, $key=null);
}
