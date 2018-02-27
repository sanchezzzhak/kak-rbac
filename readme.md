RBAC manager for Yii2
=====================
Dash Preview 
<img src="https://1.bp.blogspot.com/-rsTMp_RvCNg/WRrYWtma2_I/AAAAAAAAAJ4/HGXkSvPfCq8Eyb4OxfODmy9WtmP6BtpYACLcB/s1600/rbac-preview.png">



fork for
### install
##### step 1
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).
Either run
```
php composer.phar require --prefer-dist kak/rbac ">=1.0.1"
```
or add
```
"kak/rbac": ">=1.0.1"
```

##### step 2
add config web.php
```
    'authManager' => [
        'class' => 'kak\rbac\components\DbManager',
        'defaultRoles' => [
            'guest',
            'user'
        ],
    ],
```

##### step 3
create tables
```
yii migrate --migrationPath=@yii/rbac/migrations
```
Insert base rbac rules
```
yii migrate --migrationPath=@vendor/kak/rbac/migrations
```

#### step 4
Using module admin RBAC
```
$config['modules']['rbac'] = [
    'class' => 'kak\rbac\Module',
    // set custom Layout
    'mainLayout' => '@app/modules/dashboard/views/layouts/main.php',
    'layout' => 'main',
    'userAttributes' => [
        'username',
        'email'
    ]
    // desable check rbac - default true
    'checkAccessPermissionAdministrateRbac' => false
];
```

Controllers rules base 

Consts
```php

interface PermissionConst
{
    const
        ItemView   = 'ItemView',
        ItemUpdate = 'ItemUpdate',
        ItemCreate = 'ItemCreate',
        ItemDelete = 'ItemDelete',

        UpdateOwn  = 'UpdateOwn',
        DeleteOwn  = 'DeleteOwn',
        AuthorRule  = 'AuthorRule';
}


```


```php
public function behaviors()
{
    return [
        'access' => [
            'class' => yii\filters\AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'create'],
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN,User::ROLE_MANAGER],
                ],[
                    'actions' => ['update'],
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN, User::ROLE_MANAGER ],
                ],[
                    'actions' => ['delete'],
                    'allow' => true,
                    'roles' => [User::ROLE_ADMIN],
                ],[
                  'actions' => ['about'],
                  'allow' => true,
                  'roles' => ["?" , "@"],
                ]
            ],
        ],
    ];
}

```
using context access rule
```php
public function behaviors()
{
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'actions' => ['index', 'create'],
                    'allow' => true,
                    'roles' => ['@'],
                ],[
                    'class' => 'kak\rbac\rules\ContextAccessRule',
                    'modelClass' => 'app\models\Stream',
                    'actions' => ['update'],
                    'roles' => [PermissionConst::UpdateOwn],
                ],[
                    'class' => 'kak\rbac\rules\ContextAccessRule',
                    'modelClass' => 'app\models\Stream',
                    'actions' => ['delete'],
                    'roles' => [PermissionConst::DeleteOwn],
                ]
            ],
        ],

    ];
}
```
is current user personal check permission
```php
$isAccess = Yii::$app->user->can(PermissionConst::ItemCreate) 
            && Yii::$app->user->can(User::ROLE_ADMIN);
```