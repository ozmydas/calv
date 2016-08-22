<? namespace core\base;

if (! defined('CALV')) exit('Hey! Who Are You?');

Class Core {

public function __construct(){
//just a dummy text
}


/**
GET CONFIG
**/

protected function config($configName){
global $calv_CFG;

if( ! isset($calv_CFG[$configName])){
return $this->error_report($configName);
}

return $calv_CFG[$configName];
} //end func



/**
ERROR HANDLE
**/

protected function error_report($notif='there was an error'){
die($notif);
} //end func


protected function error_page($code=null){
$template = New \template();

SWITCH($code){
CASE '404':
$errorcode = "$code";
$errormsg = \lang::say("$code");

$notFoundCtr= $this->config('NotFound_Controller');
if( ! ($notFoundCtr=="default" OR $notFoundCtr==="")):
\url::redir($notFoundCtr);
die();
endif;

break;
DEFAULT:
$errorcode = "ERROR";
$errormsg = $code;
}

$data['error_code'] = $errorcode;
$data['error_message'] = $errormsg;

$template->load('main/error', $data, 1);
die();
} //end func


protected function check_baseurl_struct(){
global $site_url;
$baseurl = $site_url;

if(strpos(CALV,"?route=")==true){
$baseurl .= "?route=";
}

return $baseurl;
} // end function



/**
LOG WRITER
**/

protected function write_log($type,$message){
global $site_url;
$message = date("Y/m/d G:i:s")." on ".ROUTE." - ".PHP_EOL.$message.PHP_EOL.PHP_EOL;
file_put_contents("./lib/log/".$type."_notice.txt", $message,FILE_APPEND);
} //end function



/**
CSRF GUARDIAN
**/

protected function check_csrf($route){
if(count($_POST)>=1 AND ! in_array($route, $this->config("CSRF_Exclude"))):
	global $site_url;
	$message = "CSRF Attempt Detected : ";

//detect & process ajax first
if(\request::is_ajax()):
	return true;
endif;

//let the rest begin
	if( ! isset($_POST[\session::get('phantom')] )):
		$this->write_log("attack", $message);
		return die("CALV ERROR : Your Request Untrusted!!");
	endif;

	if( \session::get('token') !== $_POST[\session::get('phantom')] OR ! strpos($_SERVER['HTTP_REFERER'], ltrim($site_url, "http://"))):

		$this->write_log("attack", $message);

		if($this->config("CSRF_Redir") != false):
			return \url::redir($this->config("CSRF_Redir"));
		endif;

		return die("CALV ERROR : Your Request Untrusted!");

	endif;

endif;
} //end function

}  //end class
