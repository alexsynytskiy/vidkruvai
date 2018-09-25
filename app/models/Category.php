<?php

namespace app\models;

use app\components\activeQuery\CategoryQuery;
use app\components\AppMsg;
use app\models\definitions\DefCategory;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $slug
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $created_at
 * @property string $status
 * @property string $archived
 */
class Category extends ActiveRecord
{
    /**
     * @var int
     */
    public $parentNodeId;

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => true,
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'status'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1000],
            [['type'], 'in', 'range' => DefCategory::getListTypes('keys')],
            [['parentNodeId'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Название'),
            'description' => AppMsg::t('Описание'),
            'type' => AppMsg::t('Тип'),
            'slug' => AppMsg::t('Slug'),
            'created_at' => AppMsg::t('Created At'),
            'status' => AppMsg::t('Статус'),
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'parentNodeId' => AppMsg::t('Родительская категория'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (empty($this->type)) {
                $this->type = null;
            }

            return true;
        }

        return false;
    }

    /**
     * @param array $except
     * @param array $params
     *
     * @return array
     */
    public static function listItems($except = [], $params = [])
    {
        $items = [];
        $genres = self::find()->where($params)->orderBy('tree, lft')->asArray()->all();

        foreach ($genres as $genre) {
            if (in_array($genre['id'], $except, false)) {
                continue;
            }
            $items[$genre['id']] = str_repeat('—', $genre['depth']) . Html::encode($genre['name']);
        }

        return $items;
    }
}
