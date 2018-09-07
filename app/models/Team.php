<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\easyii\components\ActiveRecord;

/**
 * Class TeamSiteUser
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $status
 * @property integer $level_id
 * @property integer $level_experience
 * @property integer $total_experience
 * @property string $created_at
 * @property string $updated_at
 *
 * @package app\models
 */
class Team extends ActiveRecord
{
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_UNCONFIRMED = 'UNCONFIRMED';
    const STATUS_BANNED = 'BANNED';
    const STATUS_DISABLED = 'DISABLED';

    public static function tableName()
    {
        return 'team';
    }

    public function rules()
    {
        return [
            [['level_id'], 'integer'],
            [['name', 'status'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва команди'),
            'avatar' => AppMsg::t('Зображення'),
            'created_at' => AppMsg::t('Створено'),
            'updated_at' => AppMsg::t('Оновлено'),
            'status' => AppMsg::t('Статус'),
            'level_id' => AppMsg::t('ID Рівня'),
            'level_experience' => AppMsg::t('Досвід на поточному рівні'),
            'total_experience' => AppMsg::t('Загальний досвід'),
        ];
    }
}
