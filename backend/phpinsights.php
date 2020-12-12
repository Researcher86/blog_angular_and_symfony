<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use ObjectCalisthenics\Sniffs\Files\ClassTraitAndInterfaceLengthSniff;
use ObjectCalisthenics\Sniffs\Metrics\MethodPerClassLimitSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;

return [
    'preset' => 'symfony',
    'ide' => 'phpstorm',
    'exclude' => [
        'src/Kernel.php',
        'migrations',
        'php-cs-fixer.php',
    ],
    'add' => [
    ],
    'remove' => [
        ForbiddenNormalClasses::class,
        SuperfluousExceptionNamingSniff::class,
    ],
    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120,
            'ignoreComments' => false,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 7,
        ],
        MethodPerClassLimitSniff::class => [
            'maxCount' => 20,
        ],
        ClassTraitAndInterfaceLengthSniff::class => [
            'maxLength' => 400,
        ],
    ],
    'requirements' => [
        'min-quality' => 100,
        // https://github.com/nunomaduro/phpinsights/issues/367
        'min-complexity' => 93,
        'min-architecture' => 100,
        'min-style' => 100,
    ],
];
