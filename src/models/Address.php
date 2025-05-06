<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\ModelTrait;
use hipanel\modules\hosting\models\Link;
use hipanel\modules\ipam\models\query\AddressQuery;
use hipanel\modules\ipam\models\traits\IPBlockTrait;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\web\JsExpression;

/**
 * @property Link[] $links
 * @property string $device
 * @property int $device_id
 */
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

    public static function find(array $options = []): AddressQuery
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

        if ($this->hasAnyLinks()) {
            $link = $this->getFirstLink();

            if ($link) {
                $this->device = $link->device;
                $this->device_id = $link->device_id;
            }
        }
    }

    private function hasAnyLinks(): bool
    {
        return $this->isRelationPopulated('links') && !empty($this->links);
    }

    private function getFirstLink(): ?Link
    {
        $links = $this->links;
        $link = reset($links);

        return $link instanceof Link ? $link : null;
    }
}
