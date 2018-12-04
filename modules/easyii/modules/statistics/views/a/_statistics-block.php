<?php
/* @var $statistics array */
?>

    <div style="height: 10px"></div>
<?php if (count($statistics) > 0) : ?>
    <table class="table table-hover" style="width: 30%">
        <thead>
        <tr>
            <th><?= Yii::t('easyii', 'Одиниця групування') ?></th>
            <th><?= Yii::t('easyii', 'Кількість користувачів') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($statistics as $data) : ?>
            <tr>
                <td><?= $data['name'] ?></td>
                <td><?= $data['count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p><?= Yii::t('easyii', 'No records found') ?></p>
<?php endif; ?>