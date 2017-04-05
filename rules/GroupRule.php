<?php
namespace kak\rbac\rules;

use kak\rbac\interfaces\IGroupRole;
use Yii;
use yii\rbac\Rule;
/**
 * User group rule class.
 */



class GroupRule extends Rule implements IGroupRole
{
    /**
     * @inheritdoc
     */
    public $name = 'GroupRule';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {

            /** @var \app\models\User $user */
            $user = Yii::$app->user->identity;
            $role = $user->role;

            if ($item->name === self::ROLE_SUPER_ADMIN) {
                return $role === $item->name;

            } elseif ($item->name === self::ROLE_ADMIN) {
                return $role === $item->name || $role === self::ROLE_SUPER_ADMIN;

            }else if($item->name === self::ROLE_MANAGER){
                return $role === $item->name
                    || $role === self::ROLE_SUPER_ADMIN
                    || $role === self::ROLE_USER
                    || $role === self::ROLE_ADMIN;

            } elseif ($item->name === self::ROLE_USER) {
                return $role === $item->name
                    || $role === self::ROLE_SUPER_ADMIN
                    || $role === self::ROLE_ADMIN
                    || $role === self::ROLE_MANAGER;
            }
        }
        return false;
    }
}