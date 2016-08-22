<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core;

class Url extends Core {

private static $instance;

private static function get_instance(){
if(!isset(self::$instance)) :
self::$instance = New Url();
endif;
}


public static function base_url($param=NULL){
self::get_instance();
$baseurl = self::$instance->check_baseurl_struct();

$baseurl .= ltrim(rtrim($param,"/"),"/");

return $baseurl;
}


public static function link($link,$text,$other=NULL,$ntab=NULL){
if($ntab==true){ $target='target="_blank" '; }else{$target='';}

$urlprefix = substr($link,0,7);
if(($urlprefix !="http://") || $urlprefix !="https://"){
$link=self::base_url().$link;
}

return "<a href='$link' ".$other." $target>$text</a>";
}


public static function img($src,$title,$other=NULL){
echo "<img src='$src' title='$title' alt='$title' $other />";
}


public static function redir($location){
self::get_instance();

if ($location=='back'){
echo "<script>history.go(-1);</script>";
}
else{
if(substr($location,0,4)!='http'){
	$baseurl = self::$instance->check_baseurl_struct();
	$location = $baseurl.$location;
}
header("location:$location");
}
} //end function


/**
* misc
**/


}
