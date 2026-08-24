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
        // Converts getFilters()/getFunctions() to #[AsTwigFilter]/#[AsTwigFunction] attributes,
        // which requires symfony/twig-bundle 7.3+. This app runs 6.4.*, where the resulting
        // classes no longer implement Twig\Extension\ExtensionInterface (since they stop
        // extending AbstractExtension), which fatals the Twig environment at boot.
        \Rector\Symfony\Symfony73\Rector\Class_\GetFiltersAndFunctionsToAsTwigAttributeRector::class,
    ]);
