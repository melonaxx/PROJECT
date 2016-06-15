<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD id=Head1><title>北京外麦王人事系统</title>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<SCRIPT language=javascript src="/js/jquery-1.8.3.min.js"></SCRIPT>
<STYLE type=text/css>BODY {
	PADDING-RIGHT: 0px; PADDING-LEFT: 0px; PADDING-BOTTOM: 0px; MARGIN: 0px; PADDING-TOP: 0px; BACKGROUND-COLOR: #2a8dc8
}
BODY {
	FONT-SIZE: 11px; COLOR: #003366; FONT-FAMILY: Verdana
}
TD {
	FONT-SIZE: 11px; COLOR: #003366; FONT-FAMILY: Verdana
}
DIV {
	FONT-SIZE: 11px; COLOR: #003366; FONT-FAMILY: Verdana
}
P {
	FONT-SIZE: 11px; COLOR: #003366; FONT-FAMILY: Verdana
}
.mainMenu {
	FONT-WEIGHT: bold; FONT-SIZE: 14px; CURSOR: hand; COLOR: #000000
}
A.style2:link {
	PADDING-LEFT: 4px; COLOR: #0055bb; TEXT-DECORATION: none
}
A.style2:visited {
	PADDING-LEFT: 4px; COLOR: #0055bb; TEXT-DECORATION: none
}
A.style2:hover {
	PADDING-LEFT: 4px; COLOR: #ff0000; TEXT-DECORATION: none
}
A.active {
	PADDING-LEFT: 4px; COLOR: #ff0000; TEXT-DECORATION: none
}
.span {
	COLOR: #ff0000
}
#xingji{
  color:red;
  text-decoration:none;
}
</STYLE>

<SCRIPT language=javascript>
		function MenuDisplay(obj)
		{
			if(document.getElementById(obj).style.display=='none')
			{
				document.getElementById(obj).style.display='block';
			}
			else
			{
				document.getElementById(obj).style.display='none';
			}
		}
    </SCRIPT>

<META content="MSHTML 6.00.2900.3492" name=GENERATOR></HEAD>
<BODY>
<TABLE cellSpacing=0 cellPadding=0 width=210 align=center border=0>
  <TBODY>
  <TR>
    <TD width=15><IMG src="/images/new_005.jpg" border=0></TD>
    <TD align=middle width=180 background=/images/new_006.jpg 
      height=35><B>人力资源 －功能菜单</B> </TD>
    <TD width=15><IMG src="/images/new_007.jpg" 
border=0></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=210 align=center border=0>
  <TBODY>
  <TR>
    <TD width=15 background=/images/new_008.jpg></TD>
    <TD vAlign=top width=180 bgColor=#ffffff>
      <TABLE cellSpacing=0 cellPadding=3 width=165 align=center border=0>
        <TBODY>
      {if $smarty.session.auth.P_VIEW_BASIC}
        <TR>
          <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_1');"><SPAN 
            class=span id=table_1Span>＋</SPAN> 员工档案管理
		      </TD>
	     </TR>
        <TR>
          <TD>
            <TABLE id=table_1 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              <TR>
                <TD class=menuSmall><A class=style2 
                  href="peopleinfo.php" 
                  target=dmMain>－ 全部员工</A>
                </TD>
              </TR>
              {foreach from=$list item=rows}
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="peopleinfo.php?section={$rows['id']}" target=dmMain>－ {$rows['class_name']}</A>
                </TD>
                <td><a id="xingji" href="heart.php?cid={$rows['id']}" target=dmMain>(薪酬等级)</a></td>
              </TR>
              {/foreach}
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="changeclass.php" target=dmMain id="addclass">－ 部门管理</A>
                </TD>
              </TR>
              </TBODY>
              </TABLE>
            </TD>
        </TR>
       
      <TR>
          <TD background=/images/new_027.jpg height=1>
		  </TD>
	    </TR>
      {/if}
      {if $smarty.session.auth.P_VIEW_PAY}
        <TR>
          <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_2');"><SPAN 
            class=span id=table_3Span>＋</SPAN> 薪资管理</TD></TR>
        <TR>
          <TD>
            <TABLE id=table_2 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              {foreach from=$list item=rows}
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="show.php?cid={$rows['id']}&bname={$rows['class_name']}" target=dmMain>－ {$rows['class_name']}</A>
                </TD>
              </TR>
              {/foreach}
              <TR>
                <TD class=menuSmall><A class=style2 
                  href="lookslip.php" target=dmMain>－ 
                  薪资查看</A>
			          </TD>
			        </TR>
		      </TBODY>
		    </TABLE>
		  </TD>
	   </TR>
	   

      <TR>
          <TD background=/images/new_027.jpg height=1>
		  </TD>
	    </TR>
      {/if}
      {if $smarty.session.auth.P_VIEW_KAOQIN}
      <TR>
          <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_4');"><SPAN 
            class=span id=table_1Span>＋</SPAN> 考勤
      </TD>
      </TR>
        <TR>
          <TD>
            <TABLE id=table_4 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              {foreach from=$list item=rows}
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="kaoqin.php?cid={$rows['id']}" target=dmMain>－ {$rows['class_name']}</A>
                </TD>
              </TR>
              {/foreach}
              </TBODY>
              </TABLE>
            </TD>
        </TR>      
      <TR>
          <TD background=/images/new_027.jpg height=1>
      </TD>
      </TR>
      {/if}
      {if $smarty.session.auth.P_VIEW_PAPER}
      <TR>
      <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_5');"><SPAN 
            class=span id=table_1Span>＋</SPAN>题库
      </TD>
      </TR>
        <TR>
          <TD>
            <TABLE id=table_5 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              {foreach from=$rows item=row}
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="addquestion.php?cid={$row['id']}&type=1" target=dmMain>－ {$row['categary']}</A>
                </TD>
                <td><A class=style2 href="showpaper.php?cid={$row['id']}" target=_black>－ 试卷</A></td>
              </TR>
              {/foreach}
              <tr>
                <TD class=menuSmall>
                   <A class=style2 href="showcategary.php?cid={$row['id']}" target=dmMain>－ 分类管理</A>
                </TD>
              </tr>
              </TBODY>
              </TABLE>
            </TD>
        </TR>

      <TR>
          <TD background=/images/new_027.jpg height=1>
      </TD>
      </TR>
      <TR>
      <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_6');"><SPAN 
            class=span id=table_1Span>＋</SPAN>试卷管理
      </TD>
      </TR>
        <TR>
          <TD>
            <TABLE id=table_6 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="respondents.php" target=dmMain>－试卷查看</A>
                </TD>
              </TR>
              </TBODY>
              </TABLE>
            </TD>
        </TR>

      <TR>
          <TD background=/images/new_027.jpg height=1>
      </TD>
      </TR>
      {/if}
      {if $smarty.session.auth.P_VIEW_CAPITAL}
      <TR>
      <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_11');"><SPAN 
            class=span id=table_1Span>＋</SPAN>资产管理
      </TD>
      </TR>
        <TR>
          <TD>
            <TABLE id=table_11 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="allcapital.php" target=dmMain>－所有物品</A>
                </TD>
              </TR>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="capital_class.php" target=dmMain>－物品分类</A>
                </TD>
              </TR>
              </TBODY>
              </TABLE>
            </TD>
        </TR>

      <TR>
          <TD background=/images/new_027.jpg height=1>
      </TD>
      </TR>
      {/if}
      <TR>
      <TD style="cursor: pointer;" class=mainMenu onClick="MenuDisplay('table_7');"><SPAN 
            class=span id=table_1Span>＋</SPAN>日志查看
      </TD>
      </TR>
        <TR>
          <TD>
            <TABLE id=table_7 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
            width=155 align=center border=0>
              <TBODY>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="showlog.php?type=1" target=dmMain>－员工档案操作记录</A>
                </TD>
              </TR>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="showlog.php?type=2" target=dmMain>－工资操作记录</A>
                </TD>
              </TR>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="showlog.php?type=3" target=dmMain>－考勤操作记录</A>
                </TD>
              </TR>
              <TR>
                <TD class=menuSmall>
                <A class=style2 href="showlog.php?type=4" target=dmMain>－物品操作记录</A>
                </TD>
              </TR>
              </TBODY>
              </TABLE>
            </TD>
        </TR>

        <TR>
            <TD background=/images/new_027.jpg height=1>
        </TD>
        </TR>
        
        <TR>
          <TD class=mainMenu onClick="MenuDisplay('table_3');" style="cursor: pointer;"><SPAN 
            class=span id=table_3Span>＋</SPAN> 系统管理</TD></TR>
        <TR>
          {if $smarty.session.auth.root == "root"}
            <TD>
              <TABLE id=table_3 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
                  width=155 align=center border=0>
                <TBODY>
                    <TR>
                      <TD class=menuSmall><A class=style2 
                        href="showadmin.php" target=dmMain>－ 用户列表</A>
                      </TD>
                    </TR>
                    <tr>
                      <TD class=menuSmall><A class=style2 
                        href="showrole.php" target=dmMain>－ 角色管理</A>
                      </TD>
                    </tr>
                </TBODY>
            </TABLE>
          </TD>
          {else}
           <TD>
              <TABLE id=table_3 style="DISPLAY: none" cellSpacing=0 cellPadding=2 
                  width=155 align=center border=0>
                <TBODY>
                    <TR>
                      <TD class=menuSmall><A class=style2 
                        href="updatepass.php" target=dmMain>－ 更改密码</A>
                      </TD>
                    </TR>
                </TBODY>
            </TABLE>
          </TD>
          {/if}
        </TR>
                  </TBODY></TABLE></TD>
    <TD width=15 background=/images/new_009.jpg></TD></TR></TBODY></TABLE>
<TABLE cellSpacing=0 cellPadding=0 width=210 align=center border=0>
  <TBODY>
  <TR>
    <TD width=15><IMG src="/images/new_010.jpg" border=0></TD>
    <TD align=middle width=180 background=/images/new_011.jpg 
    height=15></TD>
    <TD width=15><IMG src="/images/new_012.jpg" 
border=0></TD></TR></TBODY></TABLE>
</BODY>

<script>
</script>
</HTML>
