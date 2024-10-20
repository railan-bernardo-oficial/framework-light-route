<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb7d65469378d6cebe7d4ebfc937ad419
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb7d65469378d6cebe7d4ebfc937ad419::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb7d65469378d6cebe7d4ebfc937ad419::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb7d65469378d6cebe7d4ebfc937ad419::$classMap;

        }, null, ClassLoader::class);
    }
}
