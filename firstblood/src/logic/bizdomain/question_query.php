<?php

class question_Query extends Query
{
	//查出所有分类
    public function categary()
    {
        $sql = "select * from etc_categary";
        return $this->listByCmd($sql);
    }
    //查出分类下的选择题题目
    public function showpro($type,$cid)
    {
        $sql = "select * from etc_topic where type=? and cid=? order by id asc";
        return $this->listByCmd($sql,array($type,$cid));
    }
    //查出单选多选每道题的所有答案
    public function showanswer($id)
    {
        $sql = "select * from etc_selection where tid=? order by id asc";
        return $this->listByCmd($sql,array($id));
    }
    //查到问答题的答案
    public function showanswer1($id)
    {
        $sql = "select * from etc_ask where tid=? order by id asc";
        return $this->listByCmd($sql,array($id));
    }
    //查出分类下的判断题
    public function showpro1($cid)
    {
        $sql = "select * from etc_judge where cid=? order by id asc";
        return $this->listByCmd($sql,array($cid));
    }

}
class paper_Query extends Query
{
	//拿到单选题
    public function showpaper($cid)
    {
        $sql = "select id as tid,topic from etc_topic where cid= ? and type = 1 order by id desc";
        return $this->listByCmd($sql,array($cid));
    }
    //拿到判断题
    public function showpaper1($cid)
    {
        $sql = "select id as tid,content from etc_judge where cid= ? order by id desc";
        return $this->listByCmd($sql,array($cid));
    }
    //拿到多选题
    public function showpaper2($cid)
    {
        $sql = "select id as tid,topic from etc_topic where cid= ? and type = 2 order by id desc";
        return $this->listByCmd($sql,array($cid));
    }
    //拿到问答题
    public function showpaper3($cid)
    {
        $sql = "select id as tid,topic from etc_topic where cid= ? and type = 4 order by id desc";
        return $this->listByCmd($sql,array($cid));
    }
    //拿到填空题
    public function showpaper4($cid)
    {
        $sql = "select id as tid,topic from etc_topic where cid= ? and type = 5 order by id desc";
        return $this->listByCmd($sql,array($cid));
    }
    //拿到单选题的答案
    public function showanswer($id)
    {
        $sql = "select id as asid,content from etc_selection where tid= ?";
        return $this->listByCmd($sql,array($id));
    }
    //检查单选题正确的数量
    public function checksel($tid,$asid)
    {
        $sql = "select correct from etc_selection where tid= ? and id = ?";
        return $this->getByCmd($sql,array($tid,$asid));
    }
    //检查多选
    public function checksel2($tid)
    {
        $sql = "select count(*)as num1 from etc_selection where tid= ? and correct= 1";
        return $this->getByCmd($sql,array($tid));
    }
    public function checksel3($tid,$asid)
    {
        $sql = "select count(*)as num from etc_selection where tid= ? and id=? and correct=1";
        return $this->getByCmd($sql,array($tid,$asid));
    }
    public function checksel4($tid,$asid)
    {
        $sql = "select count(*)as xnum from etc_selection where tid= ? and id=? and correct=0";
        return $this->getByCmd($sql,array($tid,$asid));
    }
    //检查判断题正确的数量
    public function checkjuege1($tid)
    {
        $sql = "select correct from etc_judge where id= ?";
        return $this->getByCmd($sql,array($tid));
    }
    //查出所有的答题人
    public function listres($start,$stop)
    {
    	$sql = "select * from respondents limit ?,?";
    	return $this->listByCmd($sql,array($start,$stop));
    }
    public function listres1($name,$start,$stop)
    {
        $sql = "select * from respondents where uname like ? limit ?,?";
        return $this->listByCmd($sql,array($name,$start,$stop));
    }
    //计算总共多少答卷
    public function count()
    {
        $sql = "select count(*)as num from respondents";
        return $this->getByCmd($sql);
    }
    public function count1($name)
    {
        $sql = "select count(*)as num from respondents where uname like ?";
        return $this->getByCmd($sql,array($name));
    }
    //计算得分
    public function listscore($pid)
    {
    	$sql = "select id,sum(score)as score from showpaper where pid = ?;";
    	return $this->listByCmd($sql,array($pid));
    }
    //查出一个人做过的题
    public function showonepaper($pid)
    {
    	$sql = "select * from showpaper where pid = ?;";
    	return $this->listByCmd($sql,array($pid));
    }
    //查出一个人做的选择题
    public function  check1($tid)
    {
    	$sql = "select id,topic from etc_topic where id = ?;";
    	return $this->getByCmd($sql,array($tid));
    }
    //查出一个人做的xuanze题的答案
    public function  checkas1($tid)
    {
    	$sql = "select content from etc_selection where id = ?;";
    	return $this->getByCmd($sql,array($tid));
    }
    //查出一个人做的判断题
    public function  checkas3($tid)
    {
    	$sql = "select id,content from etc_judge where id = ?;";
    	return $this->getByCmd($sql,array($tid));
    }
    //查出一个人做的问答题
    public function  checkas4($tid)
    {
    	$sql = "select id,topic from etc_topic where id = ?;";
    	return $this->getByCmd($sql,array($tid));
    }
    public function  search4($tid4)
    {
        $sql = "select * from etc_ask where tid = ?;";
        return $this->getByCmd($sql,array($tid4));
    }
    //查出一个人做的填空题
    public function  checkas5($tid)
    {
    	$sql = "select id,topic from etc_topic where id = ?;";
    	return $this->getByCmd($sql,array($tid));
    }
    public function listpapername($id)
    {
        $sql = "select categary from etc_categary where id = ?;";
        return $this->getByCmd($sql,array($id));
    }
}