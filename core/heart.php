<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

class Heart extends BaseCore {

function __construct($dataRoute){
parent::__construct();

//define route
if($dataRoute==NULL):
	$dataRoute = rtrim($this->config('Default_Controller'),"/")."/" ;
endif;
$dataRoute = rtrim($dataRoute,"/");
$this->dataRoute=explode("/",$dataRoute);

//csrf protection
if($this->config("CSRF_Guard")):
	 $this->check_csrf($dataRoute);
endif;

} //end func construct


/**
* load class-method-parameter-value from url route and execute it
*/

public function live(){
$iR=0;
$iS=sizeof($this->dataRoute)-1;

if($iS<1){ $this->dataRoute[1]=null; }
if($iS>4){ $this->error_page("Go Home, Your'e Drunk"); die(); }

$imaginPath=$this->config('Controller_Path');
$iR=0;

/**
* First, CALV try to Get Controller Name from url
*/

if (file_exists("{$imaginPath}/{$this->dataRoute[$iR]}.php")):
	$this->controllerName = $this->dataRoute[$iR];
	$this->controllerPath=$imaginPath."/".$this->dataRoute[$iR].".php";
elseif (file_exists("{$imaginPath}/{$this->dataRoute[$iR++]}/{$this->dataRoute[$iR]}.php")):
	$this->controllerName = $this->dataRoute[$iR];
	$this->controllerPath=$imaginPath."/".$this->dataRoute[--$iR]."/".$this->dataRoute[++$iR].".php";
else:
	$this->error_page(404);
	die();
endif;

/**
* After that, CALV search the  action Method
*/

if ($iR==$iS OR $this->dataRoute[++$iR] == NULL):
	$this->actionMethod =  'index';
else:
	$this->actionMethod =  $this->dataRoute[$iR++];
endif;


/**
* Next, its time to decide what parameter are passed from url
*/

$paramValue=array(NULL,NULL);
if($iS>1):
	for($par=0;$iR<=$iS;$iR++):
		$paramValue[$par++]=$this->dataRoute[$iR];
	endfor;
endif;

/**
* Checking Class and Action
* If exists, return the class name
* If not, kick user to 404 page
*/

$loadClass = $this->checkClass();

/**
* Last, CALV Execute the correct Class and diplaying requested Controller to user
*/ 

if($paramValue[0]==null):
	$loadClass->{$this->actionMethod}();
elseif($paramValue[1]==null):
	$loadClass->{$this->actionMethod}($paramValue[0]);
else:
	$loadClass->{$this->actionMethod}($paramValue[0], $paramValue[1]);
endif;

} //end function



/*////////////////////////////*/


/**
* method check if class and method are exists or not
*/

private function checkClass(){

$this->controllerName=ucfirst($this->controllerName);
$className="apps\controller\\".$this->controllerName;

//cek if class exists
require_once $this->controllerPath;
if (class_exists($className)):
	$result = New $className;
else:
	$this->error_page(404);
	die();
endif;

if (!method_exists($className,$this->actionMethod)):
	$this->error_page(404);
	die();
endif;

return $result;

} //end function


}
