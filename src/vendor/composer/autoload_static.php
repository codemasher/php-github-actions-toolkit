<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3b9139ed484975fc4b172add44eae163
{
    public static $files = array (
        'a2a3f85d279d489986a3855b938c4d2f' => __DIR__ . '/..' . '/chillerlan/php-http-message-utils/src/includes.php',
        '71a289382e4ef3720852310b50d116ea' => __DIR__ . '/../..' . '/common.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'chillerlan\\Settings\\' => 20,
            'chillerlan\\HTTP\\Utils\\' => 22,
            'chillerlan\\HTTP\\' => 16,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Server\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
        ),
        'F' => 
        array (
            'Fig\\Http\\Message\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'chillerlan\\Settings\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-settings-container/src',
        ),
        'chillerlan\\HTTP\\Utils\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-http-message-utils/src',
        ),
        'chillerlan\\HTTP\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-httpinterface/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Server\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-server-handler/src',
            1 => __DIR__ . '/..' . '/psr/http-server-middleware/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-factory/src',
            1 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'Fig\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/fig/http-message-util/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3b9139ed484975fc4b172add44eae163::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3b9139ed484975fc4b172add44eae163::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3b9139ed484975fc4b172add44eae163::$classMap;

        }, null, ClassLoader::class);
    }
}
