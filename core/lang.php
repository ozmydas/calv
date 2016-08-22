<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core;

Class Lang extends Core {

private static $instance;
private $text;
private $langsel;
private $langnow;


function __construct(){
$this->defaultLang = $this->config('Default_Language');
$this->langsel = isset($_COOKIE['language']) ? $_COOKIE['language'] : $this->defaultLang;
}


private static function get_instance(){
if(!isset(self::$instance)) :
	self::$instance = New Lang();
endif;
}


public static function set($language){
self::get_instance();

if(!file_exists("lib/language/{$language}_lang.php")) :
	$language = self::$instance->defaultLang;
endif;

self::$instance->langsel = $language;
//Session::set('language',$language);
setcookie("language",$language);
}


private function load(){
//$selectedLan = Session::get('language');
$selectedLan = $this->langsel;

if( ! isset($selectedLan) ) :
	$selectedLan = $this->defaultLang;
	$this->set($this->defaultLang);
endif;

$langDir = "lib/language/".$selectedLan."_lang.php";
require($langDir);

$this->langnow = $selectedLan;
return $this->text = $text;
}


public static function say($word){
self::get_instance();

if( ! isset(self::$instance->text) OR (self::$instance->langnow != self::$instance->langsel) ) :
	self::$instance->load();
endif;

$say = (isset(self::$instance->text[$word])) ? self::$instance->text[$word] : "Sorry CALV didn't know what to say";

return $say;
}


}
