<?php
/**
*   @brief  加密数据--解密数据
*/
class EncryptUtls
{
    const ALGORITHM = "rijndael-256";
    const MODE = "cfb";
    const IV = "2df66968fdd720d7ce2845bcbae98edf";

    /**
    *   @brief 加密字符串数据
    *   @param  $data       要加密的字符串
    *   @param  $key        要加密的字符串对应的键值
    *   @return [string]    加密后的字符串
    */
    public static function encrypt($data, $key)
    {
        /* Open the cipher */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');

        /* Create key */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        /* Intialize encryption */
        mcrypt_generic_init($td, $key, self::IV);

        /* Encrypt data */
        $encrypted = mcrypt_generic($td, $data);

        /* Terminate encryption handler */
        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return base64_encode($encrypted);
    }

    /**
    *   @brief 解密字符串数据
    *   @param  $encryptedData          要解密的字符串
    *   @param  $key                    要解密的字符串对应的键值
    *   @return [string]                解密后的字符串
    */
    public static function decrypt($encryptedData, $key)
    {
        if (empty($encryptedData)) {
            return "";
        }

        $encryptedData = base64_decode($encryptedData);
        if (!$encryptedData) {
            return false;
        }

        /* Open the cipher */
        $td = mcrypt_module_open(self::ALGORITHM, '', self::MODE, '');

        /* Create key */
        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        /* Initialize encryption module for decryption */
        mcrypt_generic_init($td, $key, self::IV);

        /* Decrypt encrypted string */
        $decrypted = mdecrypt_generic($td, $encryptedData);

        /* Terminate decryption handle and close module */
        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return trim($decrypted);
    }
}


/**
*   @brief 示例
*/
    // $name = 'jack';
    // $age = 23;
    // $sex = 'w';

    // $data = "$name,$age,$sex";
    // $key = 'one';

    // $encrypt = new EncryptUtls();
    // $result = $encrypt->encrypt($data,$key);            // '3eFtmgNYi4ew'

    // $encryptedData = '3eFtmgNYi4ew';
    // $ends = $encrypt->decrypt($encryptedData,$key);     // "jack,23,w"