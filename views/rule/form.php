<?php
use yii\helpers\Html;

/** @var $role \app\modules\rbac\models\Role */
?>
<?php $form = \yii\widgets\ActiveForm::begin()?>

    <?php kak\widgets\panel\Panel::begin(['heading' => false ]);?>

        <?php
            echo Html::submitButton($model->isNewRecord ? Yii::t('rbac', 'Create') : Yii::t('rbac', 'Update'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

            if(!$model->isNewRecord){
                echo "&nbsp;" . Html::a(Yii::t('rbac', 'Delete'), ['delete', 'id' => $model->name], [
                    'class' => 'btn btn-danger',
                    'data-confirm' => Yii::t('rbac', 'Are you sure to delete this item?'),
                    'data-method' => 'post',
                ]);
            }

        ?>
        <hr>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 64]) ?>

        <?= $form->field($model, 'className')->textInput()?>
        <div>
            Package class list<br>
            \kak\rbac\rules\GroupRule<br>
            \kak\rbac\rules\UserRule<br>
            \kak\rbac\rules\AuthorRule<br>
            \kak\rbac\rules\ContextAccessRule<br>
        </div>



    <?php kak\widgets\panel\Panel::end();?>

<?php \yii\widgets\ActiveForm::end()?>
