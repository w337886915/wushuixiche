<?php

namespace App;



use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Wechat extends Authenticatable
{
    protected $table = 'users';
    private $wxappid = 'wx4ff2f7cd3f7243f1';
    private $wxsecret = '48a1192f1afa54cfb9c2cf3e86a48833';
    use Notifiable,HasApiTokens;

    public function findForPassport($username) {
        return $this->where('unionid', $username)->first();
    }

    public function validateForPassportPasswordGrant($password)
    {
        return true;
    }
//    //获取acctoken
//    public function getAccessToken($code){
//        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=wx4ff2f7cd3f7243f1&secret=48a1192f1afa54cfb9c2cf3e86a48833&js_code=' . $code . '&grant_type=authorization_code';
//        //yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；
//        $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
//        $json = json_decode($info);//对json数据解码
//        $arr = get_object_vars($json);
//        dd($arr);
//
//    }

    public function getAccessToken($post)
    {
        if (!empty($post)) {
            $appid = $this->wxappid;
            $secret = $this->wxsecret;
            if(isset($post['code']))                $code        = $post['code'];
            if(isset($post['iv']))                  $iv          = $post['iv'];
            if(isset($post['rawData']))             $rawData     = $post['rawData'];
            if(isset($post['signature']))           $signature   = $post['signature'];
            if(isset($post['encryteData']))       $encryptedData = $post['encryteData'];
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appid . "&secret=" . $secret . "&js_code=" . $code . "&grant_type=authorization_code";
            $weixin = file_get_contents($url);
            $jsondecode = json_decode($weixin);
            $res = get_object_vars($jsondecode);
            $sessionKey = $res['session_key'];//取出json里对应的值
            // 验证签名
            $signature2 = sha1(htmlspecialchars_decode($rawData) . $sessionKey);
            if ($signature2 !== $signature) return json("signNotMatch");
            $data = [];
            $errCode = $this->decryptData($encryptedData, $iv, $sessionKey, $data);

            if ($errCode == 0) {
                return $data;
            } else {
                return json('获取失败');
            }
        }
    }
    public function decryptData( $encryptedData, $iv,$sessionKey, &$data )
    {
        if (strlen($sessionKey) != 24) {
            return json('sessionKey错误');
        }
        $aesKey=base64_decode($sessionKey);


        if (strlen($iv) != 24) {
            return json('iv错误');
        }
        $aesIV=base64_decode($iv);
        $aesCipher=base64_decode($encryptedData);
        $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj=json_decode( $result );
        if( $dataObj  == NULL )
        {
            return json('IllegalBuffer错误');
        }
        if( $dataObj->watermark->appid != $this->wxappid )
        {
            return json('IllegalBuffer错误');
        }
        $data = $result;
        return  $data;
    }
    public function register($data){
        return $this->insert($data);
    }
    //passport授权登陆
    public function login($unionid)
    {
        $http = new \GuzzleHttp\Client();

        $response = $http->post('https://weixin.whgjh.top/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'ANfut0P5nOY3l76AuIQRKKVCy8Exk02Ozyk4GYoh',
                'username' => $unionid,
                'password' => '*',
                'scope' => '',
            ],
        ]);

        return json_decode((string)$response->getBody(), true);
    }

}
