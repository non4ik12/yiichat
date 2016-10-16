<?php

namespace app\modules\admin;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function beforeAction($action) {
        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->getIsAdmin()) {
            return true;
        }
        \Yii::$app->getResponse()->redirect('/',302);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
