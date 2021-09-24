<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Department;
use app\models\DepartmentSearch;
use app\models\Employee;
use app\models\EmployeeSearch;
use app\models\DepartmentEmployee;
use app\models\DepartmentEmployeeSearch;
use yii\widgets\ActiveForm;
use yii\web\HttpException;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

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
        return $this->render('index');
    }
    
    public function actionDepartment() {
    	$model = new Department();
        $searchModel = new DepartmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $DepartmentEmployee = new DepartmentEmployee();
        $notDelete = $DepartmentEmployee->toDelete();
        return $this->render('department', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'notDelete'=> $notDelete,
        ]);
    }
    
    public function actionEmployee() {
        $model = new Employee();
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('employee', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }
    
    public function actionDepartmentStuff() {
        $model = new DepartmentEmployee();
        $searchModel = new DepartmentEmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('stuff', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model'=>$model,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    
    public function actionDepartmentCreate () {
    	$model = new Department();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation ) { // валидация
    		Yii::$app->response->format = Response::FORMAT_JSON;
    		return ActiveForm::validate($model);
        }

        return $this->renderAjax('_department_form', [
            'model' => $model,
        ]);
    }
    
    public function actionDepartmentUpdate () {
    	$model = new Department();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (isset(Yii::$app->request->post()['id'])){
        	$model = Department::find()->where(['id' => Yii::$app->request->post()['id']])->one();
        } else {
            throw new HttpException(404, "Страница не найдена.");
    	}
    	
        return $this->renderAjax('_department_form', [
            'model' => $model,
        ]);
    }
    
    public function actionDepartmentCreating() {
        $model = new Department();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            if ($model->validate()) {
                return $model->createDepartment();
                return true;
            }
        }
        return false;
    }
    
    public function actionDepartmentUpdating() {
        $model = (isset(Yii::$app->request->post('Department')['id']) ? Department::findOne(Yii::$app->request->post('Department')['id']) : new Department());
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            if ($model->validate()) {
                return $model->updateDepartment();
                return true;
            }
        }
        return false;
    }
    
    public function actionDepartmentDeleting() {
        if (Yii::$app->request->post()['info']) {
            Department::deleteDepartment(json::decode(Yii::$app->request->post()['info']));
        }
        return;
    }
    
    public function actionEmployeeCreate () {
    	$model = new Employee();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation ) { // валидация
    		Yii::$app->response->format = Response::FORMAT_JSON;
    		return ActiveForm::validate($model);
        }
        
        $departments = Department::find()->where(['deleted' => false])->all();
        $departmentArr = ArrayHelper::map($departments, 'id', 'name');

        return $this->renderAjax('_employee_form', [
            'model' => $model,
            'departments' => $departmentArr,
        ]);
    }
    
    public function actionEmployeeUpdate () {
    	$model = new Employee();
    	
    	$departments = Department::find()->where(['deleted' => false])->all();
        $departmentArr = ArrayHelper::map($departments, 'id', 'name');
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (isset(Yii::$app->request->post()['id'])){
        	$model = Employee::find()->where(['id' => Yii::$app->request->post()['id']])->one();
        	$departmentOne = DepartmentEmployee::find()->where(['employee_id' => Yii::$app->request->post()['id']])->all();
        	$model->department = ArrayHelper::map($departmentOne, 'department_id', 'department_id');
        } else {
            throw new HttpException(404, "Страница не найдена.");
    	}
    	
        return $this->renderAjax('_employee_form', [
            'model' => $model,
            'departments' => $departmentArr,
        ]);
    }
    
    public function actionEmployeeCreating() {
        $model = new Employee();
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            if ($model->validate()) {
                return $model->createEmployee();
                return true;
            }
        }
        return false;
    }
    
    public function actionEmployeeUpdating() {
        $model = (isset(Yii::$app->request->post('Employee')['id']) ? Employee::findOne(Yii::$app->request->post('Employee')['id']) : new Employee());
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->enableCsrfValidation) { // валидация
            if ($model->validate()) {
                $model->updateEmployee();
                return true;
            }
        }
        return false;
    }
    
    public function actionEmployeeDeleting() {
        if (Yii::$app->request->post()['info']) {
            return Employee::deleteEmployee(json::decode(Yii::$app->request->post()['info']));
        }
        return;
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}