<?php

use yii\helpers\Html;
use kak\widgets\panel\Panel;
use yii\widgets\DetailView;

?>

<?php Panel::begin(['heading' => false]) ?>
<?= Html::a(Yii::t('rbac', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>&nbsp;
<?= Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]) ?>
<hr>
<?= etailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'className',
    ],
]) ?>
<?php Panel::end() ?>
