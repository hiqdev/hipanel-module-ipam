<?php

namespace hipanel\modules\ipam\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\RedirectAction;
use hipanel\actions\SearchAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartDeleteAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\filters\EasyAccessControl;
use hipanel\modules\hosting\models\Link;
use hipanel\modules\ipam\helpers\PrefixSort;
use hipanel\modules\ipam\models\Prefix;
use hiqdev\hiart\Collection;
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
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_parent' => 1],
                'data' => static function ($action) {
                    $parents = Prefix::find()->andWhere(['ip_cntd' => $action->getCollection()->first->ip])->withParent()->limit(-1)->all();
                    PrefixSort::byKinship($parents);
                    $parentDataProvider = new ArrayDataProvider([
                        'allModels' => $parents,
                    ]);

                    return ['parentPrefixesDataProvider' => $parentDataProvider];
                },
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel.ipam', 'IP Address was created successfully'),
                'error' => Yii::t('hipanel.ipam', 'An error occurred when trying to add a prefix'),
                'collectionLoader' => function ($action) {
                    $this->collectionLoader($action->scenario, $action->collection);
                },
                'POST html' => [
                    'save' => true,
                    'success' => [
                        'class' => RedirectAction::class,
                        'url' => 'index',
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
                'collectionLoader' => function ($action) {
                    $this->collectionLoader($action->scenario, $action->collection);
                },
            ],
            'delete' => [
                'class' => SmartDeleteAction::class,
                'success' => Yii::t('hipanel.ipam', 'IP Address was deleted successfully'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
            'set-note' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel.ipam', 'Description was changed'),
                'error' => Yii::t('hipanel.ipam', 'Failed to change description'),
            ],
        ]);
    }

    public function collectionLoader($scenario, Collection $collection): void
    {
        $addressModel = $this->newModel(['scenario' => $scenario]);
        $linkModel = new Link(['scenario' => $scenario]);
        $addressModel->load($this->request->post());
        $ipLinks = $this->request->post($linkModel->formName(), []);
        $ipLinkModels = [];
        $ipLinkModels = array_pad($ipLinkModels, count($ipLinks), $linkModel);
        Link::loadMultiple($ipLinkModels, [$linkModel->formName() => $ipLinks]);
        foreach ($ipLinkModels as $link) {
            if ($link->ip_id === $addressModel->id && $link->validate()) {
                $addressModel->addLink($link);
            }
        }
        $collection->set($addressModel);
    }
}
