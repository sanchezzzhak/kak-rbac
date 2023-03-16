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
    public $checkAccessPermissionAdministrateRbac = true;

    /**
     * @var array
     */
    public $userAttributes = [
        'username'
    ];

    public $language;

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
        $app = \Yii::$app;

        if (null !== $this->language) {
            $app->language = $this->language;
        }

        if (!isset($app->get('i18n')->translations['rbac*'])) {
            $app->get('i18n')->translations['rbac*'] = [
                'class'    => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }

        parent::init();

    }
}
