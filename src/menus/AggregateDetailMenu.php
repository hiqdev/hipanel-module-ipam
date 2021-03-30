<?php

namespace hipanel\modules\ipam\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\ipam\models\Aggregate;
use Yii;

class AggregateDetailMenu extends AbstractDetailMenu
{
    public Aggregate $model;

    public function items()
    {
        return [
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@aggregate/update', 'id' => $this->model->id],
            ],
            'delete' => [
                'label' => Yii::t('hipanel', 'Delete'),
                'icon' => 'fa-trash',
                'url' => ['@aggregate/delete', 'id' => $this->model->id],
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
