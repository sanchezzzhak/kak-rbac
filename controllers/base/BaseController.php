<?php
namespace kak\rbac\controllers\base;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

/**
 * Class BaseController
 * @package kak\rbac\controllers\base
 * @property $module kak\rbac\Module
 */
class BaseController extends  Controller
{

    public function init(){
        parent::init();

        $this->view->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => [$this->module->defaultRoute] ];
        if ($this->module->checkAccessPermissionAdministrateRbac && !Yii::$app->user->can('administrateRbac')) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


}