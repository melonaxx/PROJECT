<?php

/**
 * @brief 	验证码
 */
class Action_captcha extends XAction
{
    public function _run($request, $xcontext)
    {
        $app = $request->app;
        $client = GClientAltar::getWOreoClient();
        $result = $client->createCode($app , 4 , 143 , 53 , 27);

        return XNext::nothing();
    }
}