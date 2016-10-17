<?php

namespace app\modules\olchat;

/**
 * olchat module definition class
 */
class Module extends \yii\base\Module
{

    public function beforeAction($action)
    {
        $this->layout = "@app/modules/olchat/views/default/layout";
        if (!\Yii::$app->user->isGuest && \Yii::$app->user->identity->getIsAdmin()) {
            return true;
        }
        \Yii::$app->getResponse()->redirect('/', 302);
    }

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\olchat\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
