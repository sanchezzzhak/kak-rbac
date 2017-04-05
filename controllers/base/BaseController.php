<?php
namespace app\modules\rbac\controllers\base;
use yii\web\Controller;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;

class BaseController extends  Controller
{

    public function init(){
        parent::init();

        $this->view->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => [$this->module->defaultRoute] ];
        if (!Yii::$app->user->can('administrateRbac')) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


}