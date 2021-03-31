<?php

namespace hipanel\modules\ipam\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\ipam\models\Aggregate;
use Yii;

class AggregateActionsMenu extends AbstractDetailMenu
{
    public Aggregate $model;

    public function items()
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-pencil',
                'url' => ['@aggregate/view', 'id' => $this->model->id],
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@aggregate/update', 'id' => $this->model->id],
            ],
        ];
    }
}
