<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

class Loader extends BaseCore {

static private $instance;

public function __construct(){
parent::__construct();
$this->helpath=$this->config('Helper_Path');
$this->libpath=rtrim($this->config('Library_Path'), "/");
}


static private function get_instance(){
if( ! isset(self::$instance)):
	self::$instance = New Loader();
endif;
}


static public function helper($loaded='', $path=null){
self::get_instance();

$rpath = isset($path) ? $path : self::$instance->helpath;
$loaded = is_array($loaded) ? $loaded : explode(",", $loaded);
foreach($loaded as $key) :
	$path = rtrim($rpath, "/")."/".$key.".php";
	if( ! file_exists($path)):
	self::$instance->error_report("CALV is Confused : '{$path}' not found");
	endif;
	require_once($path);
endforeach;
}


static public function library($loaded='', $path=null){
self::get_instance();

$rpath = isset($path) ? $path : self::$instance->libpath;
$loaded = is_array($loaded) ? $loaded : explode(",", $loaded);
foreach($loaded as $key) :
	$path = rtrim($rpath, "/")."/".$key.".php";
	if(file_exists($path)):
	self::$instance->error_report("CALV is Confused : '{$path}' not found");
	endif;
	require_once($path);
endforeach;
}


}