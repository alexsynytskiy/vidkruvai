<?php

namespace app\models\search;

use app\models\definitions\DefNotificationUser;
use app\models\NotificationUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class NotificationUserSearch
 * @package app\models\search
 */
class NotificationUserSearch extends NotificationUser
{
    /**
     * @var null
     */
    public $category = null;
    /**
     * @var null
     */
    public $userId = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'safe'],
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
        $query = NotificationUser::find();
        $query->joinWith([
            'notification AS notif',
        ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        $this->status = ArrayHelper::getValue($params, 'status');
        $this->category = ArrayHelper::getValue($params, 'category');

        if ($this->category === 'all') {
            $this->category = null;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'n_id' => $this->n_id,
            'user_id' => $this->userId,
            'notif.category' => $this->category,
        ]);

        if ($this->status === null) {
            $query->andFilterWhere([
                '!=', 'status', DefNotificationUser::STATUS_ARCHIVED,
            ]);
        } else {
            $query->andFilterWhere([
                'status' => $this->status,
            ]);
        }

        return $dataProvider;
    }
}
