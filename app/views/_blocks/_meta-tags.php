<?php
/** \app\assets\AppAsset $asset */
?>

<!-- Meta Tags -->
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=yes">
<?= \yii\bootstrap\Html::csrfMetaTags() ?>
<meta content="IE=edge" http-equiv="X-UA-Compatible">

<!-- Page Title -->
<title><?= $this->title ?></title>

<!-- Favicon and Apple Touch Icon -->
<link rel="shortcut icon" href="<?= $asset->baseUrl ?>/img/favicon.png" type="image/x-icon">

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpVcxjCQWKJb952npbOD5hGSo8qyJ5UTE"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-127132032-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-127132032-1');
</script>
