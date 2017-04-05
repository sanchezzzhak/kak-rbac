<?php

namespace app\modules\rbac;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\rbac\controllers';

    public $userAttributes = [
        'username'

    ];


    public $defaultRoute = 'role/index';

    public $mainLayout = '@app/modules/rbac/views/layouts/main.php';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
