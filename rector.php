<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\CodeQuality\Rector\ClassMethod\ActionSuffixRemoverRector;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
//    ->withPhpSets(php82: true)
        ->withSets([
            // define sets of rules
             LevelSetList::UP_TO_PHP_82,
//            SetList::CODE_QUALITY,
//            PHPUnitSetList::PHPUNIT_90,
             SetList::TYPE_DECLARATION,
            SymfonySetList::SYMFONY_CODE_QUALITY,
             SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
             SymfonySetList::SYMFONY_64,
            SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES
        ]

    )
    ->withRules([
//        AddVoidReturnTypeWhereNoReturnRector::class,
//        ReturnTypeFromStrictNativeCallRector::class,
//        ReturnTypeFromStrictScalarReturnExprRector::class,
        ActionSuffixRemoverRector::class
    ]);
