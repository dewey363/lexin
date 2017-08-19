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
<style>
.home_info li em {
	float: left;
	width: 120px;
	font-style: normal;
}
li {
	list-style: none;
}
	/*公告*/
	.notice{width:600px;margin:5px auto;height:26px;overflow:hidden;}
	.noticTipTxt{color:#ff7300;height:22px;line-height:22px;overflow:hidden;margin:0 0 0 40px;}
	.noticTipTxt li{height:22px;line-height:22px;}
	.noticTipTxt a{color:#317eac;font-size:20px;text-decoration:none;}
	.noticTipTxt a:hover{color:#0075E8;text-decoration:underline;}
</style>
</head>
<body>
	<div class="wrap">
		<div class="alert" style="height:25px;background-color: #e3e3e3">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<div class="notice">
				<ul id="notice" class="noticTipTxt">
					<?php if(is_array($notice)): foreach($notice as $key=>$vo): ?><li>
						<a href="<?php echo U('Portal/AdminPage/view',array('id'=>$vo['id']));?>">
							<?php echo ($vo["post_title"]); ?>
						</a>
					</li><?php endforeach; endif; ?>

				</ul>
			</div>
		</div>
		<div id="home_toptip"></div>
		<h4 class="well"><?php echo L('SYSTEM_INFORMATIONS');?></h4>
		<div class="home_info">
			<ul>
				<?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><em><?php echo ($key); ?></em> <span><?php echo ($vo); ?></span></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
		</div>
		<h4 class="well"><?php echo L('INITIATE_TEAM');?></h4>
		<div class="home_info" id="home_devteam">
			<ul>
				<li><em>YinHang</em> <a href="http://www.winlang.com" target="_blank">www.winlang.com</a></li>
				<li><em>核心开发者</em> <span>空灵</span></li>
				<li><em><?php echo L('TEAM_MEMBERS');?></em> <span>空灵</span></li>
				<li><em><?php echo L('CONTACT_EMAIL');?></em> <span>63928427@qq.com</span></li>
			</ul>
		</div>
	</div>
	<script src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/scrolltext.js"></script>
	<script type="text/javascript">
        // 演示一
        if (document.getElementById("notice")) {
            var scrollup = new ScrollText("notice");
            scrollup.LineHeight = 22;        //单排文字滚动的高度
            scrollup.Amount = 1;            //注意:子模块(LineHeight)一定要能整除Amount.
            scrollup.Delay = 20;           //延时
            scrollup.Start();             //文字自动滚动
            scrollup.Direction = "down"; //文字向下滚动
        }
	</script>
</body>
</html>