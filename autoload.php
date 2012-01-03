<?php

require_once __DIR__ . '/vendors/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'PhpDirect'       => __DIR__.'/src',
    'Symfony'        => __DIR__.'/vendors',
));

$loader->registerPrefixes(array(
   'UniversalErrorCatcher_' => __DIR__.'/vendors/UniversalErrorCatcher/src',
));

$loader->register();