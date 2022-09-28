<?php

use hipanel\models\IndexPageUiOptions;
use hipanel\modules\ipam\grid\AddressGridView;
use hipanel\modules\ipam\models\Address;
use hipanel\widgets\IndexPage;
use hiqdev\higrid\representations\RepresentationCollection;
use hiqdev\hiart\ActiveDataProvider;
use yii\helpers\Html;

/**
 * @var Address $model
 * @var RepresentationCollection $representationCollection
 * @var ActiveDataProvider $dataProvider
 * @var IndexPageUiOptions $uiModel
 */

$this->title = Yii::t('hipanel.ipam', 'IP Addresses');
$this->params['subtitle'] = array_filter(Yii::$app->request->get($model->formName(), [])) ? Yii::t('hipanel', 'filtered list') : Yii::t('hipanel', 'full list');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $page = IndexPage::begin(['model' => $model, 'dataProvider' => $dataProvider]) ?>

    <?php $page->beginContent('main-actions') ?>
        <?= Html::a(Yii::t('hipanel.ipam', 'Add a new address'), 'create', ['class' => 'btn btn-sm btn-success']) ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('sorter-actions') ?>
        <?php if (!$dataProvider instanceof ArrayDataProvider): ?>
            <?= $page->renderSorter([
                'attributes' => ['ip']
            ]) ?>
        <?php endif ?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('bulk-actions') ?>
        <?= $page->renderBulkDeleteButton('delete')?>
    <?php $page->endContent() ?>

    <?php $page->beginContent('table') ?>
        <?php $page->beginBulkForm() ?>
            <?= AddressGridView::widget([
                'boxed' => false,
                'dataProvider' => $dataProvider,
                'filterModel' => $model,
                'columns' => $representationCollection->getByName($uiModel->representation)->getColumns(),
                'rowOptions' => static fn(Address $prefix, $key): array => [
                    'class' => sprintf("%s", $prefix->isSuggested() ? 'success' : ''),
                ],
            ]) ?>
        <?php $page->endBulkForm() ?>
    <?php $page->endContent() ?>
<?php $page::end() ?>
