<?php

namespace app\models;

use app\components\AppMsg;

/**
 * This is the model class for table "site_user".
 *
 * @property integer            $id
 * @property string             $email
 * @property string             $name
 * @property string             $avatar
 * @property string             $password
 * @property integer            $login_count
 * @property integer            $level_id
 * @property integer            $level_experience
 * @property integer            $total_experience
 * @property string             $language
 * @property string             $status
 * @property string             $created_at
 * @property string             $updated_at
 *
 */
class SiteUser extends \yii\db\ActiveRecord
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
}
