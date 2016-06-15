<?php
class Action_addquestion extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$cid = intval($request->cid);  //部门id
    	$type = intval($request->type); //题类型

    	if($type == 1 || $type == 2 || $type == 4 || $type == 5)
        {

	    	$list=XDao::query("question_Query")->showpro($type,$cid);
	    	$xcontext->list=$list; //单选多选问答填空

        }else if($type == 3)
        {
	        //判断题
	        $list1=XDao::query("question_Query")->showpro1($cid);
	        $xcontext->list1=$list1;
        }

        $xcontext->cid=$cid;  //题的类别
        $xcontext->type=$type;//题的类型
        return XNext::useTpl("showquestion.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
    }
}
//所有的题的分类
class Action_showcategary extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
	    $rows=XDao::query("question_Query")->categary();
        $xcontext->rows=$rows;
        return XNext::useTpl("showcategary.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
    }
}
//添加分类
class Action_addcategary extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$cname = htmlspecialchars($request->cname);  //部门名称   
        $id = Etc_categarySvc::ins()->addc($cname);
        if($id){
          echo  $id;
        }else{
          echo "no";
        } 
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }

}
//删除分类
class Action_delcategary extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->cid);  //分类id
        $rows=XDao::dwriter("Etc_categaryWriter")->delc($id);
        if($rows){
          echo  "yes";
        }else{
          echo "no";
        } 
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//修改分类名称
class Action_changecategary extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->cid);  //分类id
    	$cname = htmlspecialchars($request->classname); //分类名称
        $rows=Etc_categarySvc::ins()->changename($id,$cname);
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//添加题
class Action_addpro extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$cid = intval($request->cid);  //分类id
  
    	$proname = htmlspecialchars($request->pname); //题目名称
    	$type = intval($request->type); //题目分类

        if($type ==1 || $type == 2 || $type == 4 || $type == 5){
        	//添加选择题或者问答题的题目
            $rows=Etc_topicSvc::ins()->addspro($cid,$proname,$type); 	
        }else if($type == 3){
        	//添加判断题
            $rows=Etc_judgeSvc::ins()->addpro($cid,$proname);
        }
        if($rows){
        	echo $rows;
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//删除题目
class Action_delpro extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
		    	$id = intval($request->id);  //题目id
		    	$type = intval($request->type); //题目分类

           //删除单选或多选或问答题的题目
	       if($type ==1 || $type == 2 || $type ==5){
		        $row=XDao::dwriter("Etc_topicWriter")->delpro($id);

		        if($row){
                    //执行成功删除答案
		        	XDao::dwriter("Etc_selectWriter")->delanswer($id);;
		        }else{
		        	echo "no";
		        }

		   }else if($type==4){
		   	    $row=XDao::dwriter("Etc_topicWriter")->delpro($id);

		        if($row){
		        	XDao::dwriter("Etc_selectWriter")->delanswer1($id);;
		        }else{
		        	echo "no";
		        }
		   }else if($type ==3){
                $row=XDao::dwriter("Etc_judgeWriter")->delone($id);
		   }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//编辑答案
class Action_lookanswer extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->id);  //题目id
    	$type = intval($request->type);
    	$topic = htmlspecialchars($request->topic); //题目内容
        $xcontext->topic=$topic;
        $xcontext->tid=$id;
        $xcontext->type=$type;
        //拿到单选和多选的答案
        if($type == 1 || $type == 2){
           $list=XDao::query("question_Query")->showanswer($id);

        //拿到问答的答案
        }else if($type == 4){
           $list=XDao::query("question_Query")->showanswer1($id);
        }
        $xcontext->list=$list;
    	return XNext::useTpl("lookanswer.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//添加答案
class Action_addanswer extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$tid = intval($request->tid);  //题id
    	$type = intval($request->type);  //题类型
    	$proname = htmlspecialchars($request->pname); //答案

        //添加单选题或者多选的答案
        if($type == 1 || $type == 2){
            $rows=Etc_selectionSvc::ins()->addanswer($tid,$proname);
        }else if($type == 4){
        	$rows=Etc_askSvc::ins()->addanswer($tid,$proname);
        }
        if($rows){
        	echo $rows;
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//删除选择题的答案
class Action_delanswer extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->id);  //答案id
        $row=XDao::dwriter("Etc_selectWriter")->deloneanswer($id);
        if($row){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//删除问答题的答案
class Action_delask extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->id);  //答案id
        $row=XDao::dwriter("Etc_askWriter")->delaskanswer($id);
        if($row){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//设置选择题答案的错对
class Action_setanswer extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->id);  //答案id
    	$correct = intval($request->correct); //错与对
    	$type = $request->type; //题目分类

    	if($correct==0){
    		$correct=1;
    	}else if($correct==1){
            $correct=0;
    	}
 
        $rows=Etc_selectionSvc::ins()->setanswer($id,$correct);

        if($rows){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//设置判断题的对错
class Action_setjudge extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$id = intval($request->id);  //答案id
    	$cor = intval($request->correct); //错与对
    	$type = intval($request->type); //题目分类
        $correct=$cor == 0 ? 1 : 0;
        $rows=Etc_judgeSvc::ins()->setanswer($id,$correct);

        if($rows){
        	echo "yes";
        }else{
        	echo "no";
        }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//生成试卷
class Action_showpaper extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
    	$cid = intval($request->cid);  //答案id
    	//单选题
    	$list = XDao::query("paper_Query")->showpaper($cid);
	        foreach($list as $k => $v){
	        	$list[$k]['as']=XDao::query("paper_Query")->showanswer($v['tid']);
	        }
        //判断题
        $list1 = XDao::query("paper_Query")->showpaper1($cid);

        //拿到多选题
    	$list2 = XDao::query("paper_Query")->showpaper2($cid);
	        foreach($list2 as $key => $value){
	        	$list2[$key]['as']=XDao::query("paper_Query")->showanswer($value['tid']);
	        }
        //拿到问答题
    	$list3 = XDao::query("paper_Query")->showpaper3($cid);
        //拿到填空题
        $pattern = '/\{as\}.*?\{\/as\}/';
        $lis = XDao::query("paper_Query")->showpaper4($cid);
             foreach($lis as $k => $v){
     	         $input = "<input type='text' name='{$v["tid"]}_5[]' value='' style='background-color:white; width:150px;border-left:none;border-right:none;border-top:none;border-bottom:1px solid red;outline:none;color:red;'>";
				 $list4["{$v['tid']}"]["as"]=preg_replace($pattern,$input,$v['topic']);
             }
        $xcontext->list=$list;
        $xcontext->list1=$list1;
        $xcontext->list2=$list2;
        $xcontext->list3=$list3;
        $xcontext->list4=$list4;
        $xcontext->cid=$cid;
        return XNext::useTpl("paper.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
    }
}
//检查试卷
class Action_checkpaper extends XAction
{
    public function _run($request,$xcontext)
    {
       $cid=intval($request->cid);
       $list = $_POST;
       $uname = htmlspecialchars($list['uname']);
       $phone = htmlspecialchars($list['phone']);
       $row=RespondentsSvc::ins()->addp($uname,$phone,$cid);
          if($row){
          	//答卷人的id
          	 $pid = $row;

  	        foreach($list as $k => $v){
        	         $type = substr($k,-2);
			       	if($type == "_2" && is_array($v)){
	  	        	         $num=0;
	  	        	         $xnum=0;
			       	     $tid = intval($k);  
			       	     $asid=htmlspecialchars(implode(",",$v));
   			       	     //检查多选题错对
   			       	     //应该答对的数量
			       	     $row = XDao::query("paper_Query")->checksel2($tid);  
			       	     foreach($v as $k=>$value){
			       	     	//答对的数量
                           $selnum = XDao::query("paper_Query")->checksel3($tid,$value);
                           //达错的数量
                           $selnum1 = XDao::query("paper_Query")->checksel4($tid,$value);

                           $num += $selnum['num']; 
                           $xnum += $selnum1['xnum'];                   
			       	     }	
			       	   
       		       	    //如果多选题错误的数量为0,选中的正确的数量等于应该正确的数量才对
	                       if($xnum == 0 && $num == $row['num1']){
	                       	   $score=4;
	                       }else{
	                       	   $score=0;
	                       }
			       	     $row=ShowpaperSvc::ins()->addpro($tid,$asid,$pid,2,$score);
			        }else if($type == "_5" && is_array($v)){
			        	  $tid = $k;
		        	      $asid = htmlspecialchars(implode("_",$v));
                          $row=ShowpaperSvc::ins()->addpro($tid,$asid,$pid,5,0);
			        }else if($k != "phone" && $k != 'uname'){
	                      //题目的id
				           $tid = $k;
				          //答案的id
				           $asid = htmlspecialchars($v);
				           $type = substr($k,-1);
			           if($type == 1){
			              $tid = str_replace("_1","",$k);
			              //检查单选题错对
			              $rows = XDao::query("paper_Query")->checksel($tid,$asid);
                             $cor=$rows['correct'];
                             if($cor == 1){
                             	 $score=3;
                             }else{
                             	 $score=0;
                             }
			           }else if($type == 3){
			           	  $tid = str_replace("_3","",$k);
  			           	  //检查判断题错对
  			           	  $rows = XDao::query("paper_Query")->checkjuege1($tid);
  			           	    if($asid == $rows['correct']){
                                 $score=2;
  			           	    }else{
	                       	     $score=0;
	                       }
			           }else if($type == 4){
			              $tid = str_replace("_4","",$k);
			              $score=0;
			           }         
			           $row=ShowpaperSvc::ins()->addpro($tid,$asid,$pid,$type,$score);
			        }
 			 }

          }
       return XNext::useTpl("xiaolian.html");
    }
}
//查看答完题的试卷
class Action_respondents extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {

	   //如果有搜索
   	   $info=$request->attr;
       $name=htmlspecialchars($info['name']);
    if(!empty($name)){
       $count=XDao::query("paper_Query")->count1($name);
    }else{
       $count=XDao::query("paper_Query")->count();
    }   
	   $num=$count['num'];//总条数
	   $pagesize=8; //一页显示多少条
	   $page = new Page($num,$pagesize); //实例化
	   $maxpage=$page->checkMaxPage(); //最大页数
	   $nowpage=$page->page; //当前第几页  
	   $fenye=$page->show();
	   $start=intval(explode(",",$page->limit())[0]);
	   $stop=intval(explode(",",$page->limit())[1]);
    if(!empty($name)){
        $rows = XDao::query("paper_Query")->listres1($name,$start,$stop);
    }else{
        $rows = XDao::query("paper_Query")->listres($start,$stop);
    }
        foreach($rows as $k => $v){
        	$list = XDao::query("paper_Query")->listscore($v['id']);
            foreach($list as $key => $value){
               $v['score']=$value['score'];     
               $papername = XDao::query("paper_Query")->listpapername($v['cid']);
               $v['papername']=$papername['categary'];
            }
            $array[]=$v;
        }
       $xcontext->firstpage=$fenye['firstpage'];
       $xcontext->lastpage=$fenye['lastpage'];
       $xcontext->prevpage=$fenye['prevpage'];
       $xcontext->nextpage=$fenye['nextpage'];
       $xcontext->maxpage=$maxpage;//最大页
       $xcontext->nowpage=$nowpage;//当前页
       $xcontext->num=$num;//条数

        $xcontext->list = $array;
        return XNext::useTpl("showpaper.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
    }
}
//查看试卷详情
class Action_showone extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
        $pid = intval($request->id);
        //查出此人做过的题
        $rows = XDao::query("paper_Query")->showonepaper($pid);
        foreach($rows as $k => $v){
        	if($v['type'] == 1){

        		//此人做过的单选题
        		$tid1 = $v['tid'];
                $as1 = $v['asid'];
                $rows1 = XDao::query("paper_Query")->check1($tid1);
            	$rows1['score']=$v['score'];
                $rows1['as1']=XDao::query("paper_Query")->checkas1($as1); 
                $row1[] = $rows1;

        	}else if($v['type'] == 2){

        		//此人做过的多选题
        		$tid2 = $v['tid'];
        		$arr=array();
        		$as2 =explode(",",$v['asid']);
                $rows2 = XDao::query("paper_Query")->check1($tid2);
                	$rows2['score']=$v['score'];
            	foreach($as2 as $key => $value){  	
        		    $ro2=XDao::query("paper_Query")->checkas1($value);	
                    $arr[]=$ro2;
                    $rows2['as3']=$arr;    
                }
	             $row2[]=$rows2;

        	}else if($v['type'] == 3){

        		//此人做过的判断题
        		$tid3 = $v['tid'];
                $rows3 = XDao::query("paper_Query")->checkas3($tid3);
              	    $rows3['score']=$v['score'];
              	    $rows3['asid']=$v['asid'];
                    $row3[]=$rows3;
        	}else if($v['type'] == 4){
        		//此人做过的问答题
        		$tid4 = $v['tid'];
                $rows4 = XDao::query("paper_Query")->checkas4($tid4);
                //问答题的正确答案
                $row = XDao::query("paper_Query")->search4($tid4);
	
	          	$rows4['score']=$v['score'];
	          	$rows4['as4']=$v['asid'];
	          	$rows4['id']=$v['id'];    	  
                $rows4['content']=$row['content'];
               
                $row4[]=$rows4;

        	}else if($v['type'] == 5){

        		//此人做过的填空题
        		$tid5 = $v['tid'];
        		$as5 = explode("_",$v['asid']);
                $rows5 = XDao::query("paper_Query")->checkas5($tid5);
                $pattern = '/\{as\}(.*?)\{\/as\}/';
                for($i=0;$i<count($as5);$i++){
                	$b = "<b style='color:red'>{$as5[$i]}</b>";
		              	  $rows5['score']=$v['score'];
		              	  $rows5['id']=$v['id'];   
		              	  $rows5['as5']=$as5; 
	     	              preg_match($pattern,$rows5['topic'],$result); 
	     	              $b2 = "<b style='color:#69B014'>[答案:{$result[1]}]</b>";
	     	              $rows5['topic']=preg_replace($pattern,$b.$b2,$rows5['topic'],1);  
	                 }            
               $row5[]=$rows5;
               
        	}
        }
         $xcontext->list1=$row1;
         $xcontext->list2=$row2;
         $xcontext->list3=$row3;
         $xcontext->list4=$row4;
         $xcontext->list5=$row5;
         return XNext::useTpl("showanswer.html");
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_VIEW_PAPER);
    }
}

//删除答卷
class Action_cleananswer extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $uid = intval($request->id);
       $row=XDao::dwriter("CleananswerWriter")->cleanas($uid);

       if($row){
       	   $row1=XDao::dwriter("CleananswerWriter")->cleanall($uid);
       	    	echo "yes";
       	    	
       }else{
         echo "no";
       }
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}
//打分
class Action_changescore extends XBaseLoginAction
{
    public function _run($request,$xcontext)
    {
       $tid = intval($request->id);
       $score = htmlspecialchars($request->score);
       $row=ShowpaperSvc::ins()->playscore($tid,$score);
    }
    public function getPermission()
    {
        // 当前Action需要权限类别TYPE_HR中的P_VIEW_KAOQIN权限
        return new Permission(PermissionEnum::TYPE_HR, PermissionEnum::P_EDIT_PAPER);
    }
}