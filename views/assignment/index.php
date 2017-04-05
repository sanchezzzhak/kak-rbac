<?php
/**
 * @var $this  \yii\web\View
 */
use yii\helpers\Html;

?>
<?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>

<?=Html::a('Create Role',['create'],['class' => 'btn btn-info']);?><hr>
<?php
\yii\widgets\Pjax::begin([
    'enablePushState'=>false,
]);
?>

<?=\kak\widgets\grid\GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $model,
    'columns' => \yii\helpers\ArrayHelper::merge($this->context->module->userAttributes,[[
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}',
        'buttonOptions' => ['class' => 'btn btn-small']
    ]])
])?>
<?php  \yii\widgets\Pjax::end();?>

<?php kak\widgets\panel\Panel::end();?>