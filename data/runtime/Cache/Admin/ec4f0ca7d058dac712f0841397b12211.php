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
			<li class="active"><a href="<?php echo U('Admin/Student/index');?>"><?php echo L('STUDENT_USER_INDEX');?></a></li>
			<li><a href="<?php echo U('Admin/Student/add');?>"><?php echo L('STUDENT_USER_ADD');?></a></li>
		</ul>
        <form class="well form-search" method="post" action="<?php echo U('Admin/Student/index');?>">
            关键字:
			<input type="text" name="keyword" style="width: 120px;" value="<?php echo I('request.keyword');?>"  placeholder="<?php echo L('STUDENT_NAME');?>/<?php echo L('STUDENT_MOBILE');?>">
          &nbsp;&nbsp;
			入学日期：
			<input type="text" name="start_time" class="js-datetime" value="<?php echo I('request.start_time','');?>" style="width: 120px;" autocomplete="off">-
			<input type="text" class="js-datetime" name="end_time" value="<?php echo I('request.end_time','');?>" style="width: 120px;" autocomplete="off"> &nbsp; &nbsp;
			<input type="submit" class="btn btn-primary" value="搜索" />
            <a class="btn btn-danger" href="<?php echo U('Admin/Student/index');?>">清空</a>
			<a id="modal-studentExport" href="#studentExport" role="button" class="btn" data-toggle="modal">批量导出</a>
			<a id="modal-studentImport" href="#studentImport" role="button" class="btn" data-toggle="modal">批量导入</a>
		</form>
		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
					<th width="50">
						ID
					</th>
					<th><?php echo L('STUDENT_NAME');?></th>
					<th><?php echo L('STUDENT_SEX');?></th>
					<th><?php echo L('STUDENT_AGE');?></th>
					<th><?php echo L('STUDENT_NUMBER');?></th>
					<th><?php echo L('STUDENT_MOBILE');?></th>
					<th><?php echo L('APPLY_DATE');?></th>
					<th><?php echo L('COURSE_CONSULTANT');?></th>
					<th><?php echo L('ACTIONS');?></th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
					<td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="ids[]" value="<?php echo ($vo["id"]); ?>" title="ID:<?php echo ($vo["id"]); ?>"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["name"]); ?></td>
					<td>
						<?php if($vo["sex"] == 1): ?>男
							<?php else: ?>女<?php endif; ?>
					</td>
					<td><?php echo ($vo["age"]); ?></td>
					<td><?php echo ($vo["number"]); ?></td>
					<td><?php echo ($vo["mobile"]); ?></td>
					<td><?php echo ($vo["apply_date"]); ?></td>
					<td><?php echo ($vo["course_consultant"]); ?></td>
					<td>
						<a href='<?php echo U("Admin/Contract/add",array("stuId"=>$vo["id"]));?>' class="btn btn-mini btn-primary">创建合同</a> |
						<a href='<?php echo U("Admin/Contract/index",array("stuId"=>$vo["id"]));?>' class="btn btn-mini btn-primary">查看合同</a> |
						<a href='<?php echo U("Admin/Student/classConsume",array("stuId"=>$vo["id"]));?>' class="btn btn-mini btn-primary">课时消耗</a> |
						<a href='<?php echo U("Admin/Student/edit",array("id"=>$vo["id"]));?>' class="btn btn-mini btn-primary"><?php echo L('EDIT');?></a> |
						<a class="js-ajax-delete btn btn-mini btn-danger" href="<?php echo U('Admin/Student/delete',array('id'=>$vo['id']));?>"><?php echo L('DELETE');?></a>
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>

	</div>
	<!--批量导出-->
	<div id="studentExport" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">
				导出数据
			</h3>
		</div>
		<form method="post" action="<?php echo U('Admin/Student/export');?>">
		<div class="modal-body">
			<p>
				关键字:
				<input type="text" name="keyword" style="width: 200px;" value="<?php echo I('request.keyword');?>"  placeholder="<?php echo L('STUDENT_NAME');?>/<?php echo L('STUDENT_MOBILE');?>/<?php echo L('CLASS_NAME');?>"><br/><br/>
				剩余课时:
				<input type="text" name="surplus_hour" style="width: 100px;" value="<?php echo I('request.surplus_hour');?>" placeholder="请输入<?php echo L('SURPLUS_HOUR');?>"><br/><br/>
				分班状态：
				<select name="divide_class" style="width: 80px;">
					<option value='0'>全部</option>
					<option value='1'>已分班</option>
					<option value='2'>未分班</option>
				</select><br/><br/>
				入学日期：
				<input type="text" name="start_time" class="js-datetime" value="<?php echo I('request.start_time','');?>" style="width: 120px;" autocomplete="off">-
				<input type="text" class="js-datetime" name="end_time" value="<?php echo I('request.end_time','');?>" style="width: 120px;" autocomplete="off"> &nbsp; &nbsp;

			</p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			<button class="btn btn-primary">导出</button>
		</div>
		</form>

	</div>
	<!--批量导出结束-->
	<!--批量导入开始-->
	<div id="studentImport" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">
				导入数据
			</h3>
		</div>

		<form  method="post" action="<?php echo U('Admin/Student/upload');?>" enctype="multipart/form-data" >
			<div class="modal-body">
				<p>
					选择文件：<input type="file" name="excelData" value=""/>
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
				<button class="btn btn-primary">导入</button>
			</div>
		</form>

	</div>
	<!--批量导入结束-->
	<script src="/public/js/common.js"></script>
</body>
</html>