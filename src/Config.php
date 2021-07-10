<?php

namespace Kenty\RsaUtils;

/**
 * Class Config
 * @package Kenty\RsaUtils
 */
class Config
{
    /**
     * @var string 秘钥对存放目录
     */
    protected $keyPairPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'pem' . DIRECTORY_SEPARATOR;
    /**
     * @var string 公钥文件名称
     */
    protected $publicKeyName = 'public_key.pem';
    /**
     * @var string 私钥文件名称
     */
    protected $privateKeyName = 'private_key.pem';
    /**
     * @var int 私钥字节数
     */
    protected $privateKeyBits = 1024;
    /**
     * @var int 加解密PADDING类型
     */
    protected $padding = OPENSSL_PKCS1_PADDING;
    /**
     * @var int 签名算法
     */
    protected $signAlgorithm = OPENSSL_ALGO_MD5;
    /**
     * @var string
     */
    protected $publicKey = null;
    /**
     * @var string
     */
    protected $privateKey = null;

    /**
     * @title getPublicKey
     * @return string
     */
    public function getPublicKey(): string
    {
        if (!$this->publicKey) {
            $this->publicKey = file_get_contents($this->getPublicKeyFilePath());
        }
        return $this->publicKey;
    }

    /**
     * @title getPrivateKey
     * @return string
     */
    public function getPrivateKey(): string
    {
        if (!$this->privateKey) {
            $this->privateKey = file_get_contents($this->getPrivateKeyFilePath());
        }
        return $this->privateKey;
    }

    /**
     * @title setPublicKey
     * @param string $publicKey
     */
    final public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @title setPrivateKey
     * @param string $privateKey
     */
    final public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @title 获取私钥字节数
     * @return int
     */
    final public function getPrivateKeyBits(): int
    {
        return $this->privateKeyBits;
    }

    /**
     * @title 获取加解密PADDING类型
     * @return int
     */
    final public function getPadding(): int
    {
        return $this->padding;
    }

    /**
     * @title 获取签名算法
     * @return int
     */
    final public function getSignAlgorithm(): int
    {
        return $this->signAlgorithm;
    }

    /**
     * @title 获取公钥文件路径
     * @return string
     */
    final public function getPublicKeyFilePath(): string
    {
        return $this->keyPairPath . $this->publicKeyName;
    }

    /**
     * @title 获取私钥文件路径
     * @return string
     */
    final public function getPrivateKeyFilePath(): string
    {
        return $this->keyPairPath . $this->privateKeyName;
    }

    /**
     * @title 持久化密钥对
     * @param string $publicKey
     * @param string $privateKey
     */
    final public function storeKeyPair(string $publicKey, string $privateKey)
    {
        file_put_contents($this->getPublicKeyFilePath(), $publicKey);
        file_put_contents($this->getPrivateKeyFilePath(), $privateKey);
    }

    /**
     * @title 获取 openssl.cnf 文件路径
     * @desc  操作系统如为window系统且缺少openssl环境变量，需手动设置openssl.cnf文件路径，可重写该方法以使用自定义配置文件
     * @return string
     */
    public function getOpensslConfigPath(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'cnf' . DIRECTORY_SEPARATOR . 'openssl.cnf';
    }
}
