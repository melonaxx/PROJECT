<?php
class admin_assembly
{/*{{{*/
    public function setup()
    {/*{{{*/
        CommonAssembly::setup();
        XAop::pos(XAop::TPL  )->append_by_match_name(".*", new AdminNavView);
        XAop::pos(XAop::TPL )->append_by_match_name("XAdminPossibleAction", new AdminLogin);
        XAop::pos(XAop::ROOT_ERROR)->append_by_match_name(".*", new AdminErrorPoc());

    }/*}}}*/
}/*}}}*/

