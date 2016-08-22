<?  if (! defined('CALV')) exit('Hey! Who Are You?') ?>

<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=$page_title;?></title>
<link rel="stylesheet" type="text/css" href="<?=$site_url;?>assets/css/base.css">
<link rel="stylesheet"type="text/css" href="<?=$site_url;?>assets/css/bootstrap.css"/>
<script type="text/javascript" src="<?=$site_url;?>assets/js/jquery.min.js" ></script>
<script type="text/javascript" src="<?=$site_url;?>assets/js/bootstrap.js" ></script>

<style>
header > a{
color:#333;text-decoration:none;
}
</style>

</head>

<body>

<header>
	<a href="./">CALV</a> 
	<div>mini Framework</div>
</header>

<div class="content">
		<? require $page_content;?>
</div>

<footer>
	<h4>Powered by <a href="http://narugacu.ga/calv/">CALV mini Framework</a></h4>
	<div>
<span id="l"><a href="http://fb.com/taufiqhisyam">TaufiqHisyam</a></span> <span id="r">©2015-2016</span>
</div>
</footer>

</body>
</html>
