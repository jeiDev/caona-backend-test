<?php

namespace api\modules\admin\controllers;

use yii;
use app\models\Addresses;
use api\base\BaseController;

class AddressesController extends BaseController
{
    public function actionIndex($id = null)
    {
        $this->setActionIndex(Addresses::class, [
            'unsets' => ['client_id'],
            'deleteCascade' => function(){
                
            }
        ], $id);
    }
}