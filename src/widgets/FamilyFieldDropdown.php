<?php

namespace hipanel\modules\ipam\widgets;

use hipanel\widgets\AdvancedSearch;
use Yii;
use yii\base\Widget;

class FamilyFieldDropdown extends Widget
{
    public AdvancedSearch $search;

    public function run()
    {
        return $this->search->field('family')->dropDownList([
            '4' => Yii::t('hipanel.ipam', 'IPv4'),
            '6' => Yii::t('hipanel.ipam', 'IPv6'),
        ], ['prompt' => $this->search->model->getAttributeLabel('family')]);
    }
}
