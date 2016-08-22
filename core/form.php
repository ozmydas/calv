<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

class Form extends BaseCore {

private static $instance;


private static function get_instance(){
	if(!isset(self::$instance)) :
	self::$instance = New Request();
	endif;
}


public static function start($method=NULL,$action=NULL,$enctype=NULL,$other=NULL){
self::get_instance();

if($enctype==true) :
	$entipe="multipart/form-data";
else :
	$entipe=null;
endif;

$action = self::$instance->check_baseurl_struct().$action;
	echo "<form action='".$action."' method='".$method."' enctype='".$entipe."' ".$other." ><br/>";

if(self::$instance->config("CSRF_Guard")):
	echo self::token_generate();
endif;
} //end func


public static function end(){
	echo "</form><br/>";
}


public static function hidden($name,$value=NULL){
	echo "<input type='hidden' name='".$name."' value='".$value."'/><br/>";
}


public static function text($name,$value=NULL,$placeholder=NULL,$other=NULL){
	echo "<input type='text' name='".$name."' value='".$value."' placeholder='".$placeholder."' ".$other." /><br/>";
}


public static function number($name,$value=NULL,$placeholder=NULL,$other=NULL){
	echo "<input type='number' name='".$name."' value='".$value."' placeholder='".$placeholder."' ".$other." /><br/>";
}


public static function email($name,$value=NULL,$placeholder=NULL,$other=NULL){
	echo "<input type='email' name='".$name."' value='".$value."' placeholder='".$placeholder."' ".$other." /><br/>";
}


public static function pass($name,$value=NULL,$placeholder=NULL,$other=NULL){
	echo "<input type='password' name='".$name."' value='".$value."' placeholder='".$placeholder."' ".$other." /><br/>";
}


public static function radio($name,$choice,$value=NULL,$other=NULL){
	foreach($choice as $radval=>$radtext){
	if($radval==$value){$isChecked="checked";}else{$isChecked=null;}

	echo "<input type='radio' name='".$name."' value='".$radval."' {$other} $isChecked />".$radtext;
}
}


public static function check($name,$value=NULL,$title,$other=NULL){
	$name=$name."[]";
	echo "<input type='checkbox' name='".$name."' value='".$value."' {$other} /> {$title}<br/>";
}


public static function textarea($name,$value=NULL,$placeholder=NULL,$other=NULL){
	echo "<textarea name='".$name."' placeholder='".$placeholder."' {$other} >{$value}</textarea><br/>";	
}


public static function option($name,$option,$default,$other=NULL){
	$selop="<select name={$name} {$other} >";
	foreach($option as $opvalue=>$optext){
	$selected =  ($opvalue==$default) ? "selected" : null;
	$selop.="<option value={$opvalue} $selected>{$optext}</option>";
	}
	$selop.="</select><br/>";
	echo $selop;
}


public static function submit($name,$other=NULL){
	echo "<input type='submit' name='$name' value='$name' $other /><br/>";
}


public static function reset($name,$other=NULL){
	echo "<input type='reset' name='$name' value='$name' $other /><br/>";
}


public static function file($name,$other=NULL){
	echo "<input type='file' name='$name' {$other} /><br/>";
}


public static function br(){
	echo "<br/><br/>";
}


/**
* TOKEN GENERATE
**/

public static function token_generate(){
self::get_instance();

$token1 = substr(md5(time()), 0, 8);

$token2 = sha1(time().self::$instance->config("Your_FavoriteWords"));
	\session::destroy("phantom");
	\session::destroy("token");
	\session::set("phantom", $token1);
	\session::set("token", $token2);

return "<input type='hidden' name='".$token1."' value='".$token2."' />";
}


}
