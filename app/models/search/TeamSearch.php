<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\SiteUser;
use app\models\Team;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class TeamSearch
 * @package app\models\search
 */
class TeamSearch extends Team
{
    use SortTrait;

    public $captain;
    public $school_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [[
                'name', 'status', 'level_id', 'level_experience',
                'total_experience', 'level.num', 'captain', 'school_name'
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'captain' => \Yii::t('easyii', 'Капітан'),
            'school_name' => \Yii::t('easyii', 'Школа'),
            'status' => \Yii::t('easyii', 'Статус'),
        ]);
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
            ->alias('t');

        $query->joinWith([
            'level',
            'teamUsers',
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
            't.id' => $this->id,
            't.name' => $this->name,
            't.status' => $this->status,
        ]);

        $this->compareRangeDate($query, 't.created_at', $this->created_at);

        $query->andFilterWhere(['like', 'school_name', $this->school_name])
            ->andFilterWhere(['like', 'level.num', $this->level_id])
            ->andFilterWhere([
                'or',
                ['like', 'teamUsers.name', $this->captain],
                ['like', 'teamUsers.surname', $this->captain],
            ]);

        return $dataProvider;
    }
}
