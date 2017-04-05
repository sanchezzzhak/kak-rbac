<?php
namespace app\modules\rbac\models\traits;
use Yii;

/**
 * Class BaseItem
 * @property $roles
 * @property $permissions
 * @property $rules
 *
 */
trait BaseItem
{
    private
        $_permissions,
        $_rules,
        $_roles;

    /**
     * @return null|\yii\rbac\Role[] Roles array
     */
    public function getRoles()
    {
        if ($this->_roles === null) {
            $this->_roles = Yii::$app->authManager->getRoles();
            if (isset($this->name) && $this->name !== null) {
                unset($this->_roles[$this->name]);
            }
        }
        return $this->_roles;
    }
    /**
     * @return null|\yii\rbac\Permission[] Permissions array
     */
    public function getPermissions()
    {
        if ($this->_permissions === null) {
            $this->_permissions = Yii::$app->authManager->getPermissions();
        }
        return $this->_permissions;
    }
    /**
     * @return null|\yii\rbac\Rule[] Rules array
     */
    public function getRules()
    {
        if ($this->_rules === null) {
            $this->_rules = Yii::$app->authManager->getRules();
        }
        return $this->_rules;
    }

}