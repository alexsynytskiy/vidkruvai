<?php

namespace app\assets;

/**
 * Class AppAsset
 * @package app\assets
 */
class AppAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $css = [
        'plugins/css/bootstrap.min.css',
        'plugins/css/style.css',
        'plugins/css/responsive.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'plugins/pnotify/pnotify.min.css',
        'plugins/sweetalert/sweetalert2.min.css',
        'plugins/css/jquery.bxslider.css',
        'css/custom-questions.css?version=40',
        'css/custom.css?version=40',
    ];

    public $js = [
        'plugins/js/jquery.min.js',
        'plugins/js/bootstrap.min.js',
        'plugins/js/smooth-scroll.min.js',
        'plugins/js/wow.min.js',
        'plugins/js/jquery.bxslider.js',
        'js/js-translations.js',
        'plugins/pnotify/pnotify.min.js',
        'plugins/sweetalert/sweetalert2.min.js',
        'plugins/js/jquery.knob.min.js',
        'js/ResizeSensor.js',
        'js/SiteCore.js?version=2',
        'js/ObserverList.js',
        'js/News.js?version=11',
        'js/Tasks.js',
        'js/Notifications.js',
        'js/RulesPage.js',
        'js/AnswerPage.js',
        'js/TestsPage.js',
        'js/ProfilePage.js',
        'js/RegisterPage.js',
        'js/Achievements.js',
        'js/AddSchoolPage.js',
        'js/WrittenPage.js',
        'js/StorePage.js',
        'js/ProgressPage.js',
        'js/RatingPage.js',
    ];

    //public $publishOptions = ['forceCopy' => true];
}