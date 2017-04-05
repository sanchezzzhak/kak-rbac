<?php
use yii\helpers\Html;

/** @var $role \app\modules\rbac\models\Role */
?>
<?php $form = \yii\widgets\ActiveForm::begin()?>

    <?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>

        <?php
            echo Html::submitButton($model->isNewRecord ? Yii::t('rbac-module', 'Create') : Yii::t('rbac-module', 'Update'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

            if(!$model->isNewRecord){
                echo " " . Html::a(Yii::t('rbac-admin', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('rbac-admin', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
            }
        ?>
        <hr>
        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

        <?= $form->field($model, 'ruleName')->widget('kak\widgets\select2\Select2',[
            'items' => \yii\helpers\ArrayHelper::map(Yii::$app->authManager->getRules(),'name','name'),
            'firstItemEmpty' => true,
            'clientOptions' => [
                'allowClear' => true
            ]
        ]) ?>

        <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

        <div class="form-group">

        </div>

    <?php kak\widgets\panel\Panel::end();?>

<?php \yii\widgets\ActiveForm::end()?>
