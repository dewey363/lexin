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
			<li><a href="<?php echo U('AdminPage/index');?>"><?php echo L('PORTAL_ADMINPAGE_INDEX');?></a></li>
			<li><a href="<?php echo U('AdminPage/add');?>"><?php echo L('PORTAL_ADMINPAGE_ADD');?></a></li>
			<li><a href="<?php echo U('AdminPage/edit',array('id'=>$id));?>"><?php echo L('PORTAL_ADMINPAGE_EDIT');?></a></li>
			<li class="active"><a href="#">公告详情</a></li>
		</ul>
			<div class="row-fluid">
				<div class="span9">
					<table class="table table-bordered">
						<tr>
							<th width="80">标题</th>
							<td>
								<?php echo ($post["post_title"]); ?>
							</td>
						</tr>
						<tr>
							<th>摘要</th>
							<td>
								<?php echo ($post["post_excerpt"]); ?>
							</td>
						</tr>
						<tr>
							<th>内容</th>
							<td>
								<?php echo ($post["post_content"]); ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
				<div class="span3">
					<table class="table table-bordered">
						<tr>
							<th>发布时间</th>
							<td>
								<?php echo ($post["post_modified"]); ?>
							</td>
						</tr>
						<tr>
							<th>状态</th>
							<td>
								<?php if($vo["post_status"] == 1): ?>审核通过
									<?php else: ?>
									待审核<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th>所属学校</th>
							<td>
								<?php echo ($post["schoolName"]); ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="form-actions">
				<a class="btn" href="javascript:history.back(-1);"><?php echo L('BACK');?></a>
			</div>
	</div>
</body>
</html>