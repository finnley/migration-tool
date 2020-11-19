<?php

namespace app\controllers;

use app\models\ThirdpatryUserTool;
use yii\console\Controller;

class CreateMicroServiceCommandUserController extends Controller
{
    public $feature;

    /**
     * ./yii migrate-createCommandUser --feature=survey
     */
    public function actionIndex()
    {
        ThirdpatryUserTool::createUserForFeature($this->feature);
    }

    public function options($actionID)
    {
        // $actionId might be used in subclasses to provide options specific to action id
        return ['feature'];
    }
}