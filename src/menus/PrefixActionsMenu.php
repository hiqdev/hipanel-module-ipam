<?php

namespace hipanel\modules\ipam\menus;

use hipanel\menus\AbstractDetailMenu;
use hipanel\modules\ipam\models\Prefix;
use Yii;

class PrefixActionsMenu extends AbstractDetailMenu
{
    public Prefix $model;

    public function items(): array
    {
        return [
            'view' => [
                'label' => Yii::t('hipanel', 'View'),
                'icon' => 'fa-eye',
                'url' => ['@prefix/view', 'id' => $this->model->id],
            ],
            'update' => [
                'label' => Yii::t('hipanel', 'Update'),
                'icon' => 'fa-pencil',
                'url' => ['@prefix/update', 'id' => $this->model->id],
            ],
        ];
    }
}
