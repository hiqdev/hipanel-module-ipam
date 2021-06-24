<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class PrefixSearch extends Prefix
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes(): array
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'is_ip',
            'ip_cntd',
            'ip_cntd_eql',
            'ip_cnts',
            'ip_cnts_eql',
            'ip_cnts_cntd',

            'with_parent',
            'include_suggestions',
            'firstborn',
            'family',
            'no_parent',
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'family' => Yii::t('hipanel.ipam', 'Family'),
            'no_parent' => Yii::t('hipanel.ipam', 'Root prefixes only'),
        ]);
    }
}
