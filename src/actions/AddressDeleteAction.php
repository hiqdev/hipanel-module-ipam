<?php

namespace hipanel\modules\ipam\actions;

use hipanel\actions\RedirectAction;
use hipanel\actions\SmartPerformAction;
use Yii;
use yii\helpers\Url;

class AddressDeleteAction extends SmartPerformAction
{
    public function loadCollection($data = null)
    {
        parent::loadCollection(Yii::$app->request->get() ? [Yii::$app->request->get()] : null);
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'POST' => [
                'save' => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url' => function ($action) {
                        $first = $action->parent->collection->first;

                        if ($first->parent_ip) {
                            return Url::toRoute(['@prefix/view', 'id' => $first->parent_id]);
                        }

                        return 'index';
                    },
                ],
            ],
        ]);
    }
}

