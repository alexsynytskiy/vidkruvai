<?php
namespace yii\easyii\components\helpers;

use acp\components\AcpMsg;
use acp\components\ActiveRecord;
use yii\base\Model;

/**
 * Class LanguageHelper
 * @package yii\easyii\components\helpers
 */
class LanguageHelper
{
    /**
     * Const allowed languages
     */
    const LANG_UA  = 'uk';
    const LANG_EN  = 'en-US';
    const LANG_SK  = 'sk';
    const LANG_POL = 'pl';
    const LANG_HUN = 'hu';
    const LANG_RO  = 'ro';

    /**
 * @return array
 */
    public static function getLanguages() {
        return [
            self::LANG_UA  => 'Українська',
            self::LANG_EN  => 'English',
            self::LANG_SK  => 'Slovenský',
            self::LANG_POL => 'Polski',
            self::LANG_HUN => 'Magyar',
            self::LANG_RO  => 'Românesc'
        ];
    }

    /**
     * @return array
     */
    public static function getCountries() {
        return [
            self::LANG_UA  => \Yii::t('easyii', 'Ukraine'),
            self::LANG_SK  => \Yii::t('easyii', 'Slovakia'),
            self::LANG_POL => \Yii::t('easyii', 'Poland'),
            self::LANG_HUN => \Yii::t('easyii', 'Hungary'),
            self::LANG_RO  => \Yii::t('easyii', 'Romania'),
        ];
    }

    /**
     * @return array
     */
    public static function getShortLanguages() {
        return [
            self::LANG_UA => \Yii::t('Ru', [], null),
            self::LANG_EN => \Yii::t('En', [], null),
        ];
    }

    /**
     * Checks if field has a suffix "_en", "_ru", etc...
     *
     * @param string $name
     *
     * @return bool
     */
    public static function isMultilingualField($name) {
        $langSuffix = substr($name, -3);

        if($langSuffix !== false) {
            $lang = substr($langSuffix, 1, 2);

            return in_array($lang, array_keys(static::getLanguages()), true);
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
    public static function getMultilingualFieldInfo($name) {
        return [
            'name'     => substr($name, 0, -3),
            'language' => substr($name, -2),
        ];
    }

    /**
     * @param ActiveRecord|Model $model
     * @param                    $fieldId
     *
     * @return string
     */
    public static function getMultilingualFieldLabel($model, $fieldId) {
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
    public static function getLocalizedAttributeLabels(array $attributeLabels, array $localizedAttributes) {
        foreach(array_keys(static::getLanguages()) as $lang) {
            foreach($localizedAttributes as $attribute) {
                if(isset($attributeLabels[$attribute])) {
                    $attributeLabels[$attribute . '_' . $lang] = $attributeLabels[$attribute] . ' ' . strtoupper($lang);
                }
            }
        }

        return $attributeLabels;
    }
}