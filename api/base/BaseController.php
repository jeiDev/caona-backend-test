<?php

namespace api\base;

use yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\data\Pagination;

class BaseController extends Controller
{
    protected $methods = [];
    public $enableCsrfValidation = false;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    public function init(){
        parent::init();
        \Yii::$app->user->enableSession = false;
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['GET', 'POST', 'DELETE', 'PUT'],
                    'view' => ['GET']
                ],
            ],
        ];
    }

    protected function setActionIndex($method, $unsets = [], $id = null)
    {
        $verb = Yii::$app->request->getMethod();
        
        if ($verb === 'GET') {
            $this->onGet($method, $id);
        } elseif ($verb === 'POST') {
            $this->onActionCreate($method);
        } elseif ($verb === 'DELETE') {
            $this->onDelete($method);
        } elseif ($verb === 'PUT') {
            $this->onActionUpdate($method, $unsets);
        } 
    }

    protected function onGet($model, $id = null){
        if($id){
            $modelDB = $this->findModel($model, $id);

            return $this->asJson($modelDB ? [
                'data' => $modelDB
            ] : [
                'error' => 'Data not found'
            ]);
        }

        $query = $model::find();
        $pagination = new Pagination([
            'defaultPageSize' => 10, 
            'totalCount' => $query->count(),
        ]);

        $data = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return$this->asJson([
            'data' => $data,
            'pagination' => $pagination,
        ]);
    }

    protected function onDelete($model){
        $id = Yii::$app->request->getBodyParam('id');
        $data = $this->findModel($model, $id);

        if($data){
            $data->delete();
        }

        return $this->asJson([
            'deleted' => $data ? true : false
        ]);
    }

    protected function onActionView($model, $id){
        $model = $this->findModel($model, $id);
        return $this->asJson( $model ? [
            'data' => $model
        ] : [
            'errors' => [
                'message' => ['Data not found']
            ]
        ]);
    }

    protected function onActionCreate($model){
        $instance = new $model();
        $instance->load(Yii::$app->getRequest()->getBodyParams(), '');
        $created = $instance->save();

        Yii::$app->response->statusCode = $created ? 201 : 422;
        return $this->asJson($created ? [
            'data' => $instance
        ] : [
            'errors' => $instance->errors
        ]);
    }

    protected function onActionUpdate($model, $unsets = []){
        $id = Yii::$app->request->getBodyParam('id');
        $model = $this->findModel($model, $id);
        if(!$model){
            return $this->asJson([
                'errors' => [
                    'message' => ['Data not found']
                ]
            ]);
        }

        $data = Yii::$app->getRequest()->getBodyParams();

        foreach ($unsets as $unset) {
            unset($data[$unset]);
        }

        $updated = $model->load($data, '') && $model->save();

        return $this->asJson([
            'data' => $model,
            'updated' => $updated,
            'errors' => $model->errors
        ]);
    }

    protected function findModel($model, $id)
    {
        if (($modelDB = $model::findOne($id)) !== null) {
            return $modelDB;
        }

        return null;
    }
}