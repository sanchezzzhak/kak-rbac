<?php
namespace kak\rbac\models;
use app\modules\rbac\components\DbManager;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use Yii;
use yii\rbac\Role;

/**
 * Class UserAssignment
 * @package app\modules\rbac\models
 * @property $roles
 * @property $permissions
 * @property $rules
 */
class UserAssignment extends Model
{
    use traits\BaseItem;

    public $id;
    public $rolesChildren = [];
    public $permissionsChildren = [];

    /**
     * Initialize object
     * @param array $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->loadData();
    }

    protected function loadData()
    {
        $assignments = array_keys(Yii::$app->authManager->getAssignments($this->id));

        $roles = array_keys($this->roles);
        $permissions = array_keys($this->permissions);
        $rolesToUser = array_keys(Yii::$app->authManager->getRolesByUser($this->id));

        //$permissionsByUser = array_keys(Yii::$app->authManager->getPermissionsByUser($this->id));
        $permissionsByUser = array_intersect($permissions, $assignments);

        $this->rolesChildren = array_intersect($rolesToUser, $roles);
        $this->permissionsChildren = array_intersect($permissionsByUser, $permissions);
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','rolesChildren','permissionsChildren'],'safe'],
        ];
    }


    public function saveAssign($postData)
    {
        $rolesChildren = ArrayHelper::getValue($postData,'rolesChildren',[]);
        $permissionsChildren = ArrayHelper::getValue($postData,'permissionsChildren',[]);

        $this->saveRoleToUser($rolesChildren);
        $this->savePermissionsToUser($permissionsChildren);

        $this->loadData();
        return !$this->hasErrors();
    }

    /**
     * @param $rolesChildren
     */
    protected function saveRoleToUser($rolesChildren)
    {
        /** @var $item Role */
        /** @var $authManager DbManager */

        $authManager = Yii::$app->authManager;
        foreach (array_diff($this->rolesChildren,$rolesChildren) as $item) {
            try {
                $item = $authManager->getRole($item);
                Yii::$app->authManager->revoke($item, $this->id );
            } catch (\Exception $e) {
                $this->addError('rolesChildren',$e->getMessage()) ;
            }
        }
        foreach (array_diff($rolesChildren,$this->rolesChildren) as $item) {
            try {
                $item = $authManager->getRole($item);
                Yii::$app->authManager->assign($item, $this->id );
            } catch (\Exception $e) {
                $this->addError('rolesChildren',$e->getMessage()) ;
            }
        }
    }

    /**
     * @param $permissionsChildren
     */
    protected function savePermissionsToUser($permissionsChildren)
    {
        /** @var $item Role */
        /** @var $authManager DbManager */
        $authManager = Yii::$app->authManager;

        foreach (array_diff($this->permissionsChildren,$permissionsChildren) as $item) {
            try {
                $item = $authManager->getPermission($item);
                Yii::$app->authManager->revoke($item, $this->id );
            } catch (\Exception $e) {
                $this->addError('permissionsChildren',$e->getMessage()) ;
            }
        }
        foreach (array_diff($permissionsChildren,$this->permissionsChildren) as $item) {
            try {
                $item = $authManager->getPermission($item);

                Yii::$app->authManager->assign($item, $this->id );
            } catch (\Exception $e) {
                $this->addError('permissionsChildren',$e->getMessage()) ;
            }
        }
    }


}