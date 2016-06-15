<?php
class Action_question extends XAction
{
    public function _run($request,$xcontext)
    {
        
        return XNext::useTpl('showquestion.html');
    }
}