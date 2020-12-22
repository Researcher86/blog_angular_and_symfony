<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use ObjectCalisthenics\Sniffs\Files\ClassTraitAndInterfaceLengthSniff;
use ObjectCalisthenics\Sniffs\Files\FunctionLengthSniff;
use ObjectCalisthenics\Sniffs\Metrics\MethodPerClassLimitSniff;
use ObjectCalisthenics\Sniffs\NamingConventions\ElementNameMinimalLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousExceptionNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\UseSpacingSniff;

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
        SuperfluousInterfaceNamingSniff::class,
        UseSpacingSniff::class,
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
        ElementNameMinimalLengthSniff::class => [
            'minLength' => 3,
            'allowedShortNames' => ['i', 'j', 'id', 'to', 'up', 'io'],
        ],
        FunctionLengthSniff::class => [
            'maxLength' => 40,
        ],
        ForbiddenSetterSniff::class => [
            'exclude' => [
                'src/Controller/BaseController.php',
            ],
        ],
//        BinaryOperatorSpacesFixer::class => [
//            'align_double_arrow' => true,
//            'align_equals'       => false,
//        ],
    ],
    'requirements' => [
        'min-quality' => 100,
        // https://github.com/nunomaduro/phpinsights/issues/367
        'min-complexity' => 80,
        'min-architecture' => 100,
        'min-style' => 100,
    ],
];
