<?php

namespace hipanel\modules\ipam\menus;

use hiqdev\yii2\menus\Menu;
use Yii;

class SidebarMenu extends Menu
{
    public function items(): array
    {
        $user = Yii::$app->user;

        return [
            'ipam' => [
                'label' => Yii::t('hipanel.ipam', 'IPAM'),
                'url' => ['/ipam/aggregate/index'],
                'icon' => 'fa-sitemap',
                'items' => [
                    'ip-addresses' => [
                        'label' => Yii::t('hipanel.ipam', 'IP Addresses'),
                        'url' => ['/ipam/address/index'],
                    ],
                    'prefixes' => [
                        'label' => Yii::t('hipanel.ipam', 'Prefixes'),
                        'url' => ['/ipam/prefix/index'],
                    ],
                    'aggregate' => [
                        'label' => Yii::t('hipanel.ipam', 'Aggregates'),
                        'url' => ['/ipam/aggregate/index'],
                    ],
                ],
                'visible' => $user->can('test.alpha'),
            ],
        ];
    }
}
