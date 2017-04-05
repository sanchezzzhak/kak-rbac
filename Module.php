<?php

namespace kak\rbac;

/**
 * Class Module
 * @package kak\rbac
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'kak\rbac\controllers';

    /**
     * @var bool
     */
    public $checkAccessPermissionAdministrateRbac = false;

    /**
     * @var array
     */
    public $userAttributes = [
        'username'

    ];

    /**
     * @var string
     */
    public $defaultRoute = 'role/index';

    /**
     * @var string
     */
    public $mainLayout = '@app/vendor/kak/rbac/views/layouts/main.php';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
