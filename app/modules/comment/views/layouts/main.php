<?php
/** @var string $content */

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?= yii\helpers\Html::encode($this->title) ?></title>
</head>
<body>
<?= $content; ?>
</body>
</html>