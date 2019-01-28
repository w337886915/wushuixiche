<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Wechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    //
    public function getAccessToken(Wechat $wechat,Request $request){
        $data = $wechat->getAccessToken($request->post());
        //查询并注册用户
       $user = $wechat->findForPassport($data->unionId);
       if(!$user){
           $data['name'] = $data->openId;
           $data['unionid'] = $data->unionId;
           $user = $wechat->register($data);
       }
        $data = $wechat->login($data->unionid);
        return response($data);
    }
}
