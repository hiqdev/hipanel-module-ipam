<?php

namespace hipanel\modules\ipam\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RenderAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\ipam\actions\TreeGridRowsAction;
use hipanel\modules\ipam\helpers\PrefixSort;
use hipanel\modules\ipam\models\Prefix;
use yii\base\Event;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use Yii;

class PrefixController extends CrudController
{
    public function behaviors(): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            [
                'class' => EasyAccessControl::class,
                'actions' => [
                    'create' => 'admin',
                    'update' => 'admin',
                    'delete' => 'admin',
                    '*' => 'ip.read',
                ],
            ],
        ]);
    }

    public function actions()
    {
        return array_merge(parent::actions(), [
            'index' => [
                'class' => IndexAction::class,
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => function (Event $event) {
                    $event->sender->getDataProvider()->query->withParent();
                },
                'data' => static function ($action): array {
                    $model = $action->getCollection()->first;
                    $children = Prefix::find()
                        ->andWhere(['ip_cnts' => $model->ip, 'vrf' => $model->vrf])
                        ->includeSuggestions()
                        ->withParent()
                        ->firstbornOnly()
                        ->limit(-1)
                        ->all();
                    PrefixSort::byCidr($children);
                    $childDataProvider = new ArrayDataProvider([
                        'allModels' => $children,
                        'pagination' => [
                            'pageSize' => -1,
                        ],
                    ]);
                    $parentDataProvider = new ArrayDataProvider([
                        'allModels' => $model->parent->id ? [$model->parent] : [],
                        'pagination' => [
                            'pageSize' => -1,
                        ],
                    ]);

                    return [
                        'childPrefixesDataProvider' => $childDataProvider,
                        'parentPrefixesDataProvider' => $parentDataProvider,
                    ];
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.ipam', 'Prefix was created successfully'),
                'error' => Yii::t('hipanel.ipam', 'An error occurred when trying to add a prefix'),
                'data' => static function (RenderAction $action): array {
                    $prefix = $action->getCollection()->getModel();
                    $request =$action->controller->request;
                    $prefix->ip = $request->get('ip');
                    $prefix->vrf = $request->get('vrf');
                    $prefix->role = $request->get('role');
                    $prefix->site = $request->get('site');

                    return [
                        'model' => $prefix,
                        'models' => [$prefix],
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.ipam', 'Prefix was updated successfully'),
                'error' => Yii::t('hipanel.ipam', 'An error occurred when trying to update a prefix'),
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel.ipam', 'Prefix was deleted successfully'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.ipam', 'Description was changed'),
                'error' => Yii::t('hipanel.ipam', 'Failed to change description'),
            ],
            'get-tree-grid-rows' => [
                'class' => TreeGridRowsAction::class,
                'columns' => ['ip', 'state', 'vrf', 'role', 'utilization', 'site', 'text_note', 'checkbox'],
            ],
        ]);
    }
}
