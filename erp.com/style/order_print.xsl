<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<!-- UTF8 编码 -->

	<xsl:include href="/style/header.xsl" />

	<xsl:template name="text">

		<link rel="stylesheet" href="/css/order.css"></link>


		<section class="order-section-container">

			<ol class="breadcrumb">
				<li><a href="#">ERP</a></li>
				<li><a href="#">订单</a></li>
				<li class="active">打印发货</li>
			</ol>
			<form class="form-horizontal">
				<div class="well well-lg">
					<div class="form-group">

						<label for="inputEmail3" class="col-sm-1 control-label">下单时间</label>
						<div class="col-sm-2">
							<select class="form-control">
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
							</select>
						</div>
						<div class="col-sm-1">
							<input class="btn btn-primary" type="button" value="查询订单"></input>
						</div>

					</div>

				</div>
			</form>

			<div>
				<h4>订单列表</h4>
				<div>

					<table class="table table-bordered">

						<thead>
							<tr>
								<th>
									店铺名称
								</th>
								<th>
									订单时间段
								</th>
								<th>
									订单量
								</th>
								<th>
									打印时间
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>January</td>
								<td>$100</td>
								<td>January</td>
								<td>$100</td>
							</tr>
							<tr>
								<td>February</td>
								<td>$80</td>
								<td>January</td>
								<td>$100</td>
							</tr>
						</tbody>

					</table>
					<nav>
						<ul class="pagination">
							<li>
								<a href="#" aria-label="Previous">
									<span aria-hidden="true">《</span>
								</a>
							</li>
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li>
								<a href="#" aria-label="Next">
									<span aria-hidden="true">》</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>

			</div>
		</section>

	</xsl:template>
</xsl:stylesheet>
