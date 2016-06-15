<?php

class Etc_categaryWriter extends DWriter
{
	//删除分类
    public function delc($id)
    {
        $sql = "delete from etc_categary where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}
//题目表
class Etc_topicWriter extends DWriter
{
	//删除题目
    public function delpro($id)
    {
        $sql = "delete from etc_topic where id = ?";
        return $this->exeByCmd($sql, array($id));
    }

}
//选择题答案表
class Etc_selectWriter extends DWriter
{
	//删除选择题全部答案
    public function delanswer($id)
    {
        $sql = "delete from etc_selection where tid = ?";
        return $this->exeByCmd($sql, array($id));
    }
	//删除问答题全部答案
    public function delanswer1($id)
    {
        $sql = "delete from etc_ask where tid = ?";
        return $this->exeByCmd($sql, array($id));
    }
    //删除一条
    public function deloneanswer($id)
    {
        $sql = "delete from etc_selection where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}
//删除判断题
class Etc_judgeWriter extends DWriter
{
    //删除一条
    public function delone($id)
    {
        $sql = "delete from etc_judge where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}
//删除问答题答案
class Etc_askWriter extends DWriter
{
    //删除一条
    public function delaskanswer($id)
    {
        $sql = "delete from etc_ask where id = ?";
        return $this->exeByCmd($sql, array($id));
    }
}
//删除答卷
class CleananswerWriter extends DWriter
{
    public function cleanas($uid)
    {
        $sql = "delete from respondents where id = ?";
        return $this->exeByCmd($sql, array($uid));
    }
    public function cleanall($uid)
    {
        $sql = "delete from showpaper where pid = ?";
        return $this->exeByCmd($sql, array($uid));
    }
}
