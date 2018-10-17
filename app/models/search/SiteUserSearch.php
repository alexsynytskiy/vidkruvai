<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\SiteUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class SiteUserSearch
 * @package app\models\search
 */
class SiteUserSearch extends SiteUser
{
    use SortTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age', 'school_id'], 'integer'],
            [[
                'email', 'school_id', 'class', 'age',
                'name', 'surname', 'role', 'level_id', 'language', 'level_experience',
                'total_experience', 'school.name', 'level.num'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
            ->alias('u');

        $query->joinWith([
            'level',
            'school',
        ]);

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
            'id' => $this->id,
            'school_id' => $this->school_id,
            'class' => $this->class,
            'role' => $this->role,
        ]);

        $this->compareRangeDate($query, 'created_at', $this->created_at);

        $query->andFilterWhere(['like', 'school.name', $this->school_id])
            ->andFilterWhere(['like', 'level.num', $this->level_id])
            ->andFilterWhere([
                'or',
                ['like', 'u.surname', $this->name],
                ['like', 'u.name', $this->name],
            ]);

        return $dataProvider;
    }
}
