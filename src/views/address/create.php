<?php

/* @var $this yii\web\View */
/* @var $model hipanel\modules\ipam\models\Aggregate */
/* @var $tags array */

$this->title = Yii::t('hipanel.ipam', 'Add new address');
$this->params['breadcrumbs'][] = ['label' => Yii::t('hipanel.ipam', 'IP Addresses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact('models', 'model')) ?>
