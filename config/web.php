<?php
/**
 * IPAM for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-ipam
 * @package   hipanel-module-ipam
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2021, HiQDev (http://hiqdev.com/)
 */

return [
    'aliases' => [
        '@aggregate' => '/ipam/aggregate',
        '@prefix' => '/ipam/prefix',
        '@address' => '/ipam/address',
    ],
    'modules' => [
        'ipam' => [
            'class' => \hipanel\modules\ipam\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel.ipam' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => dirname(__DIR__) . '/src/messages',
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            \hiqdev\thememanager\menus\AbstractSidebarMenu::class => [
                'add' => [
                    'ipam' => [
                        'menu' => [
                            'class' => \hipanel\modules\ipam\menus\SidebarMenu::class,
                        ],
                        'where' => [
                            'after' => ['hosting'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
