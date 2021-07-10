# rsa-utils

## 1. 引入

#### 1.1 配置 composer.json

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:kenty227/rsa-utils.git"
        },
        {
            "packagist": false
        }
    ],
    "require": {
        "kenty/rsa-utils": "1.0.0"
    },
    "scripts": {
        "post-install-cmd": [
            "Kenty\\RsaUtils\\Install::postInstallOrUpdate"
        ],
        "post-update-cmd": [
            "Kenty\\RsaUtils\\Install::postInstallOrUpdate"
        ],
        "install-rsa-utils": [
            "Kenty\\RsaUtils\\Install::generateKeyPair"
        ]
    },
    "scripts-descriptions": {
        "install-rsa-utils": "Generate a new key pair for the rsa-utils package!"
    }
}
```

#### 1.2 安装 / 更新

```
composer install / update
```

#### 1.3 生成密钥对（按需）

> 使用上述配置，在安装/更新后会自动执行生成密钥对脚本，如需重新生成可直接执行以下命令。

```
composer install-rsa-utils
```

## 2. 使用

#### 2.1 使用默认配置

```php
// 生成密钥对
Kenty\RsaUtils\RSA::instance()->generateKeyPair();

// 解密
Kenty\RsaUtils\RSA::instance()->decrypt("待解密字符串");
```

#### 2.2 使用自定义配置

##### 2.2.1 自定义 Config 类继承 \Kenty\RsaUtils\Config，并选择性重写属性或方法即可

```php
class Config extends \Kenty\RsaUtils\Config
{
    // TODO rewrite ...
}
```

##### 2.2.2 获取实例时传入自定义 Config 类对象即可

```php
$config = new \Config();
Kenty\RsaUtils\RSA::instance($config)->decrypt("待解密字符串");
```
