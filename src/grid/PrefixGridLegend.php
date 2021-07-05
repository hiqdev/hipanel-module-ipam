<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\ipam\grid;

use hipanel\modules\ipam\models\Prefix;
use hipanel\widgets\gridLegend\BaseGridLegend;
use hipanel\widgets\gridLegend\GridLegendInterface;
use Yii;

class PrefixGridLegend extends BaseGridLegend implements GridLegendInterface
{
    /** @var Prefix */
    protected $model;

    public function items()
    {
        return [
            [
                'label' => Yii::t('hipanel.ipam', 'Container'),
                'color' => '#e7f4fb',
                'rule' => $this->model->type === Prefix::TYPE_CONTAINER,
                'columns' => ['actions'],
            ],
        ];
    }
}
