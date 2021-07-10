<?php

namespace Kenty\RsaUtils;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

/**
 * Class Install
 * @package Kenty\RsaUtils
 */
class Install
{
    /**
     * @title postUpdate
     * @param Event $event
     */
    public static function postInstallOrUpdate(Event $event)
    {
        self::generateKeyPair();
    }

    /**
     * @title postPackageInstall
     * @param PackageEvent $event
     */
    public static function postPackageInstall(PackageEvent $event)
    {
        self::generateKeyPair();
    }

    /**
     * @title generateKeyPair
     * @param bool $showKey
     */
    public static function generateKeyPair(bool $showKey = false)
    {
        $rsa = RSA::instance();

        $rsa->generateKeyPair();

        $publicKey = $rsa->getConfig()->getPublicKey();

        $privateKey = $rsa->getConfig()->getPrivateKey();

        if ($publicKey && $privateKey) {
            echo 'Install success!' . PHP_EOL;
            if ($showKey) {
                echo 'Public_key: ' . $publicKey . PHP_EOL;
                echo 'Private_key: ' . $privateKey . PHP_EOL;
            }
        } else {
            echo 'Install fail!' . PHP_EOL;
        }
    }
}
