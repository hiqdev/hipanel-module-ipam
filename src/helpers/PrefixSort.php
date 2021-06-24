<?php

namespace hipanel\modules\ipam\helpers;

use hipanel\modules\ipam\models\Address;
use hipanel\modules\ipam\models\Aggregate;
use hipanel\modules\ipam\models\Prefix;
use hipanel\modules\ipam\models\traits\IPBlockTrait;

class PrefixSort
{
    public static function byKinship(array &$models): void
    {
        $result = [];
        function kinship(array $models, ?int $id, array &$result)
        {
            foreach ($models as $model) {
                if ($model->parent_id === $id) {
                    $result[] = $model;
                    kinship($models, $model->id, $result);
                }
            }
        }

        kinship($models, null, $result);
        $models = empty($result) ? $models : $result;
    }

    /**
     * @param Aggregate[]|Prefix[]|Address[] $models
     */
    public static function byCidr(array &$models): void
    {
        usort($models, static function ($a, $b): int {
            /**
             * @var IPBlockTrait $a
             * @var IPBlockTrait $b
             */
            return $a->getIPBlock()->getNetworkAddress()->numeric() <=> $b->getIPBlock()->getNetworkAddress()->numeric();
        });
    }
}
