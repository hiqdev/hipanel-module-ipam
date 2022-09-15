<?php

namespace hipanel\modules\ipam\widgets\combo;

use hiqdev\combo\Combo;

class AggregateCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'ipam/aggregate';

    /** {@inheritdoc} */
    public $name = 'ip';

    /** {@inheritdoc} */
    public $url = '/ipam/aggregate/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'ip'];
}
