<?php

namespace app\modules\comment\assets;
use yii\web\View;

/**
 * Class CommentAsset
 * @package app\modules\comment\assets
 */
class CommentAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $_theme = null;
    /**
     * @var string
     */
    protected static $pathToImages;
    /**
     * @var View
     */
    protected static $_view = null;

    /**
     * @var null
     */
    protected static $_instance = null;

    /**
     * @return CommentAsset
     */
    public static function getInstance() {
        if(!static::$_instance) {
            static::$_instance = new static;
        }

        return static::$_instance;
    }

    public function getImagesPath() {
        return self::$pathToImages;
    }

    /**
     * @param \yii\web\AssetManager $assetManager
     */
    public function publish($assetManager) {
        $view = static::$_view;

        $landingsMedia = $assetManager->publish('@app/modules/comment/media',
            ['only' =>
                [
                    'img/*',
                    'js/Comment.js',
                    'css/style.css',
                ],
                'forceCopy' => true
            ]
        );

        self::$_theme = $landingsMedia[1];

        self::$pathToImages = $landingsMedia[1] . "/img";

        \Yii::$app->view->params['pathToImages'] = self::$pathToImages;

        $view->registerCssFile($landingsMedia[1] . "/css/style.css");

        $view->registerJsFile($landingsMedia[1] . '/js/Comment.js');
    }

    /**
     * @param View $view
     *
     * @return $this
     */
    public function setView(View $view) {
        static::$_view = $view;

        return $this;
    }

    /**
     * @return \yii\web\AssetBundle
     * @throws \yii\base\InvalidConfigException
     */
    public function registerAsset() {
        return static::$_view->registerAssetBundle(get_called_class());
    }

    /**
     * @param $view
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTheme($view) {
        static::getInstance()->setView($view)->registerAsset();

        return static::$_theme;
    }
}