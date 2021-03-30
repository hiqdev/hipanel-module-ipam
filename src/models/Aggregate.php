<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\modules\ipam\models\query\AggregateQuery;
use hipanel\modules\ipam\models\traits\IPBlockTrait;
use Yii;
use yii\db\QueryInterface;

class Aggregate extends Model
{
    use ModelTrait, IPBlockTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'client_id', 'seller_id', 'utilization'], 'integer'],
            [['note', 'rir', 'state', 'type', 'client', 'seller'], 'string'],
            [['ip'], 'ip', 'subnet' => true],

            [['ip', 'rir'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update']],
            [['id', 'note'], 'required', 'on' => ['set-note']],
            [['id'], 'required', 'on' => 'delete'],
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'ip' => Yii::t('hipanel.ipam', 'Aggregate'),
            'rir' => Yii::t('hipanel.ipam', 'RIR'),
            'note' => Yii::t('hipanel.ipam', 'Description'),
        ]);
    }

    /**
     * {@inheritdoc}
     * @return QueryInterface
     */
    public static function find(array $options = []): QueryInterface
    {
        return new AggregateQuery(get_called_class(), [
            'options' => $options,
        ]);
    }
}
