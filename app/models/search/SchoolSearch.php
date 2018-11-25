<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\School;
use app\models\SiteUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class SchoolSearch
 * @package app\models\search
 */
class SchoolSearch extends School
{
    use SortTrait;

    public $usersCount;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'number'], 'integer'],
            [[
                'id', 'name', 'number', 'city.state.name', 'city.city', 'type.name', 'usersCount'
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
            ->alias('s');

        $this->usersCount = (new Query)
            ->from(self::tableName() . ' s')
            ->select('COUNT(su.id) count_users')
            ->innerJoin(SiteUser::tableName() . ' su', 'su.school_id = s.id')
            ->where(['s.id' => $this->id])
            ->groupBy('su.school_id')
            ->scalar();

        $query->joinWith([
            'type',
            'city',
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
            's.id' => $this->id,
            's.name' => $this->name,
            's.number' => $this->number,
        ]);

        $query->andFilterWhere(['like', 'city.state.name', $this->city->state->name])
            ->andFilterWhere(['like', 'city.city', $this->city->city])
            ->andFilterWhere(['like', 'type.name', $this->type->name])
            ->andFilterWhere(['like', 'usersCount', $this->usersCount]);

        return $dataProvider;
    }
}
