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
        'css/bootstrap.min.css',
        'style.css',
        'css/responsive.css',
        'plugins/font-awesome/css/font-awesome.min.css',
        'plugins/rs-plugin/css/extralayers.css',
        'plugins/rs-plugin/css/settings.css',
        'plugins/lightbox-master/dist/ekko-lightbox.css',
        'plugins/animate/animate.css',
        'plugins/isotope-portfolio/css/isotope.css',
        'plugins/isotope-portfolio/css/jquery.fancybox.css',
        'plugins/isotope-portfolio/css/jquery.fancybox.css',
        'plugins/pnotify/pnotify.min.css',
        'plugins/sweetalert/sweetalert2.min.css',
        'css/jquery.bxslider.css',
        'css/custom-questions.css?version=2',
        'css/custom.css?version=2',
    ];

    public $js = [
        'js/jquery.min.js',
        'js/bootstrap.min.js',
        'js/smooth-scroll.min.js',
        'js/wow.min.js',
        'js/jquery.inview.js',
        'plugins/rs-plugin/js/jquery.themepunch.tools.min.js',
        'plugins/rs-plugin/js/jquery.themepunch.revolution.min.js',
        'plugins/lightbox-master/dist/ekko-lightbox.js',
        'plugins/isotope-portfolio/js/isotope.min.js',
        'plugins/isotope-portfolio/js/isotope-main.js',
        'plugins/isotope-portfolio/js/jquery.fancybox.pack.js',
        'plugins/pnotify/pnotify.min.js',
        'js/jquery.bxslider.js',
        'plugins/sweetalert/sweetalert2.min.js',
        'js/js-translations.js',
        'js/SiteCore.js',
        'js/ObserverList.js',
        'js/News.js',
        'js/RulesPage.js',
        'js/AnswerPage.js',
        'js/ProfilePage.js',
        'js/RegisterPage.js',
        'js/main.js?version=2',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}