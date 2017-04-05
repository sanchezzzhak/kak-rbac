<?php

namespace kak\rbac;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'kak\rbac\controllers';

    public $userAttributes = [
        'username'

    ];


    public $defaultRoute = 'role/index';

    public $mainLayout = '@app/vendor/kak/rbac/views/layouts/main.php';


    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
