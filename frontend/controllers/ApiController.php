<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use maxwen\easywechat\Wechat;

/**
 * Site controller
 */
class ApiController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {

        var_dump($_SESSION['wechat_user']);die;

        // 未登录
        if (empty($_SESSION['wechat_user'])) {
            $oauth = Yii::$app->wechat->oauth;
            $_SESSION['target_url'] = 'user/profile';
//            return $oauth->redirect();
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
           $oauth->redirect()->send();
        }
        // 已经登录过
        $user = $_SESSION['wechat_user'];


    }

    public function actionCallback(){

        $oauth = Yii::$app->wechat->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $_SESSION['wechat_user'] = $user->toArray();
        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];

//        header('location:'. $targetUrl);
        var_dump($_SESSION['wechat_user']);
        // 跳转到 user/profile
    }


    public function actionWeixin(){
        // 微信网页授权:
        if(Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
            return Yii::$app->wechat->authorizeRequired()->send();
        }else{
            var_dump(Yii::$app->wechat->user);
            // 获取 access token 实例
            $accessToken = Yii::$app->wechat->access_token; // EasyWeChat\Core\AccessToken 实例
            $token = $accessToken->getToken(); // token 字符串
            echo $token;
        }

    }
}
