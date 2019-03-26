<?php

namespace app\models\search;

use app\components\traits\SortTrait;
use app\models\definitions\DefWrittenTaskAnswers;
use app\models\WrittenTaskAnswer;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class WrittenTaskAnswerSearch
 * @package app\models\search
 */
class WrittenTaskAnswerSearch extends WrittenTaskAnswer
{
    use SortTrait;

    public $teamName;
    public $status;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['task_id', 'team_id', 'text', 'teamName', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'task_id' => \Yii::t('easyii', 'Завдання'),
            'teamName' => \Yii::t('easyii', 'Команда'),
            'text' => \Yii::t('easyii', 'Відповідь'),
            'status' => \Yii::t('easyii', 'Статус виконання'),
            'updated_at' => \Yii::t('easyii', 'Дата отримання відповіді'),
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
            'team',
            'task task',
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
            't.task_id' => $this->task_id,
        ]);

        $this->compareRangeDate($query, 't.created_at', $this->created_at);
        $this->compareRangeDate($query, 't.updated_at', $this->updated_at);

        if($this->status === DefWrittenTaskAnswers::STATUS_DONE) {
            $query->andFilterWhere([
                'or',
                ['is not', 't.text', new \yii\db\Expression('null')],
                ['is not', 't.text', ''],
            ]);
        }

        if($this->status === DefWrittenTaskAnswers::STATUS_NOT_DONE) {
            $query->andFilterWhere([
                'or',
                ['is', 't.text', new \yii\db\Expression('null')],
                ['is', 't.text', ''],
            ]);
        }

        $query->andFilterWhere(['like', 'team.name', $this->teamName])->distinct();

        $query->orderBy('team_id, task_id ASC');

        return $dataProvider;
    }
}
