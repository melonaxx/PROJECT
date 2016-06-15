<?php
//定义一个分页类
class Page
{
	//成员属性
	public $page;//当前页
	public $pageSize;//当前页大小
	public $MaxPage;//最大页
	public $pageRows;//数据总条数
    public $prevpage;//上一页
    public $nextpage;//下一页
    public $firstpage;//shang
    public $lastpage;//xia
    public $url;//当前的地址
	public function __construct($pageRows,$pageSize){
		$this->pageSize=$pageSize;
		$this->pageRows=$pageRows;
		$this->page=isset($_GET['p'])?($_GET['p']):1;
		$this->checkMaxPage();
		$this->checkPage();

	}
	//计算最大页
	public function checkMaxPage(){
		$this->MaxPage=ceil($this->pageRows/$this->pageSize);
		return $this->MaxPage;
	}
	//检测当前页
	public function checkPage(){
		if($this->page>$this->MaxPage){
			$this->page=$this->MaxPage;
		}
		if($this->page<1){
			$this->page=1;
		}
	}
	//limit返回
	public function limit(){
		return (($this->page-1)*$this->pageSize).",".$this->pageSize;
	}
	//返回上一页
	public function prev(){
		$this->prevpage = "<a href='{$url}?p=".($this->page-1)."{$where}'>前一页</a>";
		return $this->prevpage;
	}
	//下一页
	public function next(){
		$this->nextpage = "<a href='{$url}?p=".($this->page+1)."{$where}'>后一页</a>";
		return $this->nextpage;
	}
	//首页
	public function first(){
		$this->firstpage = "<a href='{$url}?p=1{$where}'>首页</a>";
		return $this->firstpage;
	}
	//尾页
	public function last(){
		$this->lastpage = "<a href='{$url}?p={$this->MaxPage}{$where}'>末页</a>";
		return $this->lastpage;
	}
	public function show(){
		$this->url=$_SERVER['PHP_SELF'];
		//过滤p的信息
		 $where = '';
		foreach($_GET as $k=>$v){
			if(!empty($v) && $k!="p"){
				$where.="&{$k}={$v}";
			}
		}
        $array=array("firstpage"=>$this->first(),"prevpage"=>$this->prev(),"nextpage"=>$this->next(),"lastpage"=>$this->last());
        return $array;
	}
} 
 ?> 
