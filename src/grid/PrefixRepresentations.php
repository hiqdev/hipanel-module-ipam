<?php

namespace hipanel\modules\ipam\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class PrefixRepresentations extends RepresentationCollection
{
    protected function fillRepresentations(): void
    {
        $this->representations = array_filter([
            'common' => [
                'label' => Yii::t('hipanel', 'common'),
                'columns' => [
                    'checkbox',
                    'actions',
                    'ip',
                    'type',
                    'vrf',
                    'role',
                    'site',
                    'utilization',
                    'note',
                ],
            ],
            'tree-grid-columns' => [
                'columns' => ['ip', 'vrf', 'role', 'utilization', 'site', 'text_note', 'checkbox'],
            ]
        ]);
    }
}
