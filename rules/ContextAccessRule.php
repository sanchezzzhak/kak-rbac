<?php
namespace kak\rbac\rules;

use app\helpers\PermissionConst;
use yii\base\InvalidConfigException;
use yii\filters\AccessRule;
use Yii;
use yii\web\NotFoundHttpException;

class ContextAccessRule extends AccessRule
{
    public $allow = true;  // Allow access if this rule matches
    public $roles = ['@']; // Ensure user is logged in.
    public $modelClass;
    public $primaryKey;
    public $cashing = true;

    protected function matchRole($user)
    {
        if (parent::matchRole($user))
            return true;

        $model = $this->findModel();
        foreach ($this->roles as $role) {

            # Call the CheckAccess() function which process rules
            if ($user->can($role, ['model' => $model], $this->cashing)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get model
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    protected function findModel()
    {
        if (!isset($this->modelClass))
            throw new InvalidConfigException(Yii::t('app', 'the "modelClass" must be set for "{class}".', ['class' => __CLASS__]));
        $primaryKey = $this->getPrimaryKey();

        # Get the request params
        $queryParams = \Yii::$app->getRequest()->getQueryParams();

        if(!isset($queryParams[join(',', $primaryKey)])){
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exists.'));
        }

        # Load the model
        $model = call_user_func([$this->modelClass, 'findOne'], $queryParams[join(',', $primaryKey)]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exists.'));
        }
    }

    /**
     * Get the primary key of the model
     * @return mixed
     */
    protected function getPrimaryKey()
    {
        if (!isset($this->primaryKey)) {
            return call_user_func([$this->modelClass, 'primaryKey']);
        } else {
            return $this->primaryKey;
        }
    }
}