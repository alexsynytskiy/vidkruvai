<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefStoreItem;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "sale".
 *
 * @property integer $id
 * @property integer $store_item_id
 * @property integer $team_id
 * @property integer $captain_id
 * @property integer $team_balance
 * @property string $created_at
 *
 * @property Team $team
 * @property SiteUser $captain
 * @property StoreItem $storeItem
 *
 */
class Sale extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_item_id', 'team_id', 'captain_id'], 'required'],
            [['store_item_id', 'team_id', 'captain_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => AppMsg::t('Створено'),
            'name' => AppMsg::t('Назва'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCaptain()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'captain_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStoreItem()
    {
        return $this->hasOne(StoreItem::className(), ['id' => 'store_item_id']);
    }
}
