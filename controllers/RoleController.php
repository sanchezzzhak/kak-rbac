<?php
namespace app\modules\rbac\controllers;
use app\modules\rbac\models\AuthItem;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;

class RoleController extends base\BaseController
{
    public function init()
    {
        parent::init();
        $this->view->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index'] ];
    }

    public function actionIndex()
    {
        $this->view->title = $this->view->params['pageHeaderText'] = 'Список ролей';

        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        $provider = $model->search(Yii::$app->request->get());
        return $this->render('index',compact('provider','model'));
    }


    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findRoleModelById($id);
        Yii::$app->getAuthManager()->remove($model->item);
        return $this->redirect(['index']);
    }


    public function actionCreate()
    {
        $this->view->title =  $this->view->params['pageHeaderText'] = 'Create Role';
        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        if($this->saveRole($model)){
            return $this->redirect(['view', 'id' => $model->name]);
        }
        return $this->render('form',compact('model'));
    }


    public function actionView($id)
    {
        $model = $this->findRoleModelById($id);
        if (Yii::$app->request->isPost && $model->saveAssign(Yii::$app->request->post('AuthItem')) ) {
            return $this->redirect(['view', 'id' => $model->name]);
        }
        return $this->render('view', compact('model'));
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findRoleModelById($id);
        if ($this->saveRole($model)) {
            return $this->redirect(['view', 'id' => $model->name]);
        }
        return $this->render('form', compact('model'));
    }

    /**
     * @param $id
     * @return AuthItem|null
     * @throws
     */
    public function findRoleModelById($id)
    {
        $item =  AuthItem::findRole($id);
        if(!$item)
            throw new NotFoundHttpException('The requested page does not exist.');

        return $item;
    }

    /**
     * @param $model AuthItem
     * @return bool
     */
    public function saveRole($model)
    {
        return ($model->load(Yii::$app->request->post()) && $model->save());

    }


}
