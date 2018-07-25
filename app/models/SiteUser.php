<?php

namespace app\models;

use app\components\AppMsg;
use app\components\ActiveRecord;
use app\models\definitions\DefSiteUser;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_user".
 *
 * @property integer            $id
 * @property string             $email
 * @property string             $name
 * @property string             $avatar
 * @property string             $password
 * @property string             $balance
 * @property integer            $login_count
 * @property integer            $level_id
 * @property integer            $level_experience
 * @property integer            $total_experience
 * @property string             $language
 * @property string             $status
 * @property string             $created_at
 * @property string             $updated_at
 * @property integer            $fake_user
 *
 */
class SiteUser extends ActiveRecord
{
    const SCENARIO_ADMIN = 'admin';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['status'], 'required', 'on' => [self::SCENARIO_ADMIN]],
            [['balance'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['email'], 'string', 'max' => 100],
            [['name'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'                    => 'ID',
            'email'                 => AppMsg::t('Email'),
            'name'                  => AppMsg::t('Имя пользователя'),
            'password'              => AppMsg::t('Пароль'),
            'balance'               => AppMsg::t('Баланс'),
            'created_at'            => AppMsg::t('Создано'),
            'updated_at'            => AppMsg::t('Обновлено'),
            'login_count'           => AppMsg::t('Количество авторизаций'),
            'status'                => AppMsg::t('Статус'),
            'language'              => AppMsg::t('Язык'),
            'level_id'              => AppMsg::t('ID Уровня'),
            'level_experience'      => AppMsg::t('Опыта на уровне'),
            'total_experience'      => AppMsg::t('Всего опыта'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function historySettings() {
        return [
            'ignoredAttributes' => ['created_at', 'updated_at'],
        ];
    }
}
