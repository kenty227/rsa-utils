<?php
/**
 * RSA工具类
 *
 * @author  WuBaijian
 * @data    2020-10-19
 * @version 1.0.0
 */

namespace Kenty\RsaUtils;

/**
 * Class RSA
 * @package Kenty\RsaUtils
 */
class RSA
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var self
     */
    private static $instance = null;

    /**
     * RSAUtils constructor.
     * @param Config|null $config
     */
    public function __construct(Config $config = null)
    {
        if (is_null($config)) {
            $config = new Config();
        }

        $this->setConfig($config);
    }

    /**
     * @title instance
     * @param Config|null $config
     * @return self
     */
    public static function instance(Config $config = null): self
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @title 生成密钥对
     */
    public function generateKeyPair()
    {
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => $this->config->getPrivateKeyBits()
        ];

        $openssl = openssl_pkey_new($config);

        if (!$openssl) {
            $config['config'] = $this->config->getOpensslConfigPath();
            $openssl = openssl_pkey_new($config);
        }

        openssl_pkey_export($openssl, $privateKey, null, $config);

        $publicKey = openssl_pkey_get_details($openssl)['key'];

        // 持久化
        $this->config->storeKeyPair($publicKey, $privateKey);

        // 设置公私钥
        $this->config->setPublicKey($publicKey);
        $this->config->setPrivateKey($privateKey);
    }

    /**
     * @title 加密
     * @param string $str 待加密数据
     * @return string
     */
    public function encrypt(string $str): string
    {
        $publicKeyId = openssl_get_publickey($this->config->getPublicKey());

        try {
            $encrypted = '';
            $str = $this->str2utf8($str);
            $strArray = str_split($str, $this->config->getPrivateKeyBits() / 8 - 11);
            foreach ($strArray as $s) {
                openssl_public_encrypt($s, $encryptedTemp, $publicKeyId, $this->config->getPadding());
                $encrypted .= $encryptedTemp;
            }
        } finally {
            openssl_free_key($publicKeyId);
        }

        return base64_encode($encrypted);
    }

    /**
     * @title 解密
     * @param string $encryptData 加密数据
     * @return string
     */
    public function decrypt(string $encryptData): string
    {
        $privateKeyId = openssl_pkey_get_private($this->config->getPrivateKey());

        try {
            $decrypted = '';
            $strArray = str_split(base64_decode($encryptData), $this->config->getPrivateKeyBits() / 8);
            foreach ($strArray as $s) {
                openssl_private_decrypt($s, $decryptedTemp, $privateKeyId, $this->config->getPadding());
                $decrypted .= $decryptedTemp;
            }
        } finally {
            openssl_free_key($privateKeyId);
        }

        return $decrypted;
    }

    /**
     * @title 加签
     * @param string $str 待加签数据
     * @return string
     */
    public function sign(string $str): string
    {
        $privateKeyId = openssl_pkey_get_private($this->config->getPrivateKey());

        try {
            $str = $this->str2utf8($str);
            openssl_sign($str, $sign, $privateKeyId, $this->config->getSignAlgorithm());
        } finally {
            openssl_free_key($privateKeyId);
        }

        return base64_encode($sign);
    }

    /**
     * @title 验签
     * @param string $str  原始数据
     * @param string $sign 加签数据
     * @return bool
     */
    public function verifySign(string $str, string $sign): bool
    {
        $publicKeyId = openssl_get_publickey($this->config->getPublicKey());

        try {
            $str = $this->str2utf8($str);
            $result = openssl_verify($str, base64_decode($sign), $publicKeyId, $this->config->getSignAlgorithm());
        } finally {
            openssl_free_key($publicKeyId);
        }

        return (bool)$result;
    }

    /**
     * @title 将字符串编码转为utf8
     * @param string $str
     * @return string
     */
    private function str2utf8(string $str): string
    {
        $encode = mb_detect_encoding($str);
        $str = $encode === false ? $str : mb_convert_encoding($str, 'UTF-8', $encode);
        return is_string($str) ? $str : '';
    }
}
