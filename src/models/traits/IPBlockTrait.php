<?php

namespace hipanel\modules\ipam\models\traits;

use PhpIP\IP;
use PhpIP\IPBlock;
use PhpIP\IPv4;

trait IPBlockTrait
{
    public function getIPBlock()
    {
        if (!str_contains($this->ip, '/')) {
            $ip = IP::create($this->ip);

            return IPBLock::create($ip, $ip instanceof IPv4 ? 32 : 128);
        }

        return IPBLock::create($this->ip);
    }
}
