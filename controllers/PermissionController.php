<?php
namespace kak\rbac\controllers;
use kak\rbac\models\AuthItem;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use Yii;


class PermissionController extends base\BaseController
{

    public function init()
    {
        parent::init();
        $this->view->params['breadcrumbs'][] = [
            'label' => Yii::t('rbac', 'Permissions'),
            'url' => ['index']
        ];
    }

    public function actionIndex()
    {
        $this->view->title = Yii::t('rbac', 'Permissions');

        $model = new AuthItem(null);
        $model->type = Item::TYPE_PERMISSION;
        $provider = $model->search(Yii::$app->request->get());

        return $this->render('index',compact('provider','model'));
    }


    public function actionCreate()
    {
        $this->view->title = Yii::t('rbac', 'Create new permission');

        $model = new AuthItem(null);
        $model->type = Item::TYPE_PERMISSION;
        if($this->savePermission($model)){
            return $this->redirect(['view' , 'id' => $model->name ]);
        }
        return $this->render('form',compact('model'));
    }

    /**
     * @param $model AuthItem
     * @return mixed
     */
    protected function savePermission($model)
    {
        return ($model->load(Yii::$app->request->post()) && $model->save() );
    }

    /**
     * @param $id
     * @return AuthItem|null
     * @throws NotFoundHttpException
     */
    protected function findPermissionModelById($id)
    {
        $item = AuthItem::findPermission($id);
        if(!$item)
            throw new NotFoundHttpException('The requested page does not exist.');

        return $item;
    }


    public function actionUpdate($id)
    {
        $this->view->title = Yii::t('rbac', 'Update permission');

        $model  = $this->findPermissionModelById($id);
        if($this->savePermission($model)){
            return $this->redirect(['view' , 'id' => $model->name ]);
        }
        return $this->render('form',compact('model'));
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findPermissionModelById($id);
        Yii::$app->getAuthManager()->remove($model->item);

        return $this->redirect(['index']);
    }

    public function actionView($id)
    {
        $this->view->title = Yii::t('rbac', 'View permission');
        
        $model = $this->findPermissionModelById($id);
        if(Yii::$app->request->isPost && $model->saveAssign(Yii::$app->request->post('AuthItem'))){
            return $this->redirect(['view','id' => $id]);
        }
        return $this->render('view',compact('model'));
    }

}