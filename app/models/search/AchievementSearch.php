<?php

namespace app\models\search;

use app\components\AppMsg;
use app\components\traits\SortTrait;
use app\models\Achievement;
use app\models\definitions\DefAchievements;
use app\models\definitions\DefUserAchievement;
use app\models\UserAchievement;
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
    public $site_user_id;
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
            [['created_at', 'group_id', 'name', 'class_name', 'archived', 'site_user_id', 'filterAchievementType',
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

        $userAchievementTable = UserAchievement::tableName();

        $this->load($params);

        $query->alias('t');
        $query->leftJoin($userAchievementTable . ' as u', 'u.achievement_id = t.id and u.site_user_id = :userId', [
            ':userId' => $this->site_user_id,
        ]);

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
            $query->andWhere(['u.done' => DefUserAchievement::IS_DONE]);
        } elseif ($this->filterAchievementType === DefAchievements::STATUS_AVAILABLE) {
            $query->andWhere('u.site_user_id is null');
        } elseif ($this->filterAchievementType === DefAchievements::STATUS_IN_PROGRESS) {
            $query->andWhere(['u.done' => DefUserAchievement::IS_IN_PROGRESS]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'class_name', $this->class_name]);


        //print_r($query->createCommand()->getRawSql()); die;

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
