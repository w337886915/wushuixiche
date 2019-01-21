<?php

namespace App\Http\Controllers\Api\Wechat;

use App\Wechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    //
    public function getAccessToken(Wechat $wechat){
        $wechat->getAccessToken('033tGDwK01ZBy825bBvK0W8EwK0tGDwA');
    }
}
