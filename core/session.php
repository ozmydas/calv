<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core;

class Session extends Core {

private $table1;
private static $instance;

function __construct(){
$this->table1= $this->config('Table_Admin');
}


private static function get_instance(){
if(!isset(self::$instance)) :
self::$instance = New Session();
endif;
}


public static function start(){
return session_start();
}

public static function set($name,$value){
return $_SESSION[$name]=$value;
}

public static function get($name){
if(isset($_SESSION[$name])){
return $session=$_SESSION[$name];
}
else{
return NULL;
}
}


public static function destroy($name=NULL){
if($name==null){
session_destroy();
}
else{
	$authdata=self::get($name);
	if($authdata==NULL){
		return false;
	}else{
		unset($_SESSION[$name]);
	}
}
}


public static function auth($name, $table=NULL){
self::get_instance();

$authdata=Session::get($name);
$tableLogin = ($table==true) ? $table : self::$instance->table1;

if($authdata==NULL) {
	$result = FALSE;
}
else{
	$sid=$authdata[0];
	$sname="$authdata[1]";
	$spass="$authdata[2]";

$chekq="SELECT * FROM $tableLogin WHERE  username=? AND password=? LIMIT 1";
$checkv=array($sname,$spass);

$crud = New Crud;
$logintrue=$crud->read($chekq, $checkv);

	if ($logintrue[0]['username'] ===  $sname ){
		$result = TRUE;
	}
	else { $result = FALSE; }
}

return $result;
}



}
