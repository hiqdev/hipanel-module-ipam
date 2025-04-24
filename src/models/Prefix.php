<?php

namespace hipanel\modules\ipam\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\models\Ref;
use hipanel\modules\ipam\models\query\PrefixQuery;
use hipanel\modules\ipam\models\traits\IPBlockTrait;
use Yii;
use yii\db\Query;
use yii\db\QueryInterface;

/**
 * @property string $ip
 * @property string $vrf
 * @property Prefix|null $parent
 * @property string $type
 * @property int $id
 * @property string $role
 * @property string $site
 */
class Prefix extends Model
{
    use ModelTrait, IPBlockTrait;

    public const TYPE_ACTIVE = 'active';
    public const TYPE_CONTAINER = 'container';

    /** {@inheritdoc} */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'parent_id', 'client_id', 'seller_id', 'utilization', 'aggregate_id', 'ip_count', 'child_count'], 'integer'],
            [['note', 'vrf', 'role', 'site', 'state', 'type', 'client', 'seller', 'vlan_group', 'vlan', 'aggregate'], 'string'],
            [['parent_ip'], 'ip'],
            [['tags'], 'safe'],

            [['ip'], 'unique', 'targetAttribute' => ['ip', 'vrf'],
                'filter' => function (Query $query): void {
                    $query->andWhere(['ne', 'id', $this->id]);
                },
                'message' => Yii::t('hipanel.ipam', 'The combination IP and VRF has already been taken.'),
                'on' => ['create', 'update']
            ],

            [['ip', 'vrf'], 'required', 'on' => ['create', 'update']],
            [['id', 'note'], 'required', 'on' => ['set-note']],
            [['type'], 'required', 'when' => fn() => self::class === static::class, 'on' => ['create', 'update']],
            'ip_validate' => [['ip'], 'ip', 'subnet' => true, 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => 'delete'],
        ]);
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'ip' => Yii::t('hipanel.ipam', 'Prefix'),
            'type' => Yii::t('hipanel.ipam', 'Status'),
            'vrf' => Yii::t('hipanel.ipam', 'VRF'),
            'site' => Yii::t('hipanel.ipam', 'Site'),
            'note' => Yii::t('hipanel.ipam', 'Description'),
            'vlan' => Yii::t('hipanel.ipam', 'VLAN'),
            'vlan_group' => Yii::t('hipanel.ipam', 'VLAN group'),
        ]);
    }

    /**
     * {@inheritdoc}
     * @return PrefixQuery
     */
    public static function find(array $options = []): QueryInterface
    {
        return new PrefixQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    public function getParent(): QueryInterface
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    public function isSuggested(): bool
    {
        return !$this->state && !$this->client;
    }

    public function getTagOptions(): array
    {
        return Ref::getList('tag,ip', 'hipanel.ipam');
    }
}
