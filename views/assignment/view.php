<?php
use yii\helpers\Html;

?>
<?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>

<hr>
<h3>View User</h3>
<?php
echo \yii\widgets\DetailView::widget([
    'model' => $user,
    'attributes' => $this->context->module->userAttributes,
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
    <?=Html::submitButton(Yii::t('rbac', 'Save'),['class' => 'btn btn-success']) ?>
    <?php echo $form->field($model,'rolesChildren')->widget(kak\widgets\itemselect\ItemSelect::className(),[
        'labelFrom' => 'Доступно',
        'labelTo' => 'Выбрано',
        'items' => Yii::$app->getAuthManager()->getRoles(),
        'itemAttributeId' => 'name',
        'templateItem' => $templateSelectItem
    ])->label('<h3>Roles</h3>');?>

    <?php echo  $form->field($model,'permissionsChildren')->widget(kak\widgets\itemselect\ItemSelect::className(),[
        'labelFrom' => 'Доступно',
        'labelTo' => 'Выбрано',
        'items' => Yii::$app->getAuthManager()->getPermissions(),
        'itemAttributeId' => 'name',
        'templateItem' => $templateSelectItem
    ])->label('<h3>Permissions</h3>');?>



    <?php \yii\widgets\ActiveForm::end()?>

<?php kak\widgets\panel\Panel::end();?>