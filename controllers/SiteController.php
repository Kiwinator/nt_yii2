<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Status;
use app\models\LocCountry;
use app\models\LocState;
use app\models\PartnerLocator;
use app\models\PartnerLocatorSearch;
use yii\widgets\ActiveForm;
use yii\web\HttpException;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
    	$model = new PartnerLocator();
        $searchModel = new PartnerLocatorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post());
    	$typeArr = ArrayHelper::map(Status::find()->asArray()->all(),'id','name');
    	$countryArr = ArrayHelper::map(LocCountry::find()->asArray()->all(),'country_id','name');
        return $this->render('index', ['model' => $model, 'dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'typeArr' => $typeArr, 'countryArr' => $countryArr]);
    }
    
    public function actionGetState() {
    	Yii::$app->response->format = Response::FORMAT_JSON;
    	if (Yii::$app->request->post()['country'] && Yii::$app->request->post()['country'] != '' && is_numeric (Yii::$app->request->post()['country'])) {
    		$countryArr = ArrayHelper::map(LocState::find()->where(['country_id'=>Yii::$app->request->post()['country']])->asArray()->all(),'state_id','name');
    		if (!empty($countryArr)) {
    			return $countryArr;
    		}
    	} 
    	return false;
    }

}