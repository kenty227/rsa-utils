<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Kenty\RsaUtils\RSA;

// 获取实例
$rsa = RSA::instance();

// 生成密钥对
$rsa->generateKeyPair();

// 原始数据
$data = '测试时间 ' . date('Y-m-d H:i:s');
echo "原始数据：{$data}" . PHP_EOL;

// -- 加密算法 --
$encryptData = $rsa->encrypt($data);
echo "加密结果：{$encryptData}" . PHP_EOL;

$decryptData = $rsa->decrypt($encryptData);
echo "解密结果：{$decryptData}" . PHP_EOL;

// -- 签名算法 --
$sign = $rsa->sign($data);
echo "签名结果：{$sign}" . PHP_EOL;

$result = $rsa->verifySign($data, $sign);
echo "验签结果：" . ($result ? 'true' : 'false') . PHP_EOL;
