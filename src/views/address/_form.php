<?php

use hiqdev\combo\StaticCombo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hipanel\modules\ipam\models\Address */


$form = ActiveForm::begin([
    'id' => 'prefix-form',
    'enableClientValidation' => true,
    'validateOnBlur' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form', 'scenario' => $model->scenario]),
]);

?>

<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="box box-widget">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t('hipanel.ipam', 'IP Address') ?></h3>
            </div>
            <div class="box-body">
                <?= Html::activeHiddenInput($model, 'id', ['data-attribute' => 'id']) ?>
                <?= $form->field($model, 'ip', ['options' => ['style' => 'margin-bottom: 0;']])->hint(Yii::t('hipanel.ipam', 'IPv4 or IPv6 network with mask or specify a numeric range to create multiple IPs.')) ?>
                <div class="help-block" style="margin: 0 0 1em;">
                    <div class="format"><?= Yii::t('hipanel.ipam', 'Format:') ?>
                        <samp class="value"><?= Yii::t('hipanel.ipam', 'IPv4, IPv6 or IP Range') ?></samp>
                    </div>
                    <div class="example"><?= Yii::t('hipanel.ipam', 'Examples:') ?>
                        <?= Yii::t('hipanel.ipam', '{v4}, {v6}, {r4}, {r6}', [
                            'v4' => Html::tag('code', '192.0.2.1'),
                            'v6' => Html::tag('code', '2001:db8::'),
                            'r4' => Html::tag('code', '192.0.2.[1,5,100-254]/24'),
                            'r6' => Html::tag('code', '2001:db8:0:[0,fd-ff]::/64'),
                        ]) ?>
                    </div>
                </div>
                <?= $form->field($model, 'vrf')
                    ->dropDownList($this->context->getRefs('type,ip_vrf', 'hipanel.ipam'))
                    ->hint(Yii::t('hipanel.ipam', 'Virtual Routing and Forwarding')) ?>
                <?php if (!$model->isNewRecord) : ?>
                    <?= $form->field($model, 'site')->dropDownList($this->context->getRefs('type,location'), [
                        'value' => $model->parent->site,
                        'readonly' => true,
                        'disabled' => true,
                    ]) ?>
                <?php endif ?>
                <?= $form->field($model, "tags")->widget(StaticCombo::class, [
                    'data' => $model->getTagOptions(),
                    'hasId' => true,
                    'multiple' => true,
                ]) ?>
                <?= $form->field($model, 'note')->textarea(['rows' => 2]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?= $this->render('_links-form', ['form' => $form, 'model' => $model]) ?>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?= Html::submitButton(Yii::t('hipanel', $model->isNewRecord ? 'Create' : 'Save'), ['class' => 'btn btn-success']) ?>
        &nbsp;
        <?= Html::button(Yii::t('hipanel', 'Cancel'), ['class' => 'btn btn-default', 'onclick' => 'history.go(-1)']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
