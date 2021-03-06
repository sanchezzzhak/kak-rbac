<?php
    /* @var $this \yii\web\View */
    /* @var $content string */
    $controller = $this->context;
    $route = $controller->route;
    $controllerId = $controller->id;
    $items = [

        ['label' => 'Assignments', 'url' => ['assignment/index'],    'active' => $controllerId == 'assignment' ],
        ['label' => 'Roles', 'url' => ['role/index'],    'active' => $controllerId == 'role' ],
        ['label' => 'Permissions', 'url' => ['permission/index'] , 'active' => $controllerId == 'permission'],
        ['label' => 'Rules', 'url' => ['rule/index'] , 'active' => $controllerId == 'rule'],
        // ['label' => 'Routes', 'url' => ['/rbac/route/index'] , 'active' => $controllerId == 'route'],
    ];

?>
<?php $this->beginContent($controller->module->mainLayout) ?>
<div class="row">
    <div class="col-lg-12">
        <?=\yii\bootstrap\Nav::widget([
            'options' => ['class' => 'nav nav-tabs'],
            'items' => $items
        ]);?>
        <?= $content ?>
    </div>
</div>
<?php $this->endContent(); ?>
