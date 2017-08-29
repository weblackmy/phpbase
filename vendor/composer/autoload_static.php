<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3f2e03be225ec5fa643a81537dc59a4e
{
    public static $files = array (
        '841780ea2e1d6545ea3a253239d59c05' => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'Qiniu\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Qiniu\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiniu/php-sdk/src/Qiniu',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3f2e03be225ec5fa643a81537dc59a4e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3f2e03be225ec5fa643a81537dc59a4e::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
