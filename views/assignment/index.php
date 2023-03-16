<?php

use yii\web\View;
use yii\helpers\Html;
use kak\widgets\panel\Panel;
use yii\widgets\Pjax;
use kak\widgets\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * @var View $this
 */
?>
<?php Panel::begin(['heading' => false]) ?>

<?php Pjax::begin([
    'enablePushState' => false,
]) ?>

<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $model,
    'columns' => ArrayHelper::merge($this->context->module->userAttributes, [[
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        'buttonOptions' => ['class' => 'btn btn-small']
    ]])
]) ?>
<?php Pjax::end() ?>
<?php Panel::end() ?>