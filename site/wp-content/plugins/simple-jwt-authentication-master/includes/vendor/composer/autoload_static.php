<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit89ab54d4543c163039c2cf5235fd7e1a
{
    public static $files = array (
        '19cefe1485315b72c45605e5be32d866' => __DIR__ . '/..' . '/donatj/phpuseragentparser/Source/UserAgentParser.php',
    );

    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit89ab54d4543c163039c2cf5235fd7e1a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit89ab54d4543c163039c2cf5235fd7e1a::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
