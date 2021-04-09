<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\ModelTrait;
use hipanel\modules\hosting\models\Link;
use hipanel\modules\ipam\models\query\AddressQuery;
use hipanel\modules\ipam\models\traits\IPBlockTrait;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class Address extends Prefix
{
    use IPBlockTrait, ModelTrait;

    public static function tableName()
    {
        return 'prefix';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['device'], 'string', 'on' => ['create', 'update']],
            [['device_id'], 'integer'],
            'ip_validate' => [
                ['ip'], 'ip', 'subnet' => null,
                'when' => fn($model) => strpos($model->ip, '[') === false,
                'whenClient' => new JsExpression('(attribute, value) => value.indexOf("[") === -1'),
                'on' => ['create', 'update'],
            ],
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ip' => Yii::t('hipanel.ipam', 'Address'),
            'device' => Yii::t('hipanel.ipam', 'Link to device'),
            'type' => Yii::t('hipanel.ipam', 'Status'),
        ]);
    }

    /**
     * {@inheritdoc}
     * @return QueryInterface
     */
    public static function find(array $options = []): QueryInterface
    {
        return new AddressQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    public function getLinks(): ActiveQuery
    {
        return $this->hasMany(Link::class, ['ip_id' => 'id']);
    }

    public function afterFind()
    {
        parent::afterFind();
        if ($this->isRelationPopulated('links')) {
            $link = reset($this->links);
            if ($link instanceof Link) {
                $this->device = $link->device;
                $this->device_id = $link->device_id;
            }
        }
    }
}
