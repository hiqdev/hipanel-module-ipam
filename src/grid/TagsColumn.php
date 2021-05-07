<?php

namespace hipanel\modules\ipam\grid;

use hipanel\grid\DataColumn;
use hipanel\modules\hosting\widgets\ip\IpTag;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class TagsColumn extends DataColumn
{
    public $format = 'raw';

    public $attribute = 'tags';

    public function init()
    {
        parent::init();
        $this->label = Yii::t('hipanel.ipam', 'Tags');
        $this->filter = static fn($column, $model) => Html::activeDropDownList(
            $model,
            'tags',
            array_merge(['' => Yii::t('hipanel', '--')], $model->getTagOptions()),
            ['class' => 'form-control']
        );
    }

    public function getDataCellValue($model, $key, $index)
    {
        return self::renderTags($model);
    }

    public static function renderTags(Model $model): string
    {
        $labels = [];
        foreach ($model->tags as $tag) {
            $labels[] = IpTag::widget(['tag' => $tag]);
        }

        return implode(' ', $labels);
    }
}
