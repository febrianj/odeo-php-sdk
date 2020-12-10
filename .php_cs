<?php

$rules = [
    '@Symfony' => true,
    '@PSR2' => true,
    'array_indentation' => true,
    'indentation_type' => true,
    'method_chaining_indentation' => true,
    'braces' => [
        'position_after_anonymous_constructs' => 'same'
    ],
    'no_extra_blank_lines' => [
        'tokens' => ['parenthesis_brace_block'],
    ],
    'array_syntax' => [
        'syntax' => 'short',
    ]
];

return PhpCsFixer\Config::create()
    ->setRules($rules);
