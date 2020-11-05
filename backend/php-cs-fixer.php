<?php

// https://mlocati.github.io/php-cs-fixer-configurator/#version:2.16
return PhpCsFixer\Config::create()
    ->setRules(array(
        '@PSR2' => true,
        'array_syntax' => array('syntax' => 'short'),
        'binary_operator_spaces' => [
            'operators' => [
                '=>' => 'align'
            ]
        ],
        'blank_line_after_namespace' => true,
        'blank_line_before_return' => false,
        'braces' => true,
        'cast_spaces' => array('space' => 'single'),
        'concat_space' => array('spacing' => 'one'),
        'declare_equal_normalize' => array('space' => 'none'),
        'doctrine_annotation_braces' => array(
            'syntax' => 'with_braces',
            'ignored_tags' => [
                'abstract', 'access', 'code', 'deprec', 'encode', 'exception', 'final', 'ingroup', 'inheritdoc',
                'inheritDoc', 'magic', 'name', 'toc', 'tutorial', 'private', 'static', 'staticvar', 'staticVar',
                'throw', 'api', 'author', 'category', 'copyright', 'deprecated', 'example', 'filesource', 'global',
                'ignore', 'internal', 'license', 'link', 'method', 'package', 'param', 'property', 'property-read',
                'property-write', 'return', 'see', 'since', 'source', 'subpackage', 'throws', 'todo', 'TODO', 'usedBy',
                'uses', 'var', 'version', 'after', 'afterClass', 'backupGlobals', 'backupStaticAttributes', 'before',
                'beforeClass', 'codeCoverageIgnore', 'codeCoverageIgnoreStart', 'codeCoverageIgnoreEnd', 'covers',
                'coversDefaultClass', 'coversNothing', 'dataProvider', 'depends', 'expectedException',
                'expectedExceptionCode', 'expectedExceptionMessage', 'expectedExceptionMessageRegExp', 'group', 'large',
                'medium', 'preserveGlobalState', 'requires', 'runTestsInSeparateProcesses', 'runInSeparateProcess',
                'small', 'test', 'testdox', 'ticket', 'uses', 'SuppressWarnings', 'noinspection', 'package_version',
                'enduml', 'startuml', 'fix', 'FIXME', 'fixme', 'override', 'part', 'usage'
            ]
        ),
        'elseif' => true,
        'fully_qualified_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => true,
        ],
        'increment_style' => ['style' => 'pre'],
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'lowercase_cast' => true,
        'lowercase_constants' => true,
        'lowercase_keywords' => true,
        'magic_constant_casing' => true,
        'method_argument_space' => true,
        'magic_method_casing' => true,
        'method_chaining_indentation' => true,
        'native_function_invocation' => [
            'scope' => 'namespaced',
        ],
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_closing_tag' => true,
        'no_empty_statement' => true,
        'no_trailing_whitespace' => true,
        'no_unused_imports' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_order' => true,
        'phpdoc_separation' => false,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_trim' => true,
        'single_blank_line_at_eof' => true,
        'single_blank_line_before_namespace' => true,
        'single_quote' => true,
        'standardize_not_equals' => true,
        'ternary_to_null_coalescing' => true,
        'visibility_required' => true,
    ))
    ->setFinder(PhpCsFixer\Finder::create());
