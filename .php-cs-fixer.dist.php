<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests')
;

$config = new PhpCsFixer\Config();

$config->setRiskyAllowed(false)
    ->setRules([
        '@PER' => true,
        '@DoctrineAnnotation' => true,
        '@PhpCsFixer' => true,
        'general_phpdoc_annotation_remove' => true,

    ])
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/var/.php_cs.cache')
;

return $config;
