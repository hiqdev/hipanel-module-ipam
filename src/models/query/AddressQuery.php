<?php

namespace hipanel\modules\ipam\models\query;

use hiqdev\hiart\ActiveQuery;

class AddressQuery extends ActiveQuery
{
    public function init(): void
    {
        parent::init();
        $this->ipOnly();
    }

    public function withLinks(): self
    {
        $this->joinWith('links');
        $this->andWhere(['with_links' => true]);

        return $this;
    }

    public function withParent(): self
    {
        $this->joinWith('parent');
        $this->andWhere(['with_parent' => true]);

        return $this;
    }

    public function ipOnly(): self
    {
        $this->andWhere(['is_ip' => true]);

        return $this;
    }

    public function includeSuggestions(): self
    {
        $this->andWhere(['include_suggestions' => true]);

        return $this;
    }

    public function firstbornOnly(): self
    {
        $this->andWhere(['firstborn' => true]);

        return $this;
    }
}
