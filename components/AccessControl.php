<?php

namespace kak\rbac\components;

use Yii;
use yii\web\User;
use yii\di\Instance;
use yii\base\Module;

use yii\web\ForbiddenHttpException;

class AccessControl extends \yii\filters\AccessControl
{

    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [];

    /**
     * @var array
     */
    public $params = [];

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param Action $action the action to be executed.
     * @return bool whether the action should continue to be executed.
     */
    public function beforeAction($action)
    {
        $controller = $action->controller;
        $params = ArrayHelper::getValue($this->params, $action->id, []);
        if (Yii::$app->user->can('/' . $action->getUniqueId(), $params)) {
            return true;
        }
        do {
            if (Yii::$app->user->can('/' . ltrim($controller->getUniqueId() . '/*', '/'))) {
                return true;
            }
            $controller = $controller->module;
        } while ($controller !== null);

        return parent::beforeAction($action);
    }


    /**
     * Returns a value indicating whether the filter is active for the given action.
     * @param Action $action the action being filtered
     * @return bool whether the filter is active for the given action.
     */
    protected function isActive($action)
    {
        if ($this->isErrorPage($action) || $this->isLoginPage($action) || $this->isAllowedAction($action)) {
            return false;
        }
        return parent::isActive($action);
    }

    /**
     * Returns a value indicating whether a current url equals `errorAction` property of the ErrorHandler component
     *
     * @param Action $action
     * @return bool
     */
    private function isErrorPage($action)
    {
        if ($action->getUniqueId() === Yii::$app->getErrorHandler()->errorAction) {
            return true;
        }
        return false;
    }


    /**
     * @param $action
     * @return bool
     */
    private function isAllowedAction($action)
    {
        if ($this->owner instanceof Module) {
            $ownerId = $this->owner->getUniqueId();
            $id = $action->getUniqueId();
            if (!empty($ownerId) && strpos($id, $ownerId . '/') === 0) {
                $id = substr($id, strlen($ownerId) + 1);
            }
        } else {
            $id = $action->id;
        }
        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, '*');
                if ($route === '' || strpos($id, $route) === 0) {
                    return true;
                }
            } else {
                if ($id === $route) {
                    return true;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }

        return false;
    }

    /**
     * Returns a value indicating whether a current url equals `loginUrl` property of the User component
     *
     * @param Action $action
     * @return bool
     */
    private function isLoginPage($action)
    {
        $loginUrl = trim(Url::to(Yii::$app->user->loginUrl), '/');
        if (Yii::$app->user->isGuest && $action->getUniqueId() === $loginUrl) {
            return true;
        }
        return false;
    }

}