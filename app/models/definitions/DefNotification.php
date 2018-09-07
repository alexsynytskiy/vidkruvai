<?php
namespace acp\models\definitions;

use acp\components\AcpMsg;
use acp\components\BaseDefinition;

/**
 * Class DefNotification
 * @package acp\models\definitions
 */
class DefNotification extends BaseDefinition
{
    /**
     * Categories
     */
    const CATEGORY_PAYOUTS             = 'payouts';
    const CATEGORY_PAYOUTS_COLOR       = 'primary';
    const CATEGORY_ACCOUNT             = 'account';
    const CATEGORY_ACCOUNT_COLOR       = 'info';
    const CATEGORY_NEWS                = 'news';
    const CATEGORY_NEWS_COLOR          = 'orange';
    const CATEGORY_GAME_KEYS           = 'game-keys';
    const CATEGORY_GAME_KEYS_COLOR     = 'danger';
    const CATEGORY_DOMAIN_STATUS       = 'domain-status';
    const CATEGORY_DOMAIN_STATUS_COLOR = 'success';
    const CATEGORY_PAYMENT_PAYED       = 'payment-payed';
    const CATEGORY_PAYMENT_PAYED_COLOR = 'info';
    /**
     * Types
     */
    const TYPE_USER_REGISTRATION = 'user-registration';
    const TYPE_NEWS_ADDED        = 'news-added';
    const TYPE_HELLO_USER        = 'hello-user';
    const TYPE_GAME_KEYS_EMPTY   = 'game-keys-empty';
    const TYPE_PAYMENT_PAYED     = 'payment-payed';
    const TYPE_DOMAIN_STATUS     = 'domain-status';

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListCategories($returnType = 'key-value') {
        $categories = [
            self::CATEGORY_PAYOUTS => [
                'title' => AcpMsg::t('Выплаты'),
                'color' => self::CATEGORY_PAYOUTS_COLOR,
            ],
            self::CATEGORY_ACCOUNT => [
                'title' => AcpMsg::t('Аккаунт'),
                'color' => self::CATEGORY_ACCOUNT_COLOR,
            ],
            self::CATEGORY_NEWS    => [
                'title' => AcpMsg::t('Новости'),
                'color' => self::CATEGORY_NEWS_COLOR,
            ],
            self::CATEGORY_GAME_KEYS    => [
                'title' => AcpMsg::t('Ключи'),
                'color' => self::CATEGORY_GAME_KEYS_COLOR,
            ],
            self::CATEGORY_DOMAIN_STATUS    => [
                'title' => AcpMsg::t('Домены'),
                'color' => self::CATEGORY_DOMAIN_STATUS_COLOR,
            ],
            self::CATEGORY_PAYMENT_PAYED    => [
                'title' => AcpMsg::t('Выплаты'),
                'color' => self::CATEGORY_PAYMENT_PAYED_COLOR,
            ],

            /*self::CATEGORY_PAYOUTS => AcpMsg::t('Выплаты'),
            self::CATEGORY_ACCOUNT => AcpMsg::t('Аккаунт'),
            self::CATEGORY_NEWS    => AcpMsg::t('Новости'),*/
        ];

        return static::getListDataByReturnType($categories, $returnType);
    }

    /**
     * @param string $returnType
     *
     * @see BaseDefinition::getListDataByReturnType()
     *
     * @return array
     */
    public static function getListTypes($returnType = 'key-value') {
        $types = [
            self::TYPE_USER_REGISTRATION => AcpMsg::t('Регистрация пользователя'),
            self::TYPE_NEWS_ADDED        => AcpMsg::t('Добавлена новость'),
            self::TYPE_HELLO_USER        => AcpMsg::t('Приветсвие пользователю'),
            self::TYPE_GAME_KEYS_EMPTY   => AcpMsg::t('Заканчиваются ключи в игре'),
        ];

        return static::getListDataByReturnType($types, $returnType);
    }
}