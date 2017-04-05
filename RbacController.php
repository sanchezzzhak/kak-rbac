<?php
namespace app\modules\rbac\commands;

use app\modules\rbac\rules\AuthorRule;
use app\modules\rbac\rules\GroupRule;
use app\modules\rbac\rules\UserRule;
use yii\console\Controller;
use Yii;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'init';
    /**
     * Initial RBAC action.
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // Rules
        $authorRule = new AuthorRule(null);
        $auth->add($authorRule);

        $userRule = new UserRule(null);
        $auth->add($userRule);

        $groupRule = new GroupRule(null);
        $auth->add($groupRule);



        // Permissions
        $administrateRbac = $auth->createPermission('administrateRbac');
        $administrateRbac->description = 'Can administrate all "RBAC" module';
        $auth->add($administrateRbac);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $auth->add($user);

        $guest = $auth->createRole('guest');
        $guest->description = 'Guest';
        $auth->add($guest);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $user);
        $auth->addChild($admin, $guest);

        $superadmin = $auth->createRole('superadmin');
        $superadmin->description = 'Super admin';
        $auth->add($superadmin);

        $auth->addChild($superadmin, $admin);
        $auth->addChild($superadmin, $administrateRbac);

        $auth->assign($superadmin, 1);
    }
}