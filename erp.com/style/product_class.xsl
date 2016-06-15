<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<link rel="stylesheet" href="http://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css"></link>
<script type="text/javascript" charset="UTF-8" src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="http://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>

<br/>
<!-- 按钮触发模态框 -->
<button class="btn btn-primary btn-primary" data-toggle="modal" 
   data-target="#myModal">
   添加商品分类
</button>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" 
   aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
		<div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" 
               data-dismiss="modal" aria-hidden="true">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            </button>
            <h5 class="modal-title" id="myModalLabel">
               添加商品分类
            </h5>
         </div>
         <div class="modal-body">
            
			  <select class="form-control">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
			  </select>
			
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" 
               data-dismiss="modal">关闭
            </button>
            <button type="button" class="btn btn-primary">
               提交更改
            </button>
         </div>
        </div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>


</xsl:template>

</xsl:stylesheet>
