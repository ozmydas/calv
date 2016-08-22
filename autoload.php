<?
 if (! defined('CALV'))
exit('Hey! Who Are You?');

//load for other class
spl_autoload_extensions(".php");
spl_autoload_register('LoadCore');

function LoadCore($classname){
	$classname = strtolower($classname);
	$file = str_replace("\\", "/", "$classname.php");
	
	if (file_exists($file)) :
		require_once $file;
	elseif (file_exists('core/'.$file)) :
		require_once 'core/'.$file;
	endif;

} //end function loadcore


//play with configuration
require_once __DIR__."/lib/config/config.php";
$calv_CFG = $config;
unset($config);
