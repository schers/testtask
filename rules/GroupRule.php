<?php

namespace app\rules;

use app\models\User;
use Yii;
use yii\rbac\Rule;

/**
 * User group rule class.
 */
class GroupRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'group';

    /**
     * @inheritdoc
     */
    public function execute($item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role_id;

            if ($item->name === 'superadmin') {
                return $role == User::ROLE_SUPERADMIN;
            } elseif ($item->name === 'admin') {
                return $role == User::ROLE_ADMIN || $role == User::ROLE_SUPERADMIN;
            } elseif ($item->name === 'user') {
                return $role == User::ROLE_USER || $role == User::ROLE_ADMIN || $role == User::ROLE_SUPERADMIN;
            }
        }
        return false;
    }
}
