<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__.'/../../bin', __DIR__.'/../../config', __DIR__.'/../../src', __DIR__.'/../../tests'])
    ->withPhpSets()
    ->withComposerBased(symfony: true, phpunit: true)
    ->withAttributesSets(symfony: true, phpunit: true)
    ->withSkip([
        \Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector::class,
        \Rector\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector::class,
    ]);
