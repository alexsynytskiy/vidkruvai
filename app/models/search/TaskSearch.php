<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\Task;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class TaskSearch
 * @package app\models\search
 */
class TaskSearch extends Task
{
    use SortTrait;

    public $taskName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['id', 'hash', 'item_type', 'required', 'status', 'taskName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'status' => \Yii::t('easyii', 'Статус'),
            'taskName' => \Yii::t('easyii', 'Назва'),
            'item_type' => \Yii::t('easyii', 'Тип завдання'),
            'required' => \Yii::t('easyii', 'Обов\'язкове'),
            'starting_at' => \Yii::t('easyii', 'Доступне з'),
            'ending_at' => \Yii::t('easyii', 'Доступне до'),
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
            'taskObject',
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
            't.item_type' => $this->item_type,
            't.required' => $this->required,
            't.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'taskObject.name', $this->taskName])->distinct();

        $this->compareRangeDate($query, 't.starting_at', $this->starting_at);
        $this->compareRangeDate($query, 't.ending_at', $this->ending_at);

        return $dataProvider;
    }
}
