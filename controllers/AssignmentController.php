<?php
namespace app\modules\rbac\controllers;

use app\models\User;
use app\modules\rbac\models\UserAssignment;
use Yii;
use yii\web\Controller;
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
        $this->view->params['breadcrumbs'][] = ['label' => 'Assignment', 'url' => ['index'] ];

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
        $user = $this->findUserModel($id);
        $model = new UserAssignment(['id' => $id]);
        if(Yii::$app->request->isPost && $model->saveAssign(Yii::$app->request->post('UserAssignment'))){
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('view', compact('user','model'));
    }


    public function actionIndex()
    {
        /** @var User $class */
        $model = new $this->userClassName;
        $provider = $model->search(Yii::$app->request->get());
        return $this->render('index',compact('model','provider') );
    }


    protected function findUserModel($id)
    {
        /** @var User $class */
        $class = $this->userClassName;
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}