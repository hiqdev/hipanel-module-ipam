<?php

use hipanel\modules\ipam\grid\AddressGridView;
use hipanel\modules\ipam\menus\AddressDetailMenu;
use hipanel\modules\ipam\models\Aggregate;
use hipanel\modules\ipam\widgets\TreeGrid;
use hipanel\widgets\MainDetails;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var Aggregate $model
 * @var ActiveDataProvider[] $parentPrefixesDataProvider
 */

$this->title = Html::encode($model->ip);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.ipam', 'IP Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-3">
        <?= MainDetails::widget([
            'title' => $this->title,
            'subTitle' => Yii::t('hipanel.ipam', 'IP Addresses'),
            'menu' => AddressDetailMenu::widget(['model' => $model], [
                'linkTemplate' => '<a href="{url}" {linkOptions}><span class="pull-right">{icon}</span>&nbsp;{label}</a>',
            ]),
        ]) ?>
        <div class="box box-widget">
            <div class="box-body no-padding">
                <?= AddressGridView::detailView([
                    'boxed' => false,
                    'model' => $model,
                    'columns' => [
                        'family',
                        'state',
                        'vrf',
                        'site',
                        'tags',
                        'link',
                        'note',
                    ],
                ]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="box box-widget">
            <div class="box-header">
                <h3 class="box-title"><?= Yii::t('hipanel.ipam', 'Parent prefixes') ?></h3>
            </div>
            <div class="box-body">
                <?= TreeGrid::widget([
                    'dataProvider' => $parentPrefixesDataProvider,
                    'showAll' => true,
                    'columns' => ['ip', 'state', 'vrf', 'role', 'site', 'text_note'],

                ]) ?>
            </div>
        </div>
    </div>
</div>
