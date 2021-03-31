<?php

namespace hipanel\modules\ipam\models\query;

use hiqdev\hiart\ActiveQuery;

class AggregateQuery extends ActiveQuery
{
    public function init()
    {
        parent::init();
        $this->select(['with_child_prefixes']);
    }
}
