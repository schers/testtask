<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\rules\GroupRule;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * Initial RBAC action
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Rules
        $groupRule = new GroupRule();

        $auth->add($groupRule);

        // Roles
        $user = $auth->createRole('user');
        $user->description = 'User';
        $user->ruleName = $groupRule->name;
        $auth->add($user);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $admin->ruleName = $groupRule->name;
        $auth->add($admin);
        $auth->addChild($admin, $user);

        $superadmin = $auth->createRole('superadmin');
        $superadmin->description = 'Superadmin';
        $superadmin->ruleName = $groupRule->name;
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
    }
}
