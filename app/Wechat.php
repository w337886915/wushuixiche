<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Wechat extends Authenticatable
{
    use Notifiable,HasApiTokens;
    public function findForPassport($username) {
        return $this->where('openId', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        $decrypted = Crypt::decryptString($password);
        if ($decrypted == $this->openId) {
            return true;
        }
        return false;
    }
    //获取acctoken
    public function getAccessToken($code){
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=yourAppid&secret=appSecret&js_code=' . $code . '&grant_type=authorization_code';
        //yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；
        $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
        $json = json_decode($info);//对json数据解码
        $arr = get_object_vars($json);
        var_dump($arr);

    }


}
