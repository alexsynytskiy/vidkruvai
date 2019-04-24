<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefTeam;
use app\models\definitions\DefTeamSiteUser;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $state_id
 * @property string $city
 * @property integer $latitude
 * @property integer $longitude
 *
 * @property State $state
 */
class City extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'city'], 'required'],
            [['city'], 'string'],
            [['state_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state_id' => AppMsg::t('Область ID'),
            'city' => AppMsg::t('Місто'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getState()
    {
        return $this->hasOne(State::className(), ['id' => 'state_id']);
    }

    /**
     * @param $stateId
     * @return array
     */
    public static function getList($stateId)
    {
        $names = [];

        /** @var City[] $cities */
        $cities = self::find()
            ->alias('c')
            ->innerJoin(State::tableName() . ' s', 's.id = c.state_id')
            ->where(['s.id' => $stateId])
            ->all();

        foreach ($cities as $city) {
            $names[$city->id] = 'м.' . $city->city;
        }

        return $names;
    }

    /**
     * @return Team[]
     */
    public function getActiveTeams()
    {
        return Team::find()
            ->alias('t')
            ->innerJoin(TeamSiteUser::tableName() . ' tsu', 'tsu.team_id = t.id')
            ->innerJoin(SiteUser::tableName() . ' su', 'tsu.site_user_id = su.id')
            ->innerJoin(School::tableName() . ' s', 'su.school_id = s.id')
            ->innerJoin(self::tableName() . ' c', 's.city_id = c.id')
            ->where([
                'c.id' => $this->id,
                'tsu.role' => DefTeamSiteUser::ROLE_CAPTAIN,
                't.status' => DefTeam::STATUS_ACTIVE
            ])
            ->all();
    }
}
