<?php

namespace app\modules\themes\controllers;

use app\base\CustomAdminController;
use app\modules\themes\models\ThemesModel;
use kartik\form\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class AdminController extends CustomAdminController {

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

    public function actionIndex(){
        $themesModel = new ThemesModel();
        $dataProvider = new ArrayDataProvider([
            'key'=>'dir',
            'allModels' => $themesModel->getThemeProvider(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $themesModel
        ]);
    }

    public function actionCopy($id){
        $themesModel = (new ThemesModel())->findRecordModel($id);
        if (\Yii::$app->request->isAjax) {
            if($themesModel->load(\Yii::$app->request->post())){
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($themesModel);
            } else {
                return $this->renderAjax('copy', [
                    'model' => $themesModel,
                ]);
            }
        } else {
            if($themesModel->load(\Yii::$app->request->post())){
                $themesModel->copy($id);
                $this->redirect(Url::to(['view', 'id' => $themesModel->dir]));
            }
        }
    }

}