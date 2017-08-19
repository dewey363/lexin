<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
	<script type="text/javascript">
	//全局变量
	var GV = {
	    ROOT: "/",
	    WEB_ROOT: "/",
	    JS_ROOT: "public/js/",
	    APP:'<?php echo (MODULE_NAME); ?>'/*当前应用名*/
	};
	</script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
    <script>
    	$(function(){
    		$("[data-toggle='tooltip']").tooltip();
    	});
    </script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Admin/Contract/index');?>">合同管理</a></li>
			<li><a href="<?php echo U('Admin/Contract/add');?>">创建合同</a></li>
		</ul>
        <form class="well form-search" method="post" action="<?php echo U('Admin/Contract/index');?>">
            关键字:
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo I('request.keyword');?>"  placeholder="合同名称">
			剩余课时:
			<input type="text" name="surplus_hour" style="width: 100px;" value="<?php echo I('request.surplus_hour');?>" placeholder="请输入<?php echo L('SURPLUS_HOUR');?>"><br/><br/>
			分班状态：
			<select name="divide_class" style="width: 80px;">
				<option value='0'>全部</option>
				<option value='1'>已分班</option>
				<option value='2'>未分班</option>
			</select>
			创建日期：
			<input type="text" name="start_time" class="js-date" value="<?php echo I('request.start_time','');?>" style="width: 120px;" autocomplete="off">-
			<input type="text" class="js-date" name="end_time" value="<?php echo I('request.end_time','');?>" style="width: 120px;" autocomplete="off"> &nbsp; &nbsp;
			<input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo U('Admin/Contract/index');?>">清空</a>
			<a id="modal-studentExport" href="#studentExport" role="button" class="btn" data-toggle="modal">批量导出</a>
		</form>
		<form class="js-ajax-form" action="" method="post">
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Admin/Contract/suspend',array('suspend'=>1));?>" data-subcheck="true">停课</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('Admin/Contract/suspend',array('unsuspend'=>1));?>" data-subcheck="true">取消停课</button>
				<button class="btn btn-primary btn-small" data-toggle="modal"  data-target="#return_class">
					转班
				</button>

			</div>
		<table class="table table-hover table-bordered" id="sample-table-2">
			<thead>
				<tr>
					<th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
					<th width="50">
						ID
					</th>
					<th>学生姓名</th>
					<th>班级</th>
					<th>合同名称</th>
					<th>合同总金额</th>
					<th>合同单价</th>
					<th>课时</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>合同状态</th>
					<th>操作者</th>
					<th width="200"><?php echo L('ACTIONS');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
					<td><input type="checkbox" class="js-check" data-yid="js-check-y" data-name="check_id" data-xid="js-check-x" name="ids[]" value="<?php echo ($vo["id"]); ?>" title="ID:<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["stu_name"]); ?></td>
					<td><?php echo ($vo["class_name"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
					<td><?php echo ($vo["total_price"]); ?></td>
					<td><?php echo ($vo["price"]); ?></td>
					<td><?php echo ($vo["class_number"]); ?></td>
					<td><?php echo ($vo["start_time"]); ?></td>
					<td><?php echo ($vo["end_time"]); ?></td>
					<td><?php echo ($vo["status"]); ?></td>
					<td><?php echo ($vo["admin_name"]); ?></td>
					<td>
						<a class="btn btn-mini btn-primary" href='<?php echo U("Admin/Contract/view",array("id"=>$vo["id"]));?>'><?php echo L('VIEW');?></a> |
						<a class="btn btn-mini btn-primary" href='<?php echo U("Admin/Contract/edit",array("id"=>$vo["id"]));?>'><?php echo L('EDIT');?></a> |
						<a class="js-ajax-delete btn btn-mini btn-danger" href="<?php echo U('Admin/Contract/delete',array('id'=>$vo['id']));?>"><?php echo L('DELETE');?></a>
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
		</form>
	</div>
	<!--批量导出-->
	<div id="studentExport" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">
				导出数据
			</h3>
		</div>
		<form method="post" action="<?php echo U('Admin/Contract/export');?>">
		<div class="modal-body">
			<p>
			<table class="table table-bordered">
				<tr>
					<th width="80">关键字</th>
					<td>
						<input type="text" name="keyword" style="width: 200px;" value=""  placeholder="合同名称">
					</td>
				</tr>
				<tr>
					<th width="80">创建时间</th>
					<td>
						<input type="text" name="start_time" class="js-date" value="" style="width: 120px;" autocomplete="off">-
						<input type="text" class="js-date" name="end_time" value="" style="width: 120px;" autocomplete="off"> &nbsp; &nbsp;
					</td>
				</tr>
			</table>
			</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			<button class="btn btn-primary">导出</button>
		</div>
		</form>

	</div>
	<!--批量导出结束-->
	<!--学员转班开始-->
	<div id="return_class" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">学员转班</h3>
		</div>
		<div class="modal-body">
			<p>
				<label class="col-sm-2 control-label no-padding-right" for="returnClassList">选择班级</label>
				<select name="class_id" id="returnClassList">
					<option value="0">请选择</option>
					<?php if(is_array($classList)): foreach($classList as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php echo ($vo["selected"]); ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
				</select>
			</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			<button class="btn btn-primary" id="returnClassBtn">保存</button>
		</div>
	</div>
	<!-- 学员转班结束-->
	<script src="/public/js/common.js"></script>
	<script src="/public/js/contract/contract.js"></script>
</body>
</html>