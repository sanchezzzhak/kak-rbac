<?php
namespace kak\rbac\models;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\rbac\PhpManager;

/**
 * Class AuthItem
 * @package app\modules\rbac\models
 * @property $isNewRecord bool
 *
 * @property string $rolesChildren Roles children
 * @property string $permissionsChildren Permissions children
 * @property array $roles Roles array
 * @property array $rules Rules array
 * @property array $permissions Permissions array
 * @property mixed $item
 */
class AuthItem extends \yii\base\Model
{
    use traits\BaseItem;
    /**
     * @var string Role name
     */
    public $name;

    public $type;
    /**
     * @var string Rule name
     */
    public $ruleName;
    /**
     * @var string Role description
     */
    public $description;
    /**
     * @var string Role data
     */
    public $data;

    public $createdAt, $updatedAt;


    public $rolesChildren = [];
    public $permissionsChildren = [];

    /**
     * @var Item
     */
    private $_item;

    /**
     * Initialize object
     * @param Item  $item
     * @param array $config
     */
    public function __construct($item, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->type = $item->type;
            $this->description = $item->description;
            $this->ruleName = $item->ruleName;
            $this->createdAt  = $item->createdAt;
            $this->updatedAt = $item->updatedAt;
            $this->data = $item->data === null ? null : Json::encode($item->data);
            $this->loadChildren();
        }
        parent::__construct($config);
    }

    protected function loadChildren()
    {
        $children = array_keys(Yii::$app->authManager->getChildren($this->name));
        $roles = array_keys($this->roles);
        $permissions = array_keys($this->permissions);
        $this->rolesChildren = array_intersect($children, $roles);
        $this->permissionsChildren = array_intersect($children, $permissions);
    }


    /**
     * Get item
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * Find role
     * @param string $name
     * @return null|self
     */
    public static function findRole($name)
    {
        $item = Yii::$app->authManager->getRole($name);
        if ($item !== null) {
            return new self($item);
        }
        return null;
    }

    /**
     * Find findPermission
     * @param string $name
     * @return null|self
     */
    public static function findPermission($name)
    {
        $item = Yii::$app->authManager->getPermission($name);
        if ($item !== null) {
            return new self($item);
        }
        return null;
    }

    /**
     * Get type name
     * @param  mixed $type
     * @return string|array
     */
    public static function getTypeName($type = null)
    {
        $result = [
            Item::TYPE_PERMISSION => 'Permission',
            Item::TYPE_ROLE => 'Role'
        ];
        if ($type === null) {
            return $result;
        }
        return $result[$type];
    }

    /**
     * Search authitem
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        $this->scenario = self::SCENARIO_SEARCH;

        /* @var \yii\rbac\PhpManager $authManager */
        $authManager = Yii::$app->authManager;
        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } else {
            $items = [];
            if ($this->type == Item::TYPE_PERMISSION) {
                foreach ($authManager->getPermissions() as $name => $item) {
                    if ($name[0] !== '/') {
                        $items[$name] = $item;
                    }
                }
            } else {
                foreach ($authManager->getPermissions() as $name => $item) {
                    if ($name[0] === '/') {
                        $items[$name] = $item;
                    }
                }
            }
        }
        if ($this->load($params) && $this->validate() && (trim($this->name) !== '' || trim($this->description) !== '')) {
            $search = strtolower(trim($this->name));
            $desc = strtolower(trim($this->description));
            $items = array_filter($items, function ($item) use ($search, $desc) {
                return (empty($search) || strpos(strtolower($item->name), $search) !== false) && ( empty($desc) || strpos(strtolower($item->description), $desc) !== false);
            });
        }
        return new ArrayDataProvider([
            'allModels' => $items,
        ]);
    }





    /**
     * Save role to [[\yii\rbac\authManager]]
     * @return boolean
     */
    public function save()
    {
        $oldName = null;

        if ($this->validate()) {

            $manager = Yii::$app->authManager;
            if ($this->getIsNewRecord()) {
                if ($this->type == Item::TYPE_ROLE) {
                    $this->_item = $manager->createRole($this->name);
                }else{
                    $this->_item = $manager->createPermission($this->name);
                }
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }

            if($this->createdAt == null )
                $this->createdAt = time();

            if($this->updatedAt == null )
                $this->updatedAt = time();

            $this->_item->name        = $this->name;
            $this->_item->description = $this->description;
            $this->_item->ruleName    = $this->ruleName;
            $this->_item->data        = $this->data === null || $this->data === '' ? null : Json::decode($this->data);
            $this->_item->createdAt   = $this->createdAt;
            $this->_item->updatedAt   = $this->updatedAt;

            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }
            return true;
        }
        return false;

    }


    public function saveAssign($postData)
    {
        $rolesChildren = ArrayHelper::getValue($postData,'rolesChildren',[]);
        $permissionsChildren = ArrayHelper::getValue($postData,'permissionsChildren',[]);

        /** @var $authManager DbManager */
        $authManager = Yii::$app->authManager;


        foreach (array_diff($this->rolesChildren,$rolesChildren) as $item) {
            try {
                Yii::$app->authManager->removeChild($this->item, $authManager->getItem($item) );
            } catch (\Exception $e) {
                $this->addError('rolesChildren',$e->getMessage()) ;
            }
        }
        foreach (array_diff($rolesChildren,$this->rolesChildren) as $item) {
            try {
                Yii::$app->authManager->addChild($this->item,  $authManager->getItem($item));
            } catch (\Exception $e) {
                $this->addError('rolesChildren',$e->getMessage()) ;
            }
        }
        foreach (array_diff($this->permissionsChildren,$permissionsChildren) as $item) {
            try {
                Yii::$app->authManager->removeChild($this->item, $authManager->getItem($item) );
            } catch (\Exception $e) {
                $this->addError('permissionsChildren',$e->getMessage()) ;
            }
        }

        foreach (array_diff($permissionsChildren,$this->permissionsChildren) as $item) {
            try {
                Yii::$app->authManager->addChild($this->item,  $authManager->getItem($item));
            } catch (\Exception $e) {
                $this->addError('permissionsChildren',$e->getMessage()) ;
            }
        }
        $this->loadChildren();
        return !$this->hasErrors();
    }

    /**
     * Check if is new record.
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }


    const SCENARIO_SEARCH = 'search';
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = [
            'name' , 'description'
        ];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ruleName'],'validateRuleName'],
            [['name', 'type'], 'required' , 'on' => self::SCENARIO_DEFAULT ],
            ['name', 'string', 'max' => 64],
            ['name', 'match', 'pattern' => '/^[a-z0-9-_а-я]+/iu'],
            [['description', 'data'], 'string'],
            [['type'], 'integer'],
            [['description', 'data', 'ruleName'], 'default'],
            [['rolesChildren','permissionsChildren'],'safe'],
            [['name'], 'unique', 'when' => function() {
                return  $this->isNewRecord || ($this->_item->name != $this->name);
            }, 'on' => self::SCENARIO_DEFAULT],
        ];
    }


    public function validateRuleName($attribute, $params)
    {
        $rules = Yii::$app->authManager->getRules();
        $hasError = false;
        if (is_string($this->{$attribute})) {
            if (!array_key_exists($this->{$attribute}, $rules)) {
                $hasError = true;
            }
        } else {
            $hasError = true;
        }
        if ($hasError === true) {
            $this->addError($attribute, 'Rule not exists');
        }
        return $hasError;
    }

    public function unique()
    {
        $authManager = Yii::$app->authManager;
        $value = $this->name;
        if ($authManager->getRole($value) !== null || $authManager->getPermission($value) !== null) {
            $message = Yii::t('yii', '{attribute} "{value}" has already been taken.');
            $params = [
                'attribute' => $this->getAttributeLabel('name'),
                'value' => $value,
            ];
            $this->addError('name', Yii::$app->getI18n()->format($message, $params, Yii::$app->language));
        }
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('rbac', 'name'),
            'ruleName' => Yii::t('rbac', 'ruleName'),
            'description' => Yii::t('rbac', 'description'),
            'data' => Yii::t('rbac', 'data'),
            'Yii' => Yii::t('rbac', 'Yii')
        ];
    }




}