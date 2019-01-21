<?php

namespace App;


use GuzzleHttp\Client;
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
        $http = new Client();
        $res = $http->get('https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx4ff2f7cd3f7243f1&secret=48a1192f1afa54cfb9c2cf3e86a48833&code='.$code.'&grant_type=authorization_code');
        dd($res);
    }


}
