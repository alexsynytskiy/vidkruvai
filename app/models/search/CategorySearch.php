<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\Category;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class CategorySearch
 * @package app\models\search
 */
class CategorySearch extends Category
{
    use SortTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id', 'name', 'description', 'type', 'slug', 'status', 'archived', 'enabled_after'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributes(), [
        ]);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        $query = static::find()
            ->alias('t');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.name' => $this->name,
            't.description' => $this->description,
            't.type' => $this->type,
            't.slug' => $this->slug,
            't.status' => $this->status,
            't.archived' => $this->archived,
        ]);

        $this->compareRangeDate($query, 't.enabled_after', $this->enabled_after);

        return $dataProvider;
    }
}
