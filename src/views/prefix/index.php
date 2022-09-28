<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\ipam\grid\PrefixGridLegend;
use hipanel\modules\ipam\grid\PrefixGridView;
use hipanel\modules\ipam\grid\PrefixRepresentations;
use hipanel\modules\ipam\models\Aggregate;
use hipanel\widgets\gridLegend\GridLegend;
use hipanel\widgets\IndexPage;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;

/**
 * @var Aggregate $model
 * @var PrefixRepresentations $representationCollection
 * @var ArrayDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 */

$this->title = Yii::t('hipanel.ipam', 'Prefixes');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->beginContent('legend') ?>
        <?= GridLegend::widget(['legendItem' => new PrefixGridLegend($model)]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel.ipam', 'Add a new prefix'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?= $page->renderSorter([
            'attributes' => ['ip']
        ]) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkButton('update', Yii::t('hipanel', 'Update'))?>
        <?= $page->renderBulkDeleteButton('delete')?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= PrefixGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                'colorize' => true,
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page::end() ?>
