<?php
use yii\helpers\Html;
?>

<?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>
<?=Html::a(Yii::t('rbac', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>&nbsp;
<?=Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]);?>
<hr>
<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'className',
    ],
]);
?>
<?php  kak\widgets\panel\Panel::end()?>
