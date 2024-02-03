<?php

namespace api\modules\admin\controllers;

use yii;
use app\models\Profiles;
use api\base\BaseController;

class ProfilesController extends BaseController
{
    public function actionIndex($id = null)
    {
        $this->setActionIndex(Profiles::class, [
            'client_id'
        ], $id);
    }
}