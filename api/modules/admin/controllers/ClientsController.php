<?php

namespace api\modules\admin\controllers;

use app\models\Clients;
use yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;

class ClientsController extends ActiveController
{

    public $modelClass = Clients::class;
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];

    public function init(){
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
}