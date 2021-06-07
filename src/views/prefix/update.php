<?php

/* @var $this yii\web\View */
/* @var $model hipanel\modules\ipam\models\Prefix */
/* @var $models hipanel\modules\ipam\models\Prefix[] */
/* @var $tags array */

$this->title = Yii::t('hipanel.ipam', 'Update Prefix');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.ipam', 'Prefixes'), 'url' => ['index']];
if (count($models) > 1) {
    $this->params['breadcrumbs'][] = Yii::t('hipanel', 'Update');
} else {
    $this->params['breadcrumbs'][] = ['label' => $model->ip, 'url' => ['view', 'id' => $model->id]];
    $this->params['breadcrumbs'][] = $model->ip;
}

?>

<?= $this->render('_form', compact('models')) ?>
