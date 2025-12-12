<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(['.'])
    ->exclude([
        '.build',
        'bootstrap/cache',
        'lang',
        'public',
        'storage',
        'stubs',
        'vendor',
    ]);

$cacheDirectory = '/tmp/';

// phpcs:ignore Generic.PHP.ForbiddenFunctions
$cacheFilePath = (is_dir($cacheDirectory) ? $cacheDirectory : '') . '.php-cs-fixer.cache.json';

return new Config()
    ->setFinder($finder)
    ->setCacheFile($cacheFilePath);
