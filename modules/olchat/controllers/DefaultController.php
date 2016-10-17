<?php

namespace app\modules\olchat\controllers;

use yii\web\Controller;

/**
 * Default controller for the `olchat` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOnlineUsers()
    {
    	return $this->render('online_users');
    }
}
