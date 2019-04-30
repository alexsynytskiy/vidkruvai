<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefStoreItem;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sale".
 *
 * @property integer $id
 * @property integer $store_item_id
 * @property integer $team_id
 * @property integer $captain_id
 * @property integer $city_id
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
            [['store_item_id', 'team_id', 'captain_id', 'city_id'], 'integer'],
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

    /**
     * @param SiteUser $user
     */
    public function processCityElements($user)
    {
        $city = $this->captain->school->city;
        $teamId = $user->team->id;

        $openedElements = StoreItem::find()
            ->alias('si')
            ->select('si.id')
            ->innerJoin(self::tableName() . ' s', 'si.id = s.store_item_id')
            ->where([
                's.city_id' => $city->id,
                's.team_id' => $teamId,
            ])
            ->all();

        $openedElementsIds = ArrayHelper::getColumn($openedElements, 'id');

        /** @var StoreItem[] $cityElementsToCheck */
        $cityElementsToCheck = StoreItem::find()
            ->where(['type' => DefStoreItem::TYPE_CITY])
            ->andWhere(['like', 'open_rule', $this->store_item_id])
            ->all();

        foreach ($cityElementsToCheck as $cityElement) {
            if(!in_array($cityElement->id, $openedElementsIds, true)) {
                $cityRule = array_map('intval', explode(',', $cityElement->open_rule));

                $allElementsPassed = true;
                foreach ($cityRule as $rule) {
                    if (!in_array((int)$rule, $openedElementsIds, true)) {
                        $allElementsPassed = false;
                    }
                }

                if ($allElementsPassed) {
                    $sell = new self;
                    $sell->store_item_id = $cityElement->id;
                    $sell->captain_id = $user->id;
                    $sell->team_id = $user->team->id;
                    $sell->city_id = $user->school->city_id;

                    if ($sell->validate()) {
                        $sell->save();
                    }
                }
            }
        }
    }

    /**
     * @param string $type
     * @param int $teamId
     *
     * @return array|ActiveRecord[]
     */
    public static function getSalesByType($type, $teamId)
    {
        return static::find()
            ->alias('s')
            ->innerJoin(StoreItem::tableName() . ' si', 's.store_item_id = si.id')
            ->where(['s.team_id' => $teamId, 'si.type' => $type])
            ->all();
    }
}
