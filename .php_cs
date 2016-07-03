<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in('src');

$fixers = [
    'align_double_arrow',
    'align_equals',
    'concat_with_spaces',
    'ordered_use',
    'phpdoc_indent',
    'phpdoc_inline_tag',
    'phpdoc_no_access',
    'phpdoc_no_package',
    'phpdoc_order',
    'phpdoc_scalar',
    'phpdoc_short_description',
    'phpdoc_summary',
    'phpdoc_to_comment',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_types',
    'phpdoc_var_without_name',
    'print_to_echo',
    'remove_leading_slash_use',
    'remove_lines_between_uses',
    'return',
    'short_array_syntax',
    'single_quote',
    'spaces_cast',
    'visibility'
];

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers($fixers)
    ->finder($finder);
