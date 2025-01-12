<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'attribute_empty_parentheses' => true,
        'declare_strict_types' => true,
        'final_class' => true,
        'list_syntax' => true,
        'mb_str_functions' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'return_assignment' => true,
        'self_static_accessor' => true,
        'simplified_if_return' => true,
        'simplified_null_return' => true,
        'ternary_to_null_coalescing' => true,
        'phpdoc_to_comment' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
