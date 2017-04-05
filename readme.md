RBAC manager for Yii2
=====================
fork for
### install
##### step 1
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).
Either run
```
php composer.phar require --prefer-dist kak/rbac "dev-master"
```
or add
```
"kak/rbac": "dev-master"
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
insert base rbac rules
```
yii migrate --migrationPath=@vendor/kak/rbac/migrations
```

##### step 4
modify app\models\User add search methods

```php

const SCENARIO_SOCIAL = 'social';
const SCENARIO_REGISTER = 'register';
const SCENARIO_CHANGE_USERNAME = 'change username';
const SCENARIO_REGISTER = 'search';

/**
 * @return array
 */
public function scenarios()
{
    $scenarios = ArrayHelper::merge(parent::scenarios(),[
        self::SCENARIO_SOCIAL   => [],
        self::SCENARIO_REGISTER => ['username', 'email', 'password'],
        self::SCENARIO_CHANGE_USERNAME => ['username', 'password'],
        self::SCENARIO_SEARCH => ['id','username', 'email', 'status'],
    ]);
    return $scenarios;
}


/**
 * @inheritdoc
 */
public function rules()
{
    return [
        [['username', 'password_hash'], 'required' , 'on' => self::SCENARIO_REGISTER ],
        [['status', 'email_verify'], 'integer'],
        [['username'], 'string', 'max' => 30],
        [['email'],'email'],
        [['email','username'],'unique'],
        [['auth_key', 'password_hash', 'password_reset_token', 'email','email_code'], 'string', 'max' => 255],
    ];
}

/***
 * Search method the gridView
 * @param $params
 * @param $group
 * @return \yii\data\ActiveDataProvider
 */
public function search($params, $group = null )
{
    $this->scenario = self::SCENARIO_SEARCH;
    $query = self::find();
    $dataProvider = new \yii\data\ActiveDataProvider([
        'query' => $query,
    ]);

    if (!( $this->load($params) && $this->validate())) {
        return $dataProvider;
    }

    $query->andFilterWhere([
        'id'        => $this->id,
        'username'  => $this->username,
        'email'     => $this->email
    ]);

    return $dataProvider;
}
```

#### step 5
use module admin RBAC
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
