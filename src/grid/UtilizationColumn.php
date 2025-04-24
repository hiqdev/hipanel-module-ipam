<?php

namespace hipanel\modules\ipam\grid;

use hiqdev\higrid\DataColumn;
use Yii;
use yii\bootstrap\Progress;
use yii\helpers\Html;

class UtilizationColumn extends DataColumn
{
    public $format = 'raw';

    public $filter = false;

    public function init()
    {
        parent::init();
        $this->label = Yii::t('hipanel.ipam', 'Utilization');
    }

    public function getDataCellValue($model, $key, $index)
    {
        $level = '';
        $prc = (int)$model->utilization ?? 0;
        switch ($prc) {
            case $prc >= 0 && $prc <= 40:
                $level = 'progress-bar-success';
                break;
            case $prc >= 41 && $prc <= 70:
                $level = 'progress-bar-warning';
                break;
            case $prc >= 71 && $prc <= 100:
                $level = 'progress-bar-danger';
                break;
        }

        return Progress::widget([
            'percent' => $prc ?? 0,
            'label' => Html::tag('span', sprintf("%d%%", $prc), ['style' => 'position: absolute; display: block; width: 100%;']),
            'barOptions' => ['class' => $level],
            'options' => ['style' => 'background-color: grey;position: relative;'],
        ]);
    }
}
