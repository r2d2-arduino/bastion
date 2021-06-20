<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Sensor;
use app\models\Home;
use app\models\Position;
use app\models\Device;


use \app\models\HomePosition;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
    public function actions()
    {
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
    public function actionIndex()
    {       
        $home_id = (int) Yii::$app->request->get('home_id', 0);
        $position_id = (int) Yii::$app->request->get('position_id', 0);
        $device_id = (int) Yii::$app->request->get('device_id', 0);
        //$sensor_id = (int) Yii::$app->request->get('sensor_id', 0);
        
        $type_id = Yii::$app->request->get('type_id', '0');
        
        $homes = Home::find()
                ->select(['id', 'name'])
                ->where(['user_id' => Yii::$app->user->id])
                ->all();
        
        
        $posWhere = ['user_id' => Yii::$app->user->id];
        if ($home_id)
        {
            $posWhere['home_id'] = $home_id;
        }
        
        $positions = Position::find()
                ->select(['id', 'name'])
                ->where($posWhere)
                ->all();
        
        
        $devWhere = ['user_id' => Yii::$app->user->id];
        if ($position_id)
        {
            $devWhere['position_id'] = $position_id;
        }
        $devices = Device::find()
                ->select(['id', 'name'])
                ->where($devWhere)
                ->all();

        
        if ($device_id)
        {
            $choosedDevices = Device::find()->where(['id' => $device_id])->all();
                    
            $sensorIds = \app\models\DeviceSensor::find()
                    ->select('sensor_id')
                    ->where(['device_id' => $device_id])
                    ->column();

            $senWhere['device_id'] = $device_id;
            
            $sensors = Sensor::find()
                ->where(['in', 'id', $sensorIds])
                ->andWhere(['user_id' => Yii::$app->user->id])
                ->all();
        }
        else
        {
            $choosedDevices = $devices;
            
            $sensors = Sensor::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->all();    
        }
        
        $types = ['Widgets', 'Sensors', 'Controllers'];
        
        return $this->render('index', [
            'homes' => $homes,
            'positions' => $positions,
            'devices' => $devices,
            'choosedDevices' => $choosedDevices,
            'sensors' => $sensors,
            'types' => $types,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
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

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
