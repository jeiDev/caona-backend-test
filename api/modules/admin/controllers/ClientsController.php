<?php

namespace api\modules\admin\controllers;

use yii;
use app\models\Clients;
use app\models\Addresses;
use app\models\Profiles;
use api\base\BaseController;

class ClientsController extends BaseController
{
    public function actionIndex($id = null)
    {
        $this->setActionIndex(Clients::class, [
            'unsets' => ['client_id'],
            'deleteCascade' => function($id){
                $modelAddress = Addresses::find()->where(['client_id' => $id])->one();
                $modelProfile = Profiles::find()->where(['client_id' => $id])->one();

                if($modelAddress){
                    $modelAddress->delete();
                }

                if($modelProfile){
                    $modelProfile->delete();
                }
            },
            'relationship' => function($id){
                $modelAddress = Addresses::find()->where(['client_id' => $id])->one();
                $modelProfile = Profiles::find()->where(['client_id' => $id])->one();

                return [
                    'address' => $modelAddress,
                    'profile' => $modelProfile
                ];
            }
        ], $id);
    }
}