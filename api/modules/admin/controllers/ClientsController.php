<?php

namespace api\modules\admin\controllers;

use yii;
use app\models\Clients;
use api\base\BaseController;

class ClientsController extends BaseController
{
    public function actionIndex($id = null)
    {
        $this->setActionIndex(Clients::class, [], $id);
    }
}