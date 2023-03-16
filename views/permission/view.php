<?php

use yii\helpers\Html;
use kak\widgets\panel\Panel;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kak\widgets\itemselect\ItemSelect;

?>

<?php Panel::begin(['heading' => false]) ?>
<?= Html::a(Yii::t('rbac', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>&nbsp;
<?= Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]) ?>
<hr>
<h3><?= Yii::t('rbac', 'View Permission') ?></h3>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description:ntext',
        'ruleName',
        'data:ntext',
    ],
]) ?>
<hr>
<?php
$templateSelectItem =
    '<div class="row">
        <div class="col-xs-4"> <b>{%=o.name%}</b> </div>
        <div class="col-xs-8">
           <div>{%=o.description%}</div>
        </div>
    </div>';
?>

<?php $form = ActiveForm::begin() ?>

<?= Html::submitButton(Yii::t('rbac', 'Save'), ['class' => 'btn btn-success']) ?>

<?= $form->field($model, 'permissionsChildren')->widget(ItemSelect::className(), [
    'labelFrom' => 'Доступно',
    'labelTo' => 'Выбрано',
    'items' => Yii::$app->getAuthManager()->getPermissions(),
    'itemAttributeId' => 'name',
    'templateItem' => $templateSelectItem
])->label(sprintf('<h3>%s</h3>', Yii::t('rbac', 'Permissions Children'))) ?>

<?php ActiveForm::end() ?>

<?php Panel::end() ?>

