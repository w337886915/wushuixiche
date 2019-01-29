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
	    $data = json_decode($data);
        //查询并注册用户
       $user = $wechat->findForPassport($data->unionId);
       if(!$user){
           $adddata['name'] = $data->openId;
           $adddata['unionid'] = $data->unionId;
	   $user = $wechat->register($adddata);
       }
        $returndata = $wechat->login($user);
       if($returndata){
           return response(['code'=>200,'msg'=>'获取成功','data'=>$returndata]);
       }else{
           return response(['code'=>401,'msg'=>'获取失败','data'=>$returndata]);
       }

    }

}
