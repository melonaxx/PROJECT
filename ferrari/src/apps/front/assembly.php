<?php
class front_assembly
{/*{{{*/
    public function setup()
    {/*{{{*/
        CommonAssembly::setup();
        // 所有XAuthPossibleAction的子类Action都要执行一遍Authorization拦截器
        XAop::pos(XAop::LOGIC)->append_by_match_class("XAuthPossibleAction", new Authorization());

        XAop::pos(XAop::TPL  )->append_by_match_name(".*", new FntView());
        XAop::pos(XAop::ERROR)->append_by_match_name(".*", new FntUserInputErrorPoc());
        XAop::pos(XAop::ROOT_ERROR)->append_by_match_name(".*", new FntErrorPoc());
    }/*}}}*/
}/*}}}*/
