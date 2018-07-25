<?php

namespace app\components;

use app\components\behaviors\TimestampBehavior;
use app\components\helpers\DateTimeHelper;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Class ActiveRecord
 * @package acp\components
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Const of archive status of the records
     */
    const IS_ARCHIVED = 'yes';
    const IS_NOT_ARCHIVED = 'no';
    /**
     * Const of publish status of the records
     */
    const IS_PUBLISH = 'yes';
    const IS_NOT_PUBLISH = 'no';
    /**
     * Const of publish status of the records
     */
    const IS_ACTIVE = 'yes';
    const IS_NOT_ACTIVE = 'no';

    /**
     * @return void
     */
    public function init()
    {
        parent::init();

        if ($this->hasProperty('archived') && ($this->archived === null)) {
            $this->archived = self::IS_NOT_ARCHIVED;
        }
    }

    /**
     * Archives OR unarchive OR delete the record
     *
     * @param bool $toArchive
     *
     * @return integer|false the number of rows deleted, or false if the deletion is unsuccessful for some reason.
     * Note that it is possible the number of rows deleted is 0, even though the deletion execution is successful.
     * @throws \Exception in case delete failed.
     *
     */
    public function delete($toArchive = true)
    {
        if ($toArchive) {
            if ($this->archived == self::IS_NOT_ARCHIVED) {
                $this->archived = self::IS_ARCHIVED;
            } else {
                $this->archived = self::IS_NOT_ARCHIVED;
            }

            return $this->update(false, ['archived']);
        }

        return parent::delete();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param ActiveQuery $query
     * @param             $dbField
     * @param             $value
     */
    public function compareRangeDate(ActiveQuery $query, $dbField, $value)
    {
        $parsedDate = DateTimeHelper::parseRangeDate($value);
        $dateFormat = '%Y-%m-%d';

        if ($parsedDate['firstTimeSeconds'] !== null) {
            $dateFormat = '%Y-%m-%d %H:%i:%s';
        } elseif ($parsedDate['firstTime'] !== null) {
            $dateFormat = '%Y-%m-%d %H:%i';
        }

        if ($parsedDate['firstTime'] === null) {
            $dbFieldFormat = 'DATE(' . $dbField . ')';
        } else {
            $dbFieldFormat = $dbField;
        }

        if ($parsedDate['firstDate'] !== null && $parsedDate['secondDate'] !== null) {
            $query->andWhere(new Expression(
                $dbFieldFormat . ' BETWEEN STR_TO_DATE(:firstDate, "' . $dateFormat . '") AND STR_TO_DATE(:secondDate, "' . $dateFormat . '")',
                [
                    ':firstDate' => $parsedDate['firstDate'],
                    ':secondDate' => $parsedDate['secondDate'],
                ]
            ));
        } elseif ($parsedDate['firstDate'] !== null) {
            $query->andWhere(new Expression($dbFieldFormat . ' = STR_TO_DATE(:firstDate, "' . $dateFormat . '")', [':firstDate' => $parsedDate['firstDate']]));
        }
    }

    /**
     * Adding new attributes to be sorted
     *
     * $attributes can be in the formats
     * [
     *      'modelAttributeName' => 'dbFieldName',
     *      'modelAttributeName' => [
     *          'dbFieldName',
     *
     *          //Yii config
     *          //@see \yii\data\Sort::$attributes
     *          'default' => 'default order'
     *          'label' => 'label'
     *      ],
     *      'dbFieldName'
     * ]
     *
     * @param ActiveDataProvider $dataProvider
     * @param array $attributes
     *
     * @return void
     */
    public function addSortAttributes(ActiveDataProvider $dataProvider, array $attributes)
    {
        foreach ($attributes as $key => $sortAttribute) {
            if (is_string($key) && $sortAttribute === null && $dataProvider->sort->hasAttribute($key)) {
                unset($dataProvider->sort->attributes[$key]);

                continue;
            }

            $modelAttribute = is_string($key) ? $key : $sortAttribute;

            $dataProvider->sort->attributes[$modelAttribute] = [
                'asc' => [$sortAttribute => SORT_ASC],
                'desc' => [$sortAttribute => SORT_DESC],
            ];
        }
    }

    /**
     * @param ActiveDataProvider $dataProvider
     * @param string $field
     * @param int $dir
     */
    public function setDefaultOrder(ActiveDataProvider $dataProvider, $field = 'id', $dir = SORT_DESC)
    {
        if (is_array($field)) {
            //@see yii\data\Sort::$defaultOrder
            $dataProvider->getSort()->defaultOrder = $field;
        } else {
            $dataProvider->getSort()->defaultOrder = [$field => $dir];
        }
    }

    /**
     * Adds sort by Weight, and if there is sort by grid attributes, sort is not applies
     *
     * @param ActiveDataProvider $dataProvider
     * @param ActiveQuery $query
     * @param string $field
     */
    public function sortByWeight(ActiveDataProvider $dataProvider, ActiveQuery $query, $field = 'weight')
    {
        $sortParam = $dataProvider->sort->sortParam;

        $isSort = isset($_GET[$sortParam]);

        if (!$isSort) {
            $query->orderBy($field);
        }
    }

    /**
     * @param $className
     */
    public function detachBehaviorByClassName($className)
    {
        foreach ($this->getBehaviors() as $behavior) {
            if ($behavior::className() === $className) {
                $behavior->detach();
            }
        }
    }

    /**
     * Settings that will be used by changelog
     *
     * Attributes that can be used
     *
     * -ignoredAttributes - array of attributes that won't be logged during changing
     * -normalizeAttributeValues [ -array of attributes which value must be normalized,
     *                              turn in human value, it should be in form 'attributeName' => function($oldValue,
     *                              $newValue) and must return an array of 2 elements first element is old human value,
     *                              second is new human value
     *     'attributeName1' => function($oldValue, $newValue) {
     *          //some actions with old and new values
     *          return [$oldValue, $newValue];
     *      },
     * ]
     * -relations [ -array of relations that uses by the main model
     *      'relationName1' => [
     *          'model' => 'acp\models\relationModelClass' - model of the relation
     *      ]
     * ]
     *
     * @return array
     */
    public static function historySettings()
    {
        return [];
    }
}
