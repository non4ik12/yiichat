<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="/js/jquery.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script src="/js/socket.io.js"></script>
    <script src="/js/olchat.js"></script>
    <style type="text/css">
        #dm-onchat{position:fixed;bottom:0;right:0;margin-right:140px;margin-bottom:10px;width:300px;transition:all .3s}#dm-onchat .dm-onchat-head{cursor:pointer;width:290px}#dm-onchat .dm-onchat-head span{font-family:"ProximaNova Regular","Glyphicons Halflings";display:inline-block;font-size:16px}#dm-onchat .dm-onchat-phrase{background:#bc503e;color:#fff;font-size:15px;float:right;padding:10px;border-radius:4px;position:relative;box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);margin-bottom:20px;margin-right:14px}#dm-onchat .admin-message:after,#dm-onchat .dm-onchat-phrase:after{content:"";display:block;margin-right:-5px;background:#bc503e;position:absolute;right:0;top:15px;height:11px;width:11px;-ms-transform:rotate(45deg);-webkit-transform:rotate(45deg);transform:rotate(45deg)}#dm-onchat .dm-onchat-phrase:before{font-size:11px;margin-right:10px}#dm-onchat .dm-onchat-body{display:none}#dm-onchat .dm-onchat-input{background-color:#fff;border:solid 1px #D7DEE0;border-radius:4px;color:#656D78;padding:11px 14px 12px;width:100%;margin-bottom:10px;-webkit-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);-moz-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);font-size:16px;padding-right:40px}#dm-onchat .dm-onchat-photo{background:url(https://tidio-images.s3.amazonaws.com/50dbee1062413211ae675d448a71f051.png);display:inline-block;height:46px;width:46px;background-size:46px;float:right;background-size:cover;background-position:center;vertical-align:top;border-radius:22px;-webkit-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);-moz-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);box-shadow:2px 6px 8px 0 rgba(0,0,0,.24)}#dm-onchat .message-row{overflow:hidden}#dm-onchat .message{max-width:198px;margin-right:12px;background-color:rgba(74,137,220,.95);color:#fff;text-align:left;padding:11px 14px 12px;display:inline-block;position:relative;border-radius:4px;-webkit-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);-moz-box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);box-shadow:2px 6px 8px 0 rgba(0,0,0,.24);overflow-wrap:break-word;word-wrap:break-word;-ms-word-break:break-all;word-break:break-all;word-break:keep-all;-ms-hyphens:auto;-moz-hyphens:auto;-webkit-hyphens:auto;hyphens:auto;margin-bottom:20px}#dm-onchat .user-message{background-color:#434a54;border-radius:4px 4px 4px 0}#dm-onchat .admin-message{background-color:#bc503e;border-radius:4px;float:right}#dm-onchat .admin-message:after{background-color:#bc503e}#dm-onchat .user-message:after{content:"";display:block;border:solid 7px transparent;border-left-color:red;border-left-color:rgba(74,137,220,.95);position:absolute;right:-14px;top:14px;transition:all .3s;border-left-color:#434a54;left:0;bottom:-7px;top:auto;top:initial;border-top-width:0}#dm-onchat .dm-onchat-dialog{max-height:292px;overflow-y:scroll}#dm-onchat .dm-onchat-close{display:none;height:40px}#dm-onchat .dm-onchat-close span{position:absolute;display:inline-block;background:rgba(30,30,30,.6);border-radius:50%;height:20px;width:20px;text-align:center;color:#fff;right:0;cursor:pointer;top:0}
    </style>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>

        <div id="dm-onchat">
            <div class="dm-onchat-content">
                <div class="dm-onchat-close"><span>&times;</span></div>
                <div class="dm-onchat-head">
                    <span class="dm-onchat-photo"></span>
                    <span class="dm-onchat-phrase  glyphicon glyphicon-envelope">Мы онлайн</span>
                </div>
                <div class="dm-onchat-body">
                    <div class="dm-onchat-dialog"></div>
                    <input type="text" class="dm-onchat-input" placeholder="Введите Ваше сообщение" />
                </div>
            </div>
        </div>
        <script>$("#dm-onchat").olchat();</script>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
