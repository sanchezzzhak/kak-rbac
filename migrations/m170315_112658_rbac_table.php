<?php

use yii\db\Migration;
use yii\db\Schema;
use kak\rbac\rules\AuthorRule;
use kak\rbac\rules\UserRule;
use kak\rbac\rules\GroupRule;


class m170315_112658_rbac_table extends Migration
{

    public function up()
    {

        $this->execute("INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES (:role, :rule, 1491401134, 1491401134)",[
            ':role' => 'AuthorRule',
            ':rule' => 'O:25:"kak\rbac\rules\AuthorRule":3:{s:4:"name";s:10:"AuthorRule";s:9:"createdAt";i:1491401134;s:9:"updatedAt";i:1491402884;}',
        ]);

        $this->execute("INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES (:role, :rule, 1447060183, 1447060183)",[
            ':role' => 'GroupRule',
            ':rule' => 'O:24:"kak\rbac\rules\GroupRule":3:{s:4:"name";s:9:"GroupRule";s:9:"createdAt";i:1491401180;s:9:"updatedAt";i:1491401180;}',
        ]);

        $this->execute("INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES  (:role, :rule, 1447060183, 1447060183)",[
            ':role' => 'UserRule',
            ':rule' => 'O:23:"kak\rbac\rules\UserRule":3:{s:4:"name";s:8:"UserRule";s:9:"createdAt";i:1491401199;s:9:"updatedAt";i:1491401199;}',
        ]);

        $sqlInsertPart = "INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)";
        $this->execute("{$sqlInsertPart}  VALUES ('admin', 1, 'Admin', NULL, NULL, 1447060183, 1447062966)");
        $this->execute("{$sqlInsertPart}  VALUES ('manager', 1, 'Manager', NULL, NULL, 1447060183, 1447062966)");
        $this->execute("{$sqlInsertPart}  VALUES ('superadmin', 1, 'Super admin', NULL, NULL, 1447060183, 1447062966)");
        $this->execute("{$sqlInsertPart}  VALUES ('user', 1, 'User', NULL, NULL, 1447060183, 1447062966)");

        $this->execute("{$sqlInsertPart}  VALUES ('administrateRbac', 2, 'Can administrate all \"RBAC\" module', NULL, NULL, 1447060183, 1447060183)");

        $this->execute("{$sqlInsertPart}  VALUES ('DeleteOwn', 2, ' Delete model', 'AuthorRule', NULL, 1447060183, 1447060183)");
        $this->execute("{$sqlInsertPart}  VALUES ('UpdateOwn', 2, 'Update model', 'AuthorRule', NULL, 1447060183, 1447060183)");

        $this->execute("{$sqlInsertPart}  VALUES ('ItemCreate', 2, 'Create object', NULL, NULL, 1447166577, 1447166577)");
        $this->execute("{$sqlInsertPart}  VALUES ('ItemDelete', 2, 'Delete object', NULL, NULL, 1447166539, 1447166595)");
        $this->execute("{$sqlInsertPart}  VALUES ('ItemUpdate', 2, 'Update object', NULL, NULL, 1447166670, 1447166670)");
        $this->execute("{$sqlInsertPart}  VALUES ('ItemView', 2, 'View object', NULL, NULL, 1447166638, 1447166638)");

$sql = <<<SQL
            INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
            ('superadmin', 'admin'),
            ('superadmin', 'administrateRbac'),
            ('admin', 'ItemCreate'),
            ('admin', 'ItemDelete'),
            ('admin', 'ItemUpdate'),
            ('admin', 'ItemView'),
            ('manager', 'ItemView'),
            ('admin', 'user'),
            ('user', 'UpdateOwn'),
            ('user', 'DeleteOwn'),
            ('manager', 'user');
SQL;




        $this->execute($sql);

    }



}