<?php
namespace kak\rbac\controllers;
use kak\rbac\models\BizRule;
use yii\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;

class RuleController extends base\BaseController
{

    public function init()
    {
        parent::init();
        $this->view->params['breadcrumbs'][] = ['label' => 'Rules', 'url' => ['index'] ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new BizRule(null);
        $provider = $model->search(Yii::$app->request->get());

        return $this->render('index',compact('model','provider'));
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BizRule(null);
        if($this->saveRule($model)){
            return $this->redirect(['view' , 'id' => $model->name ]);
        }
        return $this->render('form',compact('model'));
    }

    /**
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findRuleModelById($id);
        if ($this->saveRule($model)) {
            return $this->redirect(['view', 'id' => $model->name]);
        }
        return $this->render('form', compact('model'));
    }

    public function actionView($id)
    {
        $model = $this->findRuleModelById($id);
        return $this->render('view', compact('model'));
    }


    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findRuleModelById($id);
        Yii::$app->authManager->remove($model->item);
        return $this->redirect(['index']);
    }

    /**
     * @param $model
     * @return bool
     */
    protected function saveRule($model)
    {
        return ($model->load(Yii::$app->request->post()) && $model->save());
    }

    /**
     * @param $id
     * @return null|BizRule
     * @throws NotFoundHttpException
     */
    protected function findRuleModelById($id)
    {
        $item = BizRule::findRule($id);
        if (!$item) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $item;
    }

}