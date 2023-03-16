<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kak\widgets\itemselect\ItemSelect;
use kak\widgets\panel\Panel;
use yii\widgets\ActiveForm;

?>
<?php Panel::begin(['heading' => false]); ?>
    <h3><?=Yii::t('rbac', 'User')?></h3>
<?php
echo DetailView::widget([
    'model' => $user,
    'attributes' => $this->context->module->userAttributes,
]);
?>
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

<?php echo $form->field($model, 'rolesChildren')
    ->widget(ItemSelect::className(), [
        'labelFrom' => Yii::t('rbac', 'Available'),
        'labelTo' => Yii::t('rbac', 'Selected'),
        'items' => Yii::$app->getAuthManager()->getRoles(),
        'itemAttributeId' => 'name',
        'templateItem' => $templateSelectItem,
        'options' => [
            'class' => 'hide-control'
        ],
    ])->label(sprintf('<h3>%s</h3>', Yii::t('rbac', 'Roles'))) ?>
<div class="clearfix"></div>
<?php echo $form->field($model, 'permissionsChildren')
    ->widget(ItemSelect::className(), [
        'labelFrom' => Yii::t('rbac', 'Available'),
        'labelTo' => Yii::t('rbac', 'Selected'),
        'items' => Yii::$app->getAuthManager()->getPermissions(),
        'itemAttributeId' => 'name',
        'templateItem' => $templateSelectItem,
        'options' => [
             'class' => 'hide-control'
        ],
    ])->label(sprintf('<h3 class="">%s</h3>', Yii::t('rbac', 'Permissions'))) ?>


<?php ActiveForm::end() ?>

<?php Panel::end() ?>