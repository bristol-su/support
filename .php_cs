<?php

use BristolSU\CodeStyle\PhpCS\Config;
use PhpCsFixer\Finder;

$finder = Finder::create();
$finder->in(__DIR__ . '/config');
$finder->in(__DIR__ . '/database');
$finder->in(__DIR__ . '/routes');
$finder->in(__DIR__ . '/src');
$finder->in(__DIR__ . '/tests');

$config = new Config();
$config->setFinder($finder);

return $config;
