<?php

namespace app\models\search;

use app\components\AppMsg;
use app\components\traits\SortTrait;
use app\models\definitions\DefLevel;
use app\models\Level;
use app\models\SiteUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * Class LevelSearch
 * @package app\models\search
 */
class LevelSearch extends Level
{
    use SortTrait;

    public $levelgroupName;
    /**
     * @var string
     */
    public $filterLevelType;
    /**
     * @var string
     */
    public $filterLevelCategory;
    /**
     * @var int
     */
    public $site_user_id;
    /**
     * @var int
     */
    public $user_level;
    /**
     * @var boolean
     */
    public $isAchieved;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'required_experience', 'base_level', 'num'], 'integer'],
            [[
                'levelgroupName', 'created_at', 'site_user_id', 'filterLevelType',
                'user_level', 'isAchieved', 'filterLevelCategory', 'archived',
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributeLabels(), [
            'filterLevelType' => AppMsg::t('Показать'),
            'filterLevelCategory' => AppMsg::t('Категория'),
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
        $this->load($params);

        $query = static::find()
            ->alias('l')
            ->addSelect('l.*')
            ->addSelect(new Expression('(CASE 
                WHEN l.num < :user_achieved THEN -1
                WHEN l.num = :user_achieved THEN 0
                WHEN l.num > :user_achieved THEN 1
                END) as isAchieved',
                [
                    'user_achieved' => $this->user_level,
                ]
            ));

        /** @var SiteUser $user */
        $user = SiteUser::findOne($this->site_user_id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andWhere(['l.archived' => parent::IS_NOT_ARCHIVED]);

        if ($user) {
            if ($this->filterLevelType === DefLevel::STATUS_ACHIEVED) {
                $query->andFilterWhere(['<=', 'num', $user->level_id]);
            } elseif ($this->filterLevelType === DefLevel::STATUS_AVAILABLE) {
                $query->andFilterWhere(['>', 'num', $user->level_id]);
            }
        }

        $query->orderBy('num');

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
            ->alias('level');

        $query->joinWith([
            'levelgroup' => function ($query) {
                /** @var \yii\db\ActiveQuery $query */

                $query->alias('lg');
            },
            'category',
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->addSortAttributes($dataProvider, [
            'levelgroupName' => 'lg.name',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'group_id' => $this->group_id,
            'num' => $this->num,
            'required_experience' => $this->required_experience,
            'base_level' => $this->base_level,
            'level.archived' => $this->archived,
        ]);

        $this->compareRangeDate($query, 'level.created_at', $this->created_at);

        $query->andFilterWhere(['like', 'lg.name', $this->levelgroupName])
            ->andFilterWhere(['like', 'category.name', $this->filterLevelCategory]);

        return $dataProvider;
    }
}
