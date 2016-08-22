<? if( ! DEFINED('CALV')){ die('hey! who are you?');}

use core\base\core;

class System extends Core{

private static $instance;
private static $crud; 

public function __construct(){
parent::__construct();
}


private static function get_instance(){
if( ! self::$instance):
	self::$instance = New System();
endif;
}


public static function count_visitor(){
if(request::is_ajax()){ return false; }

self::get_instance();
self::$crud = ( ! self::$crud) ? New Magicrud() : self::$crud;

//get ip user and many more
if( ! empty($_SERVER['HTTP_CLIENT_IP'])):
$ip = $_SERVER['HTTP_CLIENT_IP'];
elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'])):
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
else:
$ip = $_SERVER['REMOTE_ADDR'];
endif;
$port = $_SERVER['REMOTE_PORT'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$date = date('Y-m-d');
$time = date('Y-m-d h:i:s');

//cek db
$data = self::$crud->read("SELECT * FROM calv_visitor WHERE ip = ? AND date = ?", [$ip, $date]);

if (count($data)): //if exists update times 
return self::$crud->update('calv_visitor', ['ua' => $ua, 'times' => $data[0]['times']+1, 'last_visit' => $time], "ip = ? AND date = ?", [$ip, $date]);
else: //if no exist, create
return self::$crud->insert('calv_visitor', [ 'date' => $date, 'ip'=>$ip, 'port' => $port, 'ua' => $ua, 'first_visit' => $time, 'last_visit' => $time]);
endif;
} //end func


}
