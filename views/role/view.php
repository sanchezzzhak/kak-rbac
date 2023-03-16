<?php

use yii\helpers\Html;
use kak\widgets\panel\Panel;
use kak\widgets\itemselect\ItemSelect;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

?>

<?php Panel::begin(['heading' => false]) ?>
<?= Html::a(Yii::t('rbac', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
<?= Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]) ?>
<hr>
<h3>View Role</h3>
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

<?= Html::submitButton(Yii::t('rbac-admin', 'Save'), ['class' => 'btn btn-success']) ?>


<?= $form->field($model, 'rolesChildren')->widget(ItemSelect::className(), [
    'labelFrom' => Yii::t('rbac', 'Available'),
    'labelTo' => Yii::t('rbac', 'Selected'),
    'items' => Yii::$app->getAuthManager()->getRoles(),
    'itemAttributeId' => 'name',
    'templateItem' => $templateSelectItem
])->label('<h3>Role Children</h3>') ?>

<?= $form->field($model, 'permissionsChildren')->widget(ItemSelect::className(), [
    'labelFrom' => Yii::t('rbac', 'Available'),
    'labelTo' => Yii::t('rbac', 'Selected'),
    'items' => Yii::$app->getAuthManager()->getPermissions(),
    'itemAttributeId' => 'name',
    'templateItem' => $templateSelectItem
])->label('<h3>Permissions Children</h3>') ?>

<?php ActiveForm::end() ?>

<?php Panel::end() ?>
