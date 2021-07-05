<?php

namespace hipanel\modules\ipam\grid;

use hipanel\grid\BoxedGridView;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\modules\ipam\menus\PrefixActionsMenu;
use hipanel\modules\ipam\models\AddressSearch;
use hipanel\modules\ipam\models\Prefix;
use hipanel\widgets\gridLegend\ColorizeGrid;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;

class PrefixGridView extends BoxedGridView
{
    use ColorizeGrid;

    public ?Prefix $parent = null;

    public function columns()
    {
        return array_merge(parent::columns(), [
            'ip' => [
                'label' => Yii::t('hipanel.ipam', 'IP'),
                'format' => 'raw',
                'attribute' => 'ip',
                'filterAttribute' => 'ip_like',
                'contentOptions' => ['style' => 'white-space:nowrap;'],
                'value' => function (Prefix $prefix) {
                    $ip = Html::encode($prefix->ip);
                    if ($prefix->isSuggested()) {
                        return Html::a($ip, [
                            '@prefix/create',
                            'ip' => $ip,
                            'vrf' => Html::encode($this->parent->vrf),
                            'role' => Html::encode($this->parent->role),
                            'site' => Html::encode($this->parent->site),
                        ], ['class' => 'text-bold']);
                    }
                    $ip = Html::a($ip, ['@prefix/view', 'id' => $prefix->id], ['class' => 'text-bold']);
                    $tags = TagsColumn::renderTags($prefix);

                    return implode('<br>', array_filter([$ip, $tags], static fn(string $entry): bool => $entry !== ''));
                },
            ],
            'type' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel.ipam',
                'gtype' => 'type,ip_prefix',
            ],
            'vrf' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel.ipam',
                'gtype' => 'type,ip_vrf',
                'value' => fn($model) => $model->vrf ?? $this->parent->vrf,
            ],
            'role' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel.ipam',
                'gtype' => 'type,ip_prefix_role',
                'value' => fn($model) => $model->role ?? $this->parent->role,
            ],
            'site' => [
                'class' => RefColumn::class,
                'i18nDictionary' => 'hipanel.ipam',
                'gtype' => 'type,location',
                'value' => fn($model) => $model->site ?? $this->parent->site,
            ],
            'parent' => [
                'format' => 'raw',
                'label' => Yii::t('hipanel.ipam', 'Parent'),
                'value' => function (Prefix $model): string {
                    if ($model->parent === null) {
                        return '';
                    }

                    return Html::a(Html::encode($model->parent->ip), ['@prefix/view', 'id' => $model->parent->id]);
                },
            ],
            'family' => [
                'class' => FamilyColumn::class,
            ],
            'utilization' => [
                'class' => UtilizationColumn::class,
            ],
            'note' => [
                'class' => XEditableColumn::class,
                'filterAttribute' => 'note_ilike',
                'pluginOptions' => [
                    'url' => '@prefix/set-note',
                ],
                'filter' => true,
                'popover' => Yii::t('hipanel', 'Make any notes for your convenience'),
            ],
            'text_note' => [
                'attribute' => 'note',
            ],
            'aggregate' => [
                'attribute' => 'aggregate',
                'format' => 'raw',
                'value' => static function ($prefix): string {
                    return $prefix->aggregate ?
                        Html::a(Html::encode($prefix->aggregate), ['@aggregate/view', 'id' => $prefix->aggregate_id]) :
                        '';
                },
            ],
            'ip_count' => [
                'attribute' => 'ip_count',
                'label' => Yii::t('hipanel.ipam', 'IP Addresses'),
                'format' => 'raw',
                'value' => static function ($prefix): string {
                    return $prefix->ip_count > 0 ?
                        Html::a('IP Addresses (' . $prefix->ip_count . ')', [
                            '@address/index', (new AddressSearch)->formName() => ['ip_cnts' => Html::encode($prefix->ip)],
                        ], ['class' => 'btn btn-success btn-flat btn-sm']) :
                        $prefix->ip_count;
                },
            ],
            'tags' => [
                'class' => TagsColumn::class,
            ],
            'actions' => [
                'class' => MenuColumn::class,
                'contentOptions' => ['style' => 'width: 1%; white-space:nowrap;'],
                'menuClass' => PrefixActionsMenu::class,
            ],
        ]);
    }
}
