<?php
namespace app\modules\rbac\models;


class Route extends \yii\base\Model
{

    /**
     * @var string Route value.
     */
    public $route;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return[
            [['route'],'safe'],
        ];
    }


    public function search($params)
    {

    }



}