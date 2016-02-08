<?php

namespace nagser\themes\controllers;

use nagser\themes\models\ThemesRecord;
use kartik\form\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class AdminController extends \nagser\base\controllers\AdminController {

    public function behaviors(){
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['themes-admin-index'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['themes-admin-update'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['themes-admin-delete'],
                    ],
                    [
                        'actions' => ['upload'],
                        'allow' => true,
                        'roles' => ['themes-admin-upload'],
                    ],
                    [
                        'actions' => ['copy'],
                        'allow' => true,
                        'roles' => ['themes-admin-copy'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Геттер для модели
     * @return string
     * */
    protected function getModel()
    {
        return ArrayHelper::getValue($this->module->modelMap, 'ThemesRecord');
    }

    public function actionIndex(){
        $model = \Yii::createObject($this->model);
        $dataProvider = new ArrayDataProvider([
            'key'=>'dir',
            'allModels' => $model->getThemeProvider(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCopy($id){
        $model = \Yii::createObject($this->model);
        /** @var ThemesRecord $model **/
        $model = $model->findRecordModel($id);
        if (\Yii::$app->request->isAjax) {
            if($model->load(\Yii::$app->request->post())){
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                return $this->renderAjax('copy', [
                    'model' => $model,
                ]);
            }
        } else {
            if($model->load(\Yii::$app->request->post())){
                $model->copy($id);
                $this->redirect(Url::to(['view', 'id' => $model->dir]));
            }
        }
    }

}