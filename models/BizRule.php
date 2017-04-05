<?php
namespace kak\rbac\models;
use yii\base\Model;
use Yii;
use yii\data\ArrayDataProvider;
use yii\rbac\Item;
use yii\rbac\Rule;

/**
 * Class BizRule
 * @package app\modules\rbac\models
 * @property $item Rule
 */
class BizRule extends Model
{

    /**
     * @var string name of the rule
     */
    public $name;
    /**
     * @var integer UNIX timestamp representing the rule creation time
     */
    public $createdAt;
    /**
     * @var integer UNIX timestamp representing the rule updating time
     */
    public $updatedAt;
    /**
     * @var string Rule classname.
     */
    public $className;
    /**
     * @var Rule
     */
    private $_item;


    /**
     * Initilaize object
     * @param \yii\rbac\Rule $item
     * @param array $config
     */
    public function __construct($item, $config = [])
    {
        $this->_item = $item;
        if ($item !== null) {
            $this->name = $item->name;
            $this->className = get_class($item);
            $this->createdAt = $item->createdAt;
            $this->updatedAt = $item->createdAt;
        }
        parent::__construct($config);
    }


    const SCENARIO_SEARCH = 'search';
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SEARCH] = [ 'name' ];
        return $scenarios;
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','className'], 'required', 'on' => self::SCENARIO_DEFAULT ],
            [['className','name'], 'string'],
            [['className'], 'classExists', 'on' => self::SCENARIO_DEFAULT ]
        ];
    }
    /**
     * Validate class exists
     */
    public function classExists()
    {
        if (!class_exists($this->className) || !is_subclass_of($this->className, Rule::className())) {
            $this->addError('className', "Unknown Class: {$this->className}");
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('rbac-admin', 'Name'),
            'className' => Yii::t('rbac-admin', 'Class Name'),
        ];
    }
    /**
     * Check if new record.
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return $this->_item === null;
    }

    /**
     * Search BizRule
     * @param array $params
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        $this->scenario = self::SCENARIO_SEARCH;

        /* @var \yii\rbac\PhpManager $authManager */
        $authManager = Yii::$app->authManager;
        $models = [];
        $included = !($this->load($params) && $this->validate() && trim($this->name) !== '');
        foreach ($authManager->getRules() as $name => $item) {
            if (($included || stripos($item->name, $this->name) !== false)) {
                $models[$name] = new self($item);
            }
        }
        return new ArrayDataProvider([
            'allModels' => $models,
            'key' => function ($model) {
                return ['id' => $model->name];
            },
            'sort' => [
                'attributes' => ['name', 'createdAt', 'updatedAt'],
            ]
        ]);
    }



    /**
     * Find model by id
     * @param $id int
     * @return null|static
     */
    public static function findRule($id)
    {
        $item = Yii::$app->authManager->getRule($id);
        if ($item !== null) {
            return new static($item);
        }
        return null;
    }
    /**
     * Save model to authManager
     * @return boolean
     */
    public function save()
    {
        $oldName = null;
        if ($this->validate()) {
            $manager = Yii::$app->authManager;
            $class = $this->className;

            if ($this->getIsNewRecord()) {
                $this->_item = new $class();
                $isNew = true;
            } else {
                $isNew = false;
                $oldName = $this->_item->name;
            }
            $this->_item->name = $this->name;
            if ($isNew) {
                $manager->add($this->_item);
            } else {
                $manager->update($oldName, $this->_item);
            }

            return true;
        }



        return false;

    }
    /**
     * Get item
     * @return Item
     */
    public function getItem()
    {
        return $this->_item;
    }
}