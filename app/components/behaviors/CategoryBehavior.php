<?php

namespace app\components\behaviors;

use app\models\CategoryEntity;
use app\models\Category;
use yii\base\Behavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class CategoryBehavior
 * @package app\components\behaviors
 */
class CategoryBehavior extends Behavior
{
    const TABLE_NAME = 'category_entity';
    /**
     * @var ActiveRecord
     */
    public $owner;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveCategories',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveCategories',
        ];
    }

    /**
     * List of the relations and settings to fetch categories
     * Format:
     *
     * [
     *  relationName => [
     *      entityAttribute => '',
     *      entityType => '',
     *      categoryInput => '', //The attribute where IDs of the categories will store
     *  ]
     * ]
     * @var array
     */
    public $settings = [];
    /**
     * @var \stdClass[]
     */
    protected $settingsInner = [];

    /**
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        foreach ($this->settings as $relation => $setting) {
            if (empty($setting['entityAttribute'])) {
                throw new \Exception("\"entityAttribute\" must be set for relation \"$relation\"");
            }

            if (empty($setting['entityType'])) {
                throw new \Exception("\"entityType\" must be set for relation \"$relation\"");
            }

            if (empty($setting['categoryInput'])) {
                throw new \Exception("\"categoryInput\" must be set for relation \"$relation\"");
            }

            $object = new \stdClass;
            $object->attribute = $setting['entityAttribute'];
            $object->type = $setting['entityType'];
            $object->input = $setting['categoryInput'];

            $this->settingsInner[$relation] = $object;
        }
    }

    /**
     * @param string $name
     * @param bool $checkVars
     *
     * @return bool
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (isset($this->settingsInner[$name])) {
            return true;
        }

        //User can get string representation of the list of categories
        //It should be called "$model->relationNameList"
        if ((strcmp(substr($name, -4), 'List') === 0)) {
            return true;
        }

        return parent::canGetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return \app\components\activeQuery\CategoryQuery|mixed|string|ActiveQuery
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if (strcmp(substr($name, -4), 'List') === 0) {
            return $this->getListCategories(substr($name, 0, -4));
        }

        if (!isset($this->settingsInner[$name])) {
            return parent::__get($name);
        }

        $object = $this->settingsInner[$name];

        $query = Category::find();
        $query->alias('t');
        $query->multiple = true;
        $query->innerJoin(CategoryEntity::tableName() . ' ce', 'ce.category_id = t.id');
        $query->andWhere(['ce.entity_id' => $this->owner->{$object->attribute}, 'entity_type' => $object->type]);

        return $query;
    }

    /**
     * @param $relation
     *
     * @return string
     */
    public function getListCategories($relation)
    {
        $list = $this->$relation->select(['id', 'name'])->asArray()->all();

        return implode(', ', ArrayHelper::getColumn($list, 'name', []));
    }

    /**
     * @throws \yii\db\Exception
     */
    public function saveCategories()
    {
        \Yii::$app->request->isPost;
        foreach ($this->settingsInner as $relation => $settings) {
            if ($this->owner->{$settings->input} === null) {
                continue;
            }

            $existedCategories = (array)$this->$relation->select(['id'])->asArray()->column() ?: [];
            $postCategories = ArrayHelper::getValue($this->owner, $settings->input, []);
            $postCategories = !empty($postCategories) ? $postCategories : [];

            $categoriesToAdd = array_diff($postCategories, $existedCategories);
            $categoriesToDelete = array_diff($existedCategories, $postCategories);

            if ($categoriesToAdd) {
                $batchRows = [];
                foreach ($categoriesToAdd as $id) {
                    $batchRows[] = [$id, $this->owner->primaryKey, $settings->type];
                }
                if ($batchRows) {
                    \Yii::$app->db->createCommand()
                        ->batchInsert(static::TABLE_NAME, ['category_id', 'entity_id', 'entity_type'], $batchRows)
                        ->execute();
                }
            }

            if ($categoriesToDelete) {
                foreach ($categoriesToDelete as $id) {
                    \Yii::$app->db->createCommand()
                        ->delete(static::TABLE_NAME, ['category_id' => $id,
                            'entity_id' => $this->owner->primaryKey, 'entity_type' => $settings->type])
                        ->execute();
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        $isGetter = strpos($name, 'get') === 0;

        if ($isGetter) {
            $relation = strtolower(substr($name, 3));
            if (isset($this->settingsInner[$relation])) {
                return true;
            }
        }

        return parent::hasMethod($name); // TODO: Change the autogenerated stub
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        $isGetter = strpos($name, 'get') === 0;

        if ($isGetter) {
            $relation = strtolower(substr($name, 3));
            if (isset($this->settingsInner[$relation])) {
                $activeQuery = $this->__get($relation);

                if ($activeQuery) {
                    return $activeQuery->all();
                }
            }
        }

        return parent::__call($name, $params);
    }

    /**
     * @param ActiveQuery $query
     * @param mixed $searchValue
     * @param string $relationName
     *
     * @return bool
     * @throws \Exception
     */
    public function filterByCategory(ActiveQuery $query, $searchValue, $relationName)
    {
        if (!isset($this->settingsInner[$relationName])) {
            throw new \Exception("The realtion \"{$relationName}\" is not found");
        }

        if (!$searchValue) {
            return false;
        }

        $entityType = $this->settingsInner[$relationName]->type;

        $query->innerJoin(self::TABLE_NAME . ' ce', 'ce.entity_id = t.id AND ce.entity_type = :entityType',
            [':entityType' => $entityType]);

        $query->andWhere(['ce.category_id' => $searchValue]);
    }
}
