<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core;

/****
* class request for handling post or get request 
****/

class Request extends Core{

private static $instance;

public function __construct(){

}

private static function get_instance(){
if(!isset(self::$instance)) :
self::$instance = New Request();
endif;
}

public static function all(){
return $request=$_REQUEST;
}


public static function post($keyrequest,$level=NULL){
//self::get_instance();

if(isset($_POST[$keyrequest]))
{ $key=$_POST[$keyrequest]; }
else
{ $key=NULL; $level=0; }

return self::stringconv($key,$level);

}


public static function get($keyrequest,$level=NULL){
//self::get_instance();

if(isset($_GET[$keyrequest]))
{ $key=$_GET[$keyrequest]; }
else
{ $key=NULL; $level=0; }

return self::stringconv($key,$level);
}



/*
VALIDATION
*/

public static function validnull($arrayString){
$defValid = isset($arrayString)? true : false;

foreach($arrayString as $string){
if($string==NULL){$isValid=FALSE;}
else{$isValid=TRUE;}

if($defValid ==  TRUE && $isValid == TRUE){
$defValid=TRUE;}
else{
$defValid=FALSE;}
}

return $defValid;
}


public static function validnum($arrayString){
$defValid = isset($arrayString)? true : false;

foreach ($arrayString as $string) :
	$isValid = is_numeric($string)? true : false;
$defValid = $defValid && $isValid ? true: false;
endforeach;

return $defValid;
}


/*
AJAX DETECTION
*/

public static function is_ajax($redir=null){
self::get_instance();

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === "xmlhttprequest"):
	return true;
else:
	if($redir):
		\url::redir($redir);
		die();
	else:
		return false;
	endif;
endif;
} //end func is ajax



/*
MISC.
*/

private static function stringconv($key,$level=null){

switch($level) :
case 'trim':
$output=trim($key," ");
break;
case 'striptag':
$output=strip_tags($key);
 break;
case 3:
//$output=preg_replace(" ","",(strip_tags($key)));
 break;
case 'html':
$output=htmlspecialchars($key);
 break;
case 'time':
$output=date("Y-m-d", strtotime(str_replace('-','/',$key)));
 break;
case 'serialize':
$output=serialize($key);
 break;
case 'jsonencode':
$output=json_encode($key);
 break;
case 'sha1':
$output=sha1($key);
 break;
case 'passwordhash':
$output=password_hash($key,PASSWORD_BCRYPT,['cost'=>12]);
 break;
default:
$output=$key;
endswitch;

if($output==""){ $output=NULL; }

return $output;
} //end func


}
