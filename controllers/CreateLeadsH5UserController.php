<?php

namespace app\controllers;

use app\models\ThirdpatryUserTool;
use yii\console\Controller;

class CreateLeadsH5UserController extends Controller
{
    public $mid;

    /**
     * ./yii migrate-createCommandUser --mid=33
     */
    public function actionIndex()
    {
        ThirdpatryUserTool::createUserForLeadsH5($this->mid);
    }

    public function options($actionID)
    {
        // $actionId might be used in subclasses to provide options specific to action id
        return ['mid'];
    }
}