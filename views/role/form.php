<?php

use yii\helpers\Html;
use kak\widgets\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kak\widgets\panel\Panel;

/** @var $role \app\modules\rbac\models\Role */
?>
<?php $form = ActiveForm::begin() ?>

<?php Panel::begin(['heading' => false]); ?>

<?= Html::submitButton($model->isNewRecord
    ? Yii::t('rbac', 'Create')
    : Yii::t('rbac', 'Update'), [
    'class' => $model->isNewRecord
        ? 'btn btn-success'
        : 'btn btn-primary'
]) ?>

<?= !$model->isNewRecord ? Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]) : '' ?>
<hr>

<?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>
<?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
<?= $form->field($model, 'ruleName')->widget(Select2::class, [
    'items' => ArrayHelper::map(Yii::$app->authManager->getRules(), 'name', 'name'),
    'firstItemEmpty' => true,
    'clientOptions' => [
        'allowClear' => true
    ]
]) ?>

<?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

<?php Panel::end(); ?>
<?php ActiveForm::end() ?>
