<?php

namespace hipanel\modules\ipam\models\traits;

use PhpIP\IPBlock;

trait IPBlockTrait
{
    public function getIPBlock()
    {
        return IPBLock::create($this->ip);
    }
}