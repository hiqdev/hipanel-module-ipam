<?php

use hipanel\helpers\Url;
use hipanel\modules\ipam\grid\PrefixGridView;
use hipanel\modules\ipam\menus\PrefixDetailMenu;
use hipanel\modules\ipam\models\AddressSearch;
use hipanel\modules\ipam\models\Prefix;
use hipanel\modules\ipam\widgets\TreeGrid;
use hipanel\widgets\MainDetails;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Prefix $model
 * @var ActiveDataProvider[] $parentPrefixesDataProvider
 * @var ActiveDataProvider[] $childPrefixesDataProvider
 */

$this->title = Html::encode($model->ip);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.ipam', 'Prefixes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $this->title,
            'subTitle' => Yii::t('hipanel.ipam', 'Prefix'),
            'menu' => PrefixDetailMenu::widget(['model' => $model], [
                'linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>',
            ]),
        ]) ?>
        <div class="box box-widget">
            <div class="box-body no-padding">
                <?= PrefixGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'family',
                        'type',
                        'vrf',
                        'aggregate',
                        'parent',
                        'role',
                        'site',
                        'utilization',
                        'tags',
                        'note',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#child_prefixes"
                       data-toggle="tab"><?= Yii::t('hipanel.ipam', 'Child Prefixes') ?></a>
                </li>
                <li>
                    <a href="#parent_prefixes"
                       data-toggle="tab"><?= Yii::t('hipanel.ipam', 'Parent Prefixes') ?></a>
                </li>
                <li>
                    <a href="<?= Url::to(['@address/index', (new AddressSearch)->formName() => ['ip_cnts' => $model->ip]]) ?>">
                        <?= Yii::t('hipanel.ipam', 'IP Addresses {count}', [
                            'count' => Html::tag('span', $model->ip_count, ['class' => 'label bg-red']),
                        ]) ?>
                    </a>
                </li>
                <li class="pull-right">
                    <?= Html::button(
                        Yii::t('hipanel', 'Update'),
                        [
                            'class' => 'btn bg-olive btn-bulk',
                            'disabled' => true,
                            'data-action' => Url::to(['@prefix/update']),
                            'style' => 'padding: 5px 10px; font-size: 12px; border-radius: 3px; margin-top: 4px;'
                        ]
                    ) ?>
                    <?= Html::button(
                        Yii::t('hipanel', 'Delete'),
                        [
                            'class' => 'btn bg-maroon btn-bulk',
                            'disabled' => true,
                            'data-action' => Url::to(['@prefix/delete']),
                            'style' => 'padding: 5px 10px; font-size: 12px; border-radius: 3px; margin-top: 4px;'
                        ]
                    ) ?>
                </li>
                <li class="pull-right">
                    <?= Html::a(
                        Yii::t('hipanel.ipam', 'Add a new prefix'),
                        ['@prefix/create'],
                        ['class' => 'btn bg-olive', 'style' => 'padding: 5px 10px; font-size: 12px; border-radius: 3px; margin-top: 4px;']
                    ) ?>
                </li>
            </ul>
            <div class="tab-content" style="position: relative;">
                <div class="overlay" style="display: none;"></div>
                <div class="tab-pane active" id="child_prefixes">
                    <?= TreeGrid::widget([
                        'parent' => $model,
                        'dataProvider' => $childPrefixesDataProvider,
                        'showAll' => false,
                        'includeSuggestions' => true,
                        'columns' => ['ip', 'vrf', 'role', 'utilization', 'site', 'text_note', 'checkbox'],
                    ]) ?>
                </div>
                <div class="tab-pane" id="parent_prefixes">
                    <?= TreeGrid::widget([
                        'dataProvider' => $parentPrefixesDataProvider,
                        'columns' => ['ip', 'vrf', 'role', 'utilization', 'site', 'text_note'],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
