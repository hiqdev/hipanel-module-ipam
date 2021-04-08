<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\ModelTrait;
use hipanel\modules\hosting\models\Link;
use hipanel\modules\ipam\models\query\AddressQuery;
use hipanel\modules\ipam\models\traits\IPBlockTrait;
use Yii;
use yii\db\QueryInterface;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

class Address extends Prefix
{
    use IPBlockTrait, ModelTrait;

    private array $_links = [];

    public static function tableName()
    {
        return 'prefix';
    }

    /** {@inheritdoc} */
    public function rules()
    {
        $res = array_merge(parent::rules(), [
            'ip_validate' => [
                ['ip'], 'ip', 'subnet' => null,
                'when' => fn($model) => strpos($model->ip, '[') === false,
                'whenClient' => new JsExpression('(attribute, value) => value.indexOf("[") === -1'),
                'on' => ['create', 'update'],
            ],
        ]);

        return $res;
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'ip' => Yii::t('hipanel.ipam', 'Address'),
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

    public function getLinks()
    {
        return in_array($this->scenario, ['create', 'update'], true)
            ? ArrayHelper::toArray($this->getAddedLinks())
            : $this->hasMany(Link::class, ['ip_id' => 'id']);
    }

    public function setAddedLinks(array $links = []): void
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function getAddedLinks(): array
    {
        if ($this->isNewRecord && empty($this->_links)) {
            $this->addLink(new Link(['scenario' => 'create']));
        }
        if (empty($this->_links)) {
            $this->setAddedLinks($this->links);
        }

        return $this->_links ?? [];
    }

    public function addLink(Link $link): void
    {
        $this->_links[] = $link;
    }
}
