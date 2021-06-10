<?php

use hipanel\widgets\DynamicFormWidget;
use hiqdev\combo\StaticCombo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use hipanel\modules\ipam\models\Prefix;

/* @var $this yii\web\View */
/* @var $models Prefix[] */

$form = ActiveForm::begin([
    'id' => 'prefix-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => reset($models)->scenario]),
]) ?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
    'widgetBody' => '.container-items', // required: css class selector
    'widgetItem' => '.item', // required: css class
    'limit' => 99, // the maximum times, an element can be cloned (default 999)
    'min' => 1, // 0 or 1 (default 1)
    'insertButton' => '.add-item', // css class
    'deleteButton' => '.remove-item', // css class
    'model' => reset($models),
    'formId' => 'prefix-form',
    'formFields' => [
        'ip',
        'type',
        'vrf',
        'role',
        'site',
        'tags',
        'note',
    ],
]) ?>

<div class="row container-items">
    <?php foreach ($models as $k => $model) : ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 item">
            <div class="box box-widget">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('hipanel.ipam', 'Prefix') ?></h3>
                </div>
                <div class="box-body">
                    <?= Html::activeHiddenInput($model, "[$k]id") ?>
                    <?= $form->field($model, "[$k]ip")->hint(Yii::t('hipanel.ipam', 'IPv4 or IPv6 network with mask')) ?>
                    <?= $form->field($model, "[$k]type")
                        ->dropDownList($this->context->getRefs('type,ip_prefix', 'hipanel.ipam'))
                        ->hint(Yii::t('hipanel.ipam', 'Operational status of this prefix')) ?>
                    <?= $form->field($model, "[$k]vrf")
                        ->dropDownList($this->context->getRefs('type,ip_vrf', 'hipanel.ipam'))
                        ->hint(Yii::t('hipanel.ipam', 'Virtual Routing and Forwarding')) ?>
                    <?= $form->field($model, "[$k]role")
                        ->dropDownList($this->context->getRefs('type,ip_prefix_role', 'hipanel.ipam'), ['prompt' => '---'])
                        ->hint(Yii::t('hipanel.ipam', 'The primary function of this prefix')) ?>
                    <?= $form->field($model, "[$k]site")->dropDownList($this->context->getRefs('type,location'), ['prompt' => '---']) ?>
                    <?= $form->field($model, "[$k]tags")->widget(StaticCombo::class, [
                        'data' => $model->getTagOptions(),
                        'hasId' => true,
                        'multiple' => true,
                    ]) ?>
                    <?= $form->field($model, "[$k]note")->textarea(['rows' => 2]) ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?= Html::submitButton(Yii::t('hipanel', reset($models)->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>

<?php DynamicFormWidget::end() ?>

<?php ActiveForm::end(); ?>
