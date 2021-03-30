<?php

/* @var $this yii\web\View */
/* @var $model hipanel\modules\ipam\models\Aggregate */
/* @var $tags array */

$this->title = Yii::t('hipanel.ipam', 'Editing IP address {ip}', ['ip' => $model->ip]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.ipam', 'IP Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ip, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('hipanel.ipam', 'Edit');

?>

<?= $this->render('_form', compact('models', 'model')) ?>
