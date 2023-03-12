<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitf1a00ad48079b69ef1d12eb4f20a4ee6
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Spoova\\Enlist\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Spoova\\Enlist\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitf1a00ad48079b69ef1d12eb4f20a4ee6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitf1a00ad48079b69ef1d12eb4f20a4ee6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitf1a00ad48079b69ef1d12eb4f20a4ee6::$classMap;

        }, null, ClassLoader::class);
    }
}