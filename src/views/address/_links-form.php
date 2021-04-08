<?php

use hipanel\modules\hosting\models\Link;
use hipanel\modules\hosting\widgets\combo\ServiceCombo;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hipanel\widgets\DynamicFormWidget;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use hipanel\modules\ipam\models\Address;

/* @var $model Address */
/* @var $form ActiveForm */

$addedLinks = empty($model->getAddedLinks()) ? [new Link()] : $model->getAddedLinks();
$formatJs = new JsExpression("
function(data) {
    if (data.text === data.soft) {
        return data.text;
    } else if (data.text == '') {
        return data.soft;
    } else if (data.soft) {
        return data.text + '<br><small>' + data.soft + '</small>';
    } else {
        return data.text;
    }
}
");
$this->registerJs(<<<JS
$('.dynamic_links').on('afterInsert', function (e, item) {
    var new_ip_id = $(item).find('[data-attribute=ip_id]');
    var ip_id = $(item).closest('.ip-item').find('[data-attribute=id]');
    new_ip_id.val(ip_id.val());
});
JS
);
?>

<div class="box box-widget">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('hipanel.ipam', 'Links') ?></h3>
    </div>
    <div class="box-body">
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamic_links',
            'widgetBody' => '.links-container',
            'widgetItem' => '.link',
            'limit' => 20,
            'min' => 1,
            'insertButton' => '.add-link',
            'deleteButton' => '.remove-link',
            'model' => new Link(['scenario' => 'create']),
            'formId' => 'prefix-form',
            'formFields' => [
                'id',
                'ip_id',
                'device',
                'service_id',
            ],
        ]) ?>

        <div class="links-container">
            <?php foreach ($addedLinks as $linkId => $link) : ?>
                <?= Html::activeHiddenInput($link, "[$linkId]id", ['value' => $link->id]) ?>
                <?= Html::activeHiddenInput($link, "[$linkId]ip_id", ['data-attribute' => 'ip_id', 'value' => $model->id]) ?>
                <div class="link row">
                    <div class="col-md-5">
                        <?= $form->field($link, "[$linkId]device")->widget(ServerCombo::class, [
                            'pluginOptions' => [
                                'select2Options' => [
                                    'placeholder' => Yii::t('hipanel.ipam', 'Device'),
                                ],
                            ],
                            'formElementSelector' => '.link',
                            'inputOptions' => [
                                'data-combo-field' => 'device',
                            ],
                        ])->label(false) ?>
                    </div>
                    <div class="col-md-5">
                        <?= $form->field($link, "[$linkId]service_id")->widget(ServiceCombo::class, [
                            'pluginOptions' => [
                                'activeWhen' => [
                                    'server/server',
                                ],
                                'select2Options' => [
                                    'placeholder' => Yii::t('hipanel.ipam', 'Service'),
                                    'templateResult' => $formatJs,
                                    'templateSelection' => $formatJs,
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                ],
                            ],
                            'formElementSelector' => '.link',
                            'inputOptions' => [
                                'data-combo-field' => 'service_id',
                            ],
                        ])->label(false) ?>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="add-link btn btn-default">
                                <i class="glyphicon glyphicon-plus"></i>
                            </button>
                            <button type="button" class="remove-link btn btn-default">
                                <i class="glyphicon glyphicon-minus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <?php DynamicFormWidget::end() ?>
    </div>
</div>
