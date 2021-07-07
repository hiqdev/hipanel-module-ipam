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
        usort($models, static function ($a, $b): int {
            /**
             * @var IPBlockTrait $a
             * @var IPBlockTrait $b
             */
            $aBlock = $a->getIPBlock();
            $bBlock = $b->getIPBlock();

            if ($aBlock->contains($bBlock)) {
                return -1;
            }

            if ($aBlock->isIn($bBlock)) {
                return 1;
            }

            return 0;
        });
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
