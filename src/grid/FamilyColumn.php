<?php

namespace hipanel\modules\ipam\grid;

use hiqdev\higrid\DataColumn;
use yii\helpers\IpHelper;
use Yii;

class FamilyColumn extends DataColumn
{
    public function init(): void
    {
        parent::init();
        $this->label = Yii::t('hipanel.ipam', 'Family');
    }

    public function getDataCellValue($model, $key, $index)
    {
        return sprintf('IPv%d', IpHelper::getIpVersion($model->ip));
    }
}
