<?php

namespace hipanel\modules\ipam\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\RenderAction;
use hipanel\actions\SearchAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\ipam\actions\AddressDeleteAction;
use hipanel\modules\ipam\helpers\PrefixSort;
use hipanel\modules\ipam\models\Prefix;
use hipanel\modules\ipam\models\query\AddressQuery;
use yii\base\Event;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use Yii;

class AddressController extends CrudController
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
                'on beforePerform' => static function (Event $event): void {
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    /** @var AddressQuery $query */
                    $query = $action->getDataProvider()->query;
                    $query->withLinks();

                    if ($action->getSearchModel()->with_suggestions) {
                        $action->getDataProvider()->pagination = false;
                        $query->limit(-1);
                        $query->includeSuggestions();
                    }
                },
                'data' => static function (RenderAction $action): array {
                    /** @var IndexAction $indexAction */
                    $indexAction = $action->parent;
                    if ($indexAction->getSearchModel()->with_suggestions) {
                        $models = $indexAction->getDataProvider()->models;
                        PrefixSort::byCidr($models);
                        return [
                            'dataProvider' => new ArrayDataProvider([
                                'allModels' => $models,
                                'pagination' => false,
                            ])
                        ];
                    }

                    return [];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'on beforePerform' => static function (Event $event): void {
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withParent()->withLinks();
                },
                'data' => static function ($action) {
                    $parents = Prefix::find()->andWhere(['ip_cntd' => $action->getCollection()->first->ip])->withParent()->limit(-1)->all();
                    PrefixSort::byKinship($parents);
                    $parentDataProvider = new ArrayDataProvider([
                        'allModels' => $parents,
                        'pagination' => false,
                    ]);

                    return ['parentPrefixesDataProvider' => $parentDataProvider];
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.ipam', 'IP Address was created successfully'),
                'error' => Yii::t('hipanel.ipam', 'An error occurred when trying to add a prefix'),
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url'   => fn($action) => $action->collection->count() > 1 ?
                            $action->controller->getSearchUrl() :
                            $action->controller->getActionUrl('view', ['id' => $action->model->id]),
                    ],
                ],
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.ipam', 'IP Address was updated successfully'),
                'error' => Yii::t('hipanel.ipam', 'An error occurred when trying to update a prefix'),
                'on beforeFetch' => static function (Event $event): void {
                    /** @var SearchAction $action */
                    $action = $event->sender;
                    $dataProvider = $action->getDataProvider();
                    $dataProvider->query->withParent()->withLinks();
                },
            ],
            'delete' => [
                'class' => AddressDeleteAction::class,
                'success' => Yii::t('hipanel.ipam', 'IP Address was deleted successfully'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
                'validatedInputId' => false,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.ipam', 'Description was changed'),
                'error' => Yii::t('hipanel.ipam', 'Failed to change description'),
            ],
        ]);
    }
}
