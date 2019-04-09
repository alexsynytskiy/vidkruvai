<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefStoreItem;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "store_items".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property integer $cost
 * @property string $icon
 *
 * @property Category $category
 *
 */
class StoreItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @param null $categoryId
     * @return array|ActiveRecord[]
     */
    public function getList($categoryId = null)
    {
        $params = ['status' => DefStoreItem::STATUS_ACTIVE];

        if ($categoryId) {
            $params['id'] = $categoryId;
        }

        return self::find()->where($params)->all();
    }

    public function isBought() {
        $user = \Yii::$app->siteUser->identity;

        return Sale::find()->where(['store_item_id' => $this->id, 'team_id' => $user->team->id])->exists();
    }
}
