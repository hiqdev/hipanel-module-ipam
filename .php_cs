<?php

$header = <<<EOF
IPAM for HiPanel

@link      https://github.com/hiqdev/hipanel-module-ipam
@package   hipanel-module-ipam
@license   BSD-3-Clause
@copyright Copyright (c) 2021, HiQDev (http://hiqdev.com/)
EOF;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        'header_comment'                             =>  [
            'header'        => $header,
            'separate'      => 'bottom',
            'location'      => 'after_declare_strict',
            'commentType'   => 'PHPDoc',
        ],
        'binary_operator_spaces'                     =>  [
            'default' => null,
        ],
        'concat_space'                               =>  ['spacing' => 'one'],
        'array_syntax'                               =>  ['syntax' => 'short'],
        'phpdoc_no_alias_tag'                        =>  ['replacements' => ['type' => 'var']],
        'blank_line_before_return'                   =>  false,
        'phpdoc_align'                               =>  false,
        'phpdoc_summary'                             =>  false,
        'phpdoc_scalar'                              =>  false,
        'phpdoc_separation'                          =>  false,
        'phpdoc_to_comment'                          =>  false,
        'phpdoc_var_without_name'                    =>  false,
        'method_argument_space'                      =>  false,
        'ereg_to_preg'                               =>  true,
        'blank_line_after_opening_tag'               =>  true,
        'single_blank_line_before_namespace'         =>  true,
        'ordered_imports'                            =>  true,
        'phpdoc_order'                               =>  true,
        'pre_increment'                              =>  true,
        'strict_comparison'                          =>  true,
        'strict_param'                               =>  true,
        'no_multiline_whitespace_before_semicolons'  =>  true,
        'semicolon_after_instruction'                =>  false,
        'yoda_style'                                 =>  false,
    ))
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->notPath('vendor')
            ->notPath('runtime')
            ->notPath('web/assets')
            ->notPath('public/assets')
            ->notPath('tests/_support/_generated')
        )
;
