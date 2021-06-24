<?php

use hipanel\modules\ipam\widgets\FamilyFieldDropdown;
use hipanel\widgets\AdvancedSearch;
use yii\web\View;

/**
 * @var AdvancedSearch $search
 * @var $this View
 */
?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip_like') ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('ip_cnts') ?>
</div>

<?php if (!empty($search->model->ip_cnts)): ?>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <?= $search->field('with_suggestions')->checkbox(['unselect' => '']) ?>
    </div>
<?php endif ?>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('tags')->dropDownList($search->model->getTagOptions(), ['prompt' => $search->model->getAttributeLabel('tags')]) ?>
</div>

<div class="col-md-4 col-sm-6 col-xs-12">
    <?= FamilyFieldDropdown::widget(['search' => $search]) ?>
</div>
