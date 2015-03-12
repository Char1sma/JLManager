<?php
/**
 * JLManager
 *
 * @copyright  Copyright (c) 2015 JackyLieu http://github.com/JackyLieu
 * @license    GNU General Public License 3.0
 * @version    0.1beta
 * 目前不支持PHP5.3以下
 */
	define("__PASSWORD__","1");
	session_start();
	global $base_dir,$post_dir;
	global $logged;
	$base_dir=__DIR__;
	function get_filename() {
		//取当前文件名
		$url = $_SERVER['PHP_SELF'];
		return substr($url,strrpos($url,'/')+1);
	}
	/* __DIR__是PHP5.3新增的魔术语法，代表当前文件所在路径 */
	if(isset($_POST['password'])) {
		//判断是否提交了密码
		if ($_POST['password'] == __PASSWORD__) {
			$_SESSION['logged'] = "yes";
		}
	}
	if (isset($_GET['dir'])) {
		//如果含有GET参数dir则获取
		$post_dir = $_GET['dir'];
	}
	if (isset($_GET['logout'])) {
		session_destroy();
		header("Location: ".get_filename());
	}
	$logged = false;
	if (isset($_SESSION['logged'])) {
		//已登录
		$logged = true;
	}
?>
<html>
<head>
	<title><?php echo ($logged ? "简单文件管理" : "请登录"); ?></title>
	<meta charset="gbk">
	<style type="text/css">
	<!--
		body {margin:0;background:#79cdcd;}
		.floater {float:left;height:50%;width:1px /*解决垂直居中*/}
		.login_form {clear:both;min-width:220px;margin:auto;padding:0}
		.login_meta {float:left;margin:0;padding:5px}
		.login_btn {float:right;margin:0;padding:5px}
		.nav {width:100%;position:fixed;top:-3px;}
		.nav .menu {list-style:none;width:174px;height:30px;margin:auto;padding-left:0;padding-top:4px;background:#7cffcc;border:3px #006699 solid;}
		.nav .menu li {float:left;width:48px;margin:0 5px;text-align:center;background:transparent;font-size:1.3em}
		.nav .menu li a {text-decoration:none;}
		.nav .menu li a:hover {text-decoration:underline;}
		.file_manager {background:#ccc;width:98%;max-width:480px;height:100%;padding:5px;margin:auto;border:#00cccc 3px dashed;}
		@media screen and (min-width:480px) {
			body {margin:2%}
			.floater {margin-bottom:-30px}
			.login_form {height:60px;width:75%;max-width:460px}
			.login_meta {width:65%;height:40px;padding:5px}
			.login_meta input {width:99%;height:38px}
			.login_btn {wdith:30%;height:50px}
			.login_btn input {margin:0;width:60px;max-width:100%;height:38px}
		}
		@media screen and (min-width:320px) and (max-width: 479px) {
			body {margin:10px}
			.floater {margin-bottom:-30px}
			.login_form {height:60px;width:75%}
			.login_meta {width:65%;height:40px;padding:5px}
			.login_meta input {width:99%;height:38px}
			.login_btn {wdith:30%;height:50px}
			.login_btn input {margin:0;width:60px;max-width:100%;height:38px}
		}
		@media screen and (max-width: 319px) {
			.floater {margin-bottom:-20px}
			.login_form {width:40%}
			.login_meta {margin:0;width:70%;height:30px}
			.login_meta input {width:99%;height:28px}
			.login_btn {margin:0;wdith:10%;height:30px}
			.login_btn input {margin:0;height:28px}
		}
	-->
	</style>
<?php if ($logged) { ?>
		<div class="nav">
			<ul class="menu">
				<li class="home"><a href="<?php echo $_SERVER['PHP_SELF']; ?>">主页</a></li>
				<li class="refresh"><a href="<?php echo $_SERVER['PHP_SELF']."?dir=".$post_dir; ?>">刷新</a></li>
				<li class="exit"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?logout=true">退出</a></li>
			</ul>
		</div>
<?php } ?>
</head>
<body>
<?php
	if (!$logged) {
	//没登录就显示登录Form
?>
<div class="floater"></div>
<div class="login_form">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<!-- 避免更改文件名导致不能登录 -->
		<div class="login_meta"><input type="password" name="password"></div>
		<div class="login_btn"><input type="submit" value="登录" /></div>
	</form>
</div>
<?php 
	} else if ($logged) {
	//登录了就显示文件管理
?>
	<div class="file_manager">
	<table>
	<tbody>
		<tr>
			<td width="100%">文件（夹）名</td>
		</tr>
<?php
		$fileList = array();
		$fileList = scandir($base_dir.$post_dir);
		foreach($fileList as $temp) {
			if ($temp == ".") continue;
			//跳过没用的"."目录
			switch (is_dir(str_replace("/","\\",$base_dir).str_replace("/","\\",$post_dir)."\\".$temp)) {
				case true:
				if ($temp == "..") {
					$temp = dirname($post_dir);
					$temp = "<a href=\"".get_filename()."?dir=".$temp."\">../</a>";
					break;
				}
				$temp = "<a href=\"".get_filename()."?dir=".str_replace("\\\\","\\",$post_dir."\\".$temp)."\">$temp</a>";
				//修正多添加上的"/"
				break;
				case false;
				$temp = "<p>".$temp."</p>";
				break;
			}
			echo "<tr>
					<td>".$temp."</td>
				  </tr>";
		}
	}
?>	</tbody>
	</table>
</body>
	