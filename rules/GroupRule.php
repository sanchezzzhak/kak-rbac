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
            return ($role === $item->name);
        }
        
        return false;
    }
}
