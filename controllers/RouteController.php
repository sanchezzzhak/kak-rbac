<?php
namespace kak\rbac\controllers;

use yii\base\Model;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class RouteController extends base\BaseController
{
    public function init()
    {
        parent::init();
        $this->view->params['breadcrumbs'][] = ['label' => 'RBAC', 'url' => [$this->module->defaultRoute] ];
        $this->view->params['breadcrumbs'][] = ['label' => 'Route', 'url' => ['index'] ];
    }

    public function actionIndex()
    {
        $model = new Model();
        //$provider = $model->search(Yii::$app->request->get());

        return $this->render('index',compact('model','provider'));
    }

   /* public function actionCreate(){

        $model = new Model();
        if($this->saveModel($model)){
            return $this->redirect(['view','id' => $model->id]);
        }

        return $this->render('form',compact('model'));
    }
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($this->saveModel($model)){
            return $this->redirect(['view','id' => $model->id]);
        }
        return $this->render('form',compact('model'));
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view',compact('model'));
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        return $this->redirect(['index']);
    }

    protected function saveModel($model)
    {
        return ($model->load(Yii::$app->request->post() && $model->save() ));
    }
    protected function findModel($id)
    {

    }
*/

}