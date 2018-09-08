<?php

namespace app\components\helpers;

use app\components\AppMsg;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class LanguageHelper
 * @package app\components\helpers
 */
class LanguageHelper
{
    /**
     * Const allowed languages
     */
    const LANG_UK = 'uk';
    const LANG_EN = 'en';

    /**
     * @return array
     */
    public static function getLanguages()
    {
        return [
            self::LANG_UK => AppMsg::t('Українська'),
            self::LANG_EN => AppMsg::t('English'),
        ];
    }

    /**
     * @return array
     */
    public static function getShortLanguages()
    {
        return [
            self::LANG_UK => AppMsg::t('Ua'),
            self::LANG_EN => AppMsg::t('En'),
        ];
    }

    /**
     * Checks if field has a suffix "_en", "_ru", etc...
     *
     * @param string $name
     *
     * @return bool
     */
    public static function isMultilingualField($name)
    {
        $langSuffix = substr($name, -3);

        if ($langSuffix !== false) {
            $lang = substr($langSuffix, 1, 2);

            return array_keys(static::getLanguages(), $lang);
        }

        return false;
    }

    /**
     * Returns name and language based on field name,
     * in form of [fieldName, language]
     *
     * @param string $name
     *
     * @return array
     */
    public static function getMultilingualFieldInfo($name)
    {
        return [
            'name' => substr($name, 0, -3),
            'language' => substr($name, -2),
        ];
    }

    /**
     * @param ActiveRecord|Model $model
     * @param                    $fieldId
     *
     * @return string
     */
    public static function getMultilingualFieldLabel($model, $fieldId)
    {
        $field = static::getMultilingualFieldInfo($fieldId);

        return $model->getAttributeLabel($field['name']) . ' ' . strtoupper($field['language']);
    }

    /**
     * Adds language code to each of the localized attribute label
     *
     * @param array $attributeLabels
     * @param array $localizedAttributes
     *
     * @return array
     */
    public static function getLocalizedAttributeLabels(array $attributeLabels, array $localizedAttributes)
    {
        foreach (array_keys(static::getLanguages()) as $lang) {
            foreach ($localizedAttributes as $attribute) {
                if (isset($attributeLabels[$attribute])) {
                    $attributeLabels[$attribute . '_' . $lang] = $attributeLabels[$attribute] . ' ' . strtoupper($lang);
                }
            }
        }

        return $attributeLabels;
    }
}
