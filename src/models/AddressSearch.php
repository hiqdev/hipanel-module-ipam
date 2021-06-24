<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class AddressSearch extends Address
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'is_ip',
            'ip_cntd',
            'ip_cntd_eql',
            'ip_cnts',
            'ip_cnts_eql',
            'ip_cnts_cntd',
            'family',
            'with_suggestions',
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'family' => Yii::t('hipanel.ipam', 'Family'),
            'ip_cnts' => Yii::t('hipanel.ipam', 'Parent prefix'),
            'with_suggestions' => Yii::t('hipanel.ipam', 'With suggestions'),
        ]);
    }
}
