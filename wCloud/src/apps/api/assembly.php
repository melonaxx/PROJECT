<?php

class api_assembly
{
    public function setup()
    {
        CommonAssembly::setup();
        XAop::pos(XAop::TPL  )->append_by_match_name(".*", new ApiNavView);
        XAop::pos(XAop::ROOT_ERROR)->append_by_match_name(".*", new ApiErrorPoc());

    }
}

