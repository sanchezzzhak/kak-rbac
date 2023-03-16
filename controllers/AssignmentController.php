<?php
namespace kak\rbac\controllers;


use kak\rbac\models\UserAssignment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AssignmentController extends base\BaseController
{

    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'username';
    public $fullnmeField;

    public function init()
    {
        parent::init();
        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('rbac', 'Assignment'),
            'url' => ['index']
        ];

        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
        }

    }

    /**
     * Displays a single Assignment model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->view->title = Yii::t('rbac', 'View assignment');
        
        
        $user = $this->findUserModel($id);
        $model = new UserAssignment(['id' => $id]);
        if(Yii::$app->request->isPost && $model->saveAssign(Yii::$app->request->post('UserAssignment'))){
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('view', compact('user','model'));
    }


    public function actionIndex()
    {
        /** @var $model \app\models\User  */
        $model = new $this->userClassName;
        $model->load(Yii::$app->request->get());
        
        $this->view->title = Yii::t('rbac', 'Assignments');

        /** @var $query \yii\db\Query */
        $query = $model::find();
        foreach ($this->module->userAttributes as $attr){
            $query->andFilterCompare($attr,$model->getAttribute($attr),'LIKE');
        }

        $provider = new ActiveDataProvider([
           'query' => $query
        ]);

        return $this->render('index',compact('model','provider') );
    }


    protected function findUserModel($id)
    {
        /** @var \app\models\User $class */
        $class = $this->userClassName;
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}