<?php
namespace kak\rbac\rules;

use yii\rbac\Rule;


class AuthorRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'AuthorRule';
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']->user_id == $user : false;
    }
}