<?  if (! defined('CALV')) exit('Hey! Who Are You?') ?>

<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=$page_title;?></title>
<link rel="stylesheet" type="text/css" href="<?=$site_url;?>assets/css/mycss.css">
<link rel="stylesheet"type="text/css" href="<?=$site_url;?>assets/css/bootstrap.css"/>
<link rel="stylesheet"type="text/css" href="<?=$site_url;?>assets/css/datatables.css"/>

<script type="text/javascript" src="<?=$site_url;?>assets/js/jquery3.min.js" ></script>
<script type="text/javascript" src="<?=$site_url;?>assets/js/bootstrap.js" ></script>
<script type="text/javascript" src="<?=$site_url;?>assets/js/datatables.js" ></script>

<style>
body{
width: 100%;
padding:0;
margin:0;
}

header > a{
color:#333;text-decoration:none;
}
</style>

</head>

<body>

<header>
	<a href="./"><?=$app_title?></a> 
	<div><?=$app_subtitle?></div>
</header>

<div class="content">
		<? require $page_content;?>
</div>

<footer>
	<h4>Powered by <a href="http://github/ozmydas/calv/">CALV mini Framework</a></h4>
</footer>

</body>
</html>

<script>
$(document).ready(function(){

$('#datatabel').DataTable();

});
</script>

