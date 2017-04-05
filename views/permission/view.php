<?php
use yii\helpers\Html;
?>

<?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>
<?=Html::a(Yii::t('rbac-admin', 'Update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>&nbsp;
<?=Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
    'class' => 'btn btn-danger',
    'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
    'data-method' => 'post',
]);?>
<hr>
<h3>View Permission</h3>

<?php
echo \yii\widgets\DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        'description:ntext',
        'ruleName',
        'data:ntext',
    ],
]);
?>
<hr>
<?php
$templateSelectItem =
    '<div class="row">
            <div class="col-xs-4">    <b>{%=o.name%}</b> </div>
            <div class="col-xs-8">
               <div>{%=o.description%}</div>
            </div>
        </div>';
?>

<?php $form = \yii\widgets\ActiveForm::begin()?>

<?=Html::submitButton(Yii::t('rbac-admin', 'Save'),['class' => 'btn btn-success']) ?>


<?=$form->field($model,'permissionsChildren')->widget(kak\widgets\itemselect\ItemSelect::className(),[
    'labelFrom' => 'Доступно',
    'labelTo' => 'Выбрано',
    'items' => Yii::$app->getAuthManager()->getPermissions(),
    'itemAttributeId' => 'name',
    'templateItem' => $templateSelectItem
])->label('<h3>Permissions Children</h3>');?>

<?php \yii\widgets\ActiveForm::end()?>

<?php  kak\widgets\panel\Panel::end()?>

