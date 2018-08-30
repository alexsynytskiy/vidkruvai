<?php

namespace app\modules\comment\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\Query;

/**
 * Class Comm
 * @package app\modules\comment\models
 */
class Comm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comm';
    }

    public static function find()
    {
        return new CommQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
        ];
    }

    public static function getCountComm()
    {
        return (new Query)
            ->from(static::tableName())
            ->count();
    }

    public static function getComm($offset = 0, $limit = 5)
    {
        return static::find()
            ->orderBy('tree DESC, lft')
            ->offset($offset)
            ->limit($limit)
            ->all();
    }
}
