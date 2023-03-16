<?php

use yii\helpers\Html;
use kak\widgets\panel\Panel;
use yii\widgets\Pjax;
use kak\widgets\grid\GridView;
?>

<?php Panel::begin(['heading' => false]) ?>
<?= Html::a(Yii::t('rbac', 'Create new permission'), ['create'], ['class' => 'btn btn-info']) ?>
    <hr>
<?php Pjax::begin([
    'enablePushState' => false,
]) ?>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $model,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn'
        ],
        'name',
        'description',
        'ruleName',
        [
            'class' => 'yii\grid\ActionColumn',
            'buttonOptions' => ['class' => 'btn btn-small']
        ],
    ],
]) ?>
<?php Pjax::end() ?>
<?php Panel::end() ?>