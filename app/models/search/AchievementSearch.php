<?php

namespace app\models\search;

use app\components\AppMsg;
use app\components\traits\SortTrait;
use app\models\Achievement;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefEntityAchievement;
use app\models\EntityAchievement;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Class AchievementSearch
 * @package app\models\search
 */
class AchievementSearch extends Achievement
{
    use SortTrait;

    /**
     * @var string
     */
    public $filterAchievementType;
    /**
     * @var int
     */
    public $entity_id;
    /**
     * @var string
     */
    public $filterAchievementCategory;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'required_steps'], 'integer'],
            [['created_at', 'group_id', 'name', 'class_name', 'archived', 'entity_id', 'entity_type', 'filterAchievementType',
                'filterAchievementCategory'], 'safe'],
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
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributeLabels(), [
            'filterAchievementType' => AppMsg::t('Показать'),
            'filterAchievementCategory' => AppMsg::t('Категория'),
        ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function userSearch($params)
    {
        $query = static::find();

        $userAchievementTable = EntityAchievement::tableName();

        $this->load($params);

        $query->alias('t');
        $query->leftJoin($userAchievementTable . ' as u', 'u.achievement_id = t.id and u.entity_id = :userId and u.entity_type = :userType' , [
            ':userId' => $this->entity_id,
            ':userType' => $this->entity_type,
        ])
            ->andWhere(['t.entity_type' => $this->entity_type]);

        $query->orderBy('t.group_id DESC, t.required_steps ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            't.archived' => $this->archived,
        ]);

        if ($this->filterAchievementType === DefAchievements::STATUS_ACHIEVED) {
            $query->andWhere(['u.done' => DefEntityAchievement::IS_DONE]);
        } elseif ($this->filterAchievementType === DefAchievements::STATUS_AVAILABLE) {
            $query->andWhere('u.entity_id is null');
        } elseif ($this->filterAchievementType === DefAchievements::STATUS_IN_PROGRESS) {
            $query->andWhere(['u.done' => DefEntityAchievement::IS_IN_PROGRESS]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'class_name', $this->class_name]);

        return $dataProvider;
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
        $query = static::find()
            ->alias('t');

        $query->joinWith([
            'category',
        ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->addSortAttributes($dataProvider, [
            'filterAchievementCategory' => 'category.name',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            't.id' => $this->id,
            't.group_id' => $this->group_id,
            't.required_steps' => $this->required_steps,
            't.archived' => $this->archived,
        ]);

        $this->compareRangeDate($query, 't.created_at', $this->created_at);

        $query->andFilterWhere(['like', 't.name', $this->name])
            ->andFilterWhere(['like', 't.class_name', $this->class_name])
            ->andFilterWhere(['like', 'category.name', $this->filterAchievementCategory]);

        return $dataProvider;
    }
}
