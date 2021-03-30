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
        '@ip' => '/hosting/ip',
        '@request' => '/hosting/request',
        '@aggregate' => '/hosting/aggregate',
        '@prefix' => '/hosting/prefix',
        '@address' => '/hosting/address',
    ],
    'modules' => [
        'hosting' => [
            'class' => \hipanel\modules\hosting\Module::class,
        ],
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                'hipanel.hosting.ipam' => [
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
                    'hosting' => [
                        'menu' => [
                            'class' => \hipanel\modules\hosting\menus\SidebarMenu::class,
                        ],
                        'where' => [
                            'after' => ['servers', 'domains', 'tickets', 'finance', 'clients', 'dashboard'],
                            'before' => ['stock'],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
