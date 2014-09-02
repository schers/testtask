<?php

namespace app\commands;

use app\models\User;
use Yii;
use yii\console\Controller;

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

        // Roles
        $user = $auth->createRole(User::ROLE_USER);
        $user->description = 'User';
        $auth->add($user);

        $admin = $auth->createRole(User::ROLE_ADMIN);
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->addChild($admin, $user);

        $superadmin = $auth->createRole(User::ROLE_SUPERADMIN);
        $superadmin->description = 'Superadmin';
        $auth->add($superadmin);
        $auth->addChild($superadmin, $admin);
    }
}
