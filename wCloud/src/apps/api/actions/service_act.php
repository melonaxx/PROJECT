<?php

/*
|-----------------------------------------------------
| 解密用户信息 DecrypeUserInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责，解密登陆后加密的信息，用于在
| 每个页面都能有登陆这的基本信息，跟cookie配合
| 使用。后期可扩展其它功能。
|
*/
class Action_decrypt_userinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $userid = $request->userid;
        $token  = $request->token;

        $login_info = SecuritySvc::ins()->decryptUserInfo($userid, $token);
        if (!$login_info) {
           echo ResultSet::jfail(500, "Server Error Of DecryptUserInfo");
           return XNext::nothing(); 
        }
    
        $info = explode("\n", $login_info);
        $userid = $info[0];
        $user = UserSvc::ins()->getUserById($userid);
        if (!$user) {
            echo ResultSet::jfail(404, "User Not Found");
            return XNext::nothing();
        } 

        $mobileno = UserInfoSvc::ins()->getMobileNoByUserId($userid);
        if (!$mobileno) {
            echo ResultSet::jfail(404, "Mobileno Not Found"); 
            return XNext::nothing();
        }

        $user = $user->toArray();
        $user['time'] = $info[1];
        $user['mobileno'] = $mobileno;

        echo ResultSet::jsuccess($user);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 存储通知信息 Store CallInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责存储通知信息，在前台首页做轮
| 播动态显示，内容显示可设置，需后台操作。
|
*/
class Action_store_callinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $content = $request->content;

        $result = CallBoardSvc::ins()->storeCallInfo($content); // 存储设置的通知内容
        if (!$result) {
            echo ResultSet::jfail(4031, "StoreCallInfo Fail");
            return XNext::nothing();
        }       

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 显示通知信息列表 Show CallInfo List Action
|-----------------------------------------------------
|
| 这个Action主要负责显示存储的所有通告信息，为设置
| 哪些信息可以前台动态显示做展示。
|
*/
class Action_show_callinfo_list extends XAction
{
    public function _run($request, $xcontext)
    {
        $calllist = CallBoardSvc::ins()->showCallInfoList(); // 获取所有的通知信息，列表展示
        if (!$calllist) {
            echo ResultSet::jfail(404, "CallInfo Not Found");
            return XNext::nothing();
        }    

        foreach($calllist as $k => $v) {
            $status[$k] = $v['status'];
            $time[$k]   = $v['createtime'];
        }

        array_multisort($status, SORT_DESC, $time, SORT_DESC, $calllist);

        echo ResultSet::jsuccess($calllist);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 显示通知信息 Show CallInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责获取前台可显示的通告信息，目前
| 值做了可显示一条信息，后期扩展可不同的位置对
| 应显示不同类型的通知信息。
|
*/
class Action_show_callinfo extends XAction
{
    public function _run($request, $xcontext)
    {
        $callinfo = CallBoardSvc::ins()->showCallInfo(); // 获取通知信息，前台展示
        if (!$callinfo) {
            echo ResultSet::jfail(404, "CallInfo Not Found");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess($callinfo);
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 移除通知信息 Remove CallInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责移除不用的通知信息，只是修改一下
| 信息的状态。
|
*/
class Action_remove_callinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $id = $request->id;

        $result = CallBoardSvc::ins()->removeCallInfo($id); // 移除对应的通知信息
        if (!$result) {
            echo ResultSet::jfail(4031, "RemoveCallInfo Fail");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

/*
|-----------------------------------------------------
| 设置通知信息显示 Set Display CallInfo Action
|-----------------------------------------------------
|
| 这个Action主要负责设置那个通知信息可展示，目前只
| 设置一条信息显示，所以，每次设置一条显示时，要将
| 本来显示的要设置为隐藏，消息在首页位置，后期
| 扩展，可增 加其它设置。
|
*/
class Action_set_display_callinfo extends XPostAction
{
    public function _run($request, $xcontext)
    {
        $sid = $request->sid; // 要显示的信息id
        $hid = $request->hid; // 开始显示要转为隐藏的id

        $show = CallBoard::STATUS_SHOW;
        $hide = CallBoard::STATUS_HIDE;

        $sresult = CallBoardSvc::ins()->updateShowStatus($sid, $show); // 设置通知信息的显示
        if (!$sresult) {
            echo ResultSet::jfail(4031, "Set Display CallInfo Fail");
            return XNext::nothing();
        }

        if ($hid) {
            $hresult = CallBoardSvc::ins()->updateShowStatus($hid, $hide); // 设置通知信息的隐藏
            if (!$hresult) {
                echo ResultSet::jfail(4031, "Set Hide CallInfo Fail");
                return XNext::nothing();
            }
        }

        echo ResultSet::jsuccess();
        return XNext::nothing();
    }
}

