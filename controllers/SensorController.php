<?php

namespace app\controllers;

use Yii;
use app\models\Sensor;
use app\models\SensorSearch;
use app\models\SensorStat;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SensorController implements the CRUD actions for Sensor model.
 */
class SensorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sensor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SensorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sensor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Sensor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sensor();

        if ($model->load(Yii::$app->request->post()) ) 
        {
            $model->user_id = Yii::$app->user->id;
            
            if ($model->save()) 
            {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Sensor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sensor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Sensor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sensor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sensor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    public function actionActual()
    {
        $sensor_id = Yii::$app->request->post('sensor_id', 0);
        $device_id = Yii::$app->request->post('device_id', 0);
        
        $sensorValues = [];
        $sensorStats = [];
                
        if ($sensor_id)
        {
            $sensorStats[] = SensorStat::find()->where(['sensor_id' => $sensor->id])->one();
        }
        else if ($device_id)
        {
            $sensorStats = SensorStat::find()->where(['device_id' => $device_id])->all();
        }
        else
        {
            $sensorStats = SensorStat::find()->all();
        }
        
        foreach ($sensorStats as $stat)
        {
            $sensorValues[] = ['sensor_id' => $stat->sensor_id, 'davice_id' => $stat->device_id, 'actuality' => $stat->getSecondsFromLast(), 'value' => $stat->getLastValue()];
        }
                
        echo \yii\helpers\Json::encode($sensorValues);
    }
}
