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
<div class="wrap">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo U('Admin/Contract/index');?>">合同管理</a></li>
		<li class="active"><a href="<?php echo U('Admin/Contract/add',array('stuId'=>$stuId));?>">创建合同</a></li>
		<?php if($stuId > 0): ?><li><a href="<?php echo U('Admin/Student/index');?>"><?php echo L('STUDENT_USER_INDEX');?></a></li>
			<li><a href="<?php echo U('Admin/Student/add');?>"><?php echo L('STUDENT_USER_ADD');?></a></li>
			<li><a href="<?php echo U('Admin/Student/edit',array('id'=>$stuId));?>">编辑学员</a></li><?php endif; ?>
	</ul>
	<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Admin/Contract/add_post');?>">
		<fieldset>
			<h4 class="lighter">
				<i class="icon-hand-right icon-animated-hand-pointer blue"></i>
				<a href="#" data-toggle="modal" class="pink">合同信息</a>
			</h4>
			<div class="row-fluid">
				<div class="span6">
					<table class="table table-bordered">
						<tr>
							<th width="80">学员</th>
							<td>
								<select name="stu_id">
									<option value="0">请选择</option>
									<?php if(is_array($studentList)): foreach($studentList as $key=>$vo): ?><option value="<?php echo ($vo["id"]); ?>" <?php echo ($vo["selected"]); ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; ?>
								</select>
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">合同名称</th>
							<td>
								<input type="text" name="name" placeholder="请输入合同名称">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">合同金额</th>
							<td>
								<input type="text" name="total_price" placeholder="请输入合同金额">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">课时数</th>
							<td>
								<input type="text" name="class_number" placeholder="请输入课时数">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">开始时间</th>
							<td>
								<input class="js-date" type="text" id="input-start-time" placeholder="请输入开始时间" name="start_time">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">结束时间</th>
							<td>
								<input class="js-date" type="text" id="input-end-time" placeholder="请输入结束时间" name="end_time">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">课程名称</th>
							<td>
								<input type="text" name="course" placeholder="请输入课程名称">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="130">自定义消耗课时提醒</th>
							<td>
								<input type="text" name="time_consuming_reminder" placeholder="请输入自定义消耗课时提醒">
								<i class="fa fa-asterisk"></i>
							</td>
						</tr>
						<tr>
							<th width="80">课时卡号</th>
							<td>
								<input type="text" name="card_info" placeholder="请输入课时卡号">
							</td>
						</tr>
						<tr>
							<th width="80">是否分期</th>
							<td>
								<label class="radio"><input type="radio" name="hire_purchase" value="0" >否</label>
								<label class="radio"><input type="radio" name="hire_purchase" value="1" >是</label>
							</td>
						</tr>
						<tr>
							<th width="80">停课</th>
							<td>
								<label class="radio"><input type="radio" name="class_end" value="0" >否</label>
								<label class="radio"><input type="radio" name="class_end" value="1" >是</label>
							</td>
						</tr>
					</table>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('ADD');?></button>
						<a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
					</div>
				</div>
			</div>
			<div class="hr hr-18 hr-double dotted"></div>
		</fieldset>
	</form>
</div>
<script src="/public/js/common.js"></script>
</body>
</html>