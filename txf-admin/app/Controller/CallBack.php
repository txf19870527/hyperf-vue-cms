<?php


namespace App\Controller;


class CallBack extends AbstractController
{

    /**
     * 第三方回调测试接口
     */
    public function test()
    {

        return 'ok';

//        $body = $this->request->getAttribute("body");
//
//        return $body;
    }

}