<?php

namespace hipanel\modules\ipam\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\ipam\models\Prefix;
use Yii;

class PrefixDetailMenu extends AbstractDetailMenu
{
    public Prefix $model;

    public function items()
    {
        return [
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@prefix/update', 'id' => $this->model->id],
            ],
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@prefix/delete', 'id' => $this->model->id],
                'linkOptions' => [
                    'data' => [
                        'confirm' => Yii::t('hipanel', 'Are you sure you want to delete this item?'),
                        'method' => 'POST',
                        'pjax' => '0',
                    ],
                ],
                'visible' => Yii::$app->user->can('ip.delete'),
            ],
        ];
    }
}
