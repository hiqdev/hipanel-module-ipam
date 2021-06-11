<?php

namespace hipanel\modules\ipam\grid;

use hipanel\grid\XEditableColumn;
use hipanel\modules\ipam\menus\AddressActionsMenu;
use hipanel\modules\ipam\models\Address;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;

class AddressGridView extends PrefixGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'ip' => [
                'format' => 'raw',
                'attribute' => 'ip',
                'filterAttribute' => 'ip_like',
                'value' => static function ($address) {
                    $ip = Html::a(Html::encode($address->ip), ['@address/view', 'id' => $address->id], ['class' => 'text-bold']);
                    $tags = TagsColumn::renderTags($address);

                    return implode('<br>', [$ip, $tags]);
                },
            ],
            'link' => [
                'format' => 'raw',
                'attribute' => 'device',
                'enableSorting' => false,
                'value' => static function (Address $address) {
                    $device = Html::encode($address->device);
                    if (Yii::getAlias('@server', false)) {
                        return Html::a($device, ['@server/view', 'id' => $address->device_id]);
                    }

                    return $device;
                }
            ],
            'note' => [
                'class' => XEditableColumn::class,
                'enableSorting' => false,
                'pluginOptions' => [
                    'url' => '@address/set-note',
                ],
                'filter' => true,
                'filterAttribute' => 'note_ilike',
                'popover' => Yii::t('hipanel', 'Make any notes for your convenience'),
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'contentOptions' => ['style' => 'width: 1%; white-space:nowrap;'],
                'menuClass' => AddressActionsMenu::class,
            ],
        ]);
    }
}
