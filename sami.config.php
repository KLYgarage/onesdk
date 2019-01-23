<?php

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('vendor')
    ->exclude('test')
    ->in('./src')
;

$repoConf = array(
    'title' => 'ONE SDK API',
    'build_dir' => __DIR__.'/docs',
    'cache_dir' => __DIR__.'/.cache.ignore',
);

return new Sami($iterator, $repoConf);
