<?php

namespace hipanel\modules\ipam\grid;

use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

class AggregateRepresentations extends RepresentationCollection
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
                    'rir',
                    'utilization',
                    'note',
                ],
            ],
        ]);
    }
}
