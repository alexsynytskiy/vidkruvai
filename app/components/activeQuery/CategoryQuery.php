<?php

namespace app\components\activeQuery;

use app\models\definitions\DefCategory;
use yii\helpers\ArrayHelper;

/**
 * Class CategoryQuery
 * @package app\components\activeQuery
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function achievement()
    {
        return $this->andWhere(['type' => DefCategory::TYPE_ACHIEVEMENT, 'status' => DefCategory::STATUS_ACTIVE,
            'archived' => 'no']);
    }

    /**
     * @return $this
     */
    public function achievementGroup()
    {
        return $this->andWhere(['type' => DefCategory::TYPE_ACHIEVEMENT_GROUP, 'status' => DefCategory::STATUS_ACTIVE,
            'archived' => 'no']);
    }

    /**
     * @return $this
     */
    public function level()
    {
        return $this->andWhere(['type' => DefCategory::TYPE_LEVEL, 'status' => DefCategory::STATUS_ACTIVE,
            'archived' => 'no']);
    }

    /**
     * @return $this
     */
    public function levelGroup()
    {
        return $this->andWhere(['type' => DefCategory::TYPE_LEVEL_GROUP, 'status' => DefCategory::STATUS_ACTIVE,
            'archived' => 'no']);
    }

    /**
     * @return array
     */
    public function getList()
    {
        return ArrayHelper::map($this->all(), 'id', 'name');
    }
}
