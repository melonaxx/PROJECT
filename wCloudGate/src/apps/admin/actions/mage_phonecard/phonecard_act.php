<?php

class Action_phonecard extends XAction
{
    public function _run($request,$xcontent)
    {
        return XNext::useTpl('/mage_phonecard/phonecard_index.html');
    }
}