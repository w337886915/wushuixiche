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
        return response($data);
    }
}
