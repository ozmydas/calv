<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core;

Class Template extends Core {

private $twigster;
private $viewPath;

function __construct(){
parent::__construct();
$this->site_title = ucfirst($this->config('Site_Title'));
$this->site_desc = $this->config('Site_Description');
$this->viewPath = rtrim($this->config('View_Path'), "/");
}

public function load($file, $data=NULL,$ownVar=NULL){

//ubah menjadi array
$tplateArr = explode("/",$file);

//cek base dan tujuan
$templatelocal = isset($tplateArr[1]) ? $this->viewPath."/base_" :  "view/";
$templatelocal .= $tplateArr[0].".php";

if(!file_exists($templatelocal)){
$this->error_page("CALV is Confused : Template File \"{$file}\" Was Not Found");
}

$path = $templatelocal;

if(file_exists($path)){
	global $site_url; 
	$site_url = $site_url."/"; 
	$page_title = $this->get_page_title();
	$page_desc =$this->site_desc;
	if(isset($tplateArr[1])):
		$page_content = "{$tplateArr[0]}/{$tplateArr[1]}.php";
	endif;

	if ($ownVar!=FALSE){
		foreach ($data as $key => $value){
			${$key} = $value;
		}
	}
	require $path;
}
else{
	$this->error_page("CALV is Confused : Template File \"{$file}\" Was Not Found");
}

} //end function



private function get_page_title(){
$tmptitle = array();

if(isset($_GET['route'])){
$tmptitle = explode("/", $_GET['route']);
}

if(!isset($tmptitle[1])){ $tmptitle[1]=null; }

if(isset($tmptitle[0])){
$tmptitle = ucfirst($tmptitle[1])." ".ucfirst($tmptitle[0])." - ".$this->site_title;
}
else{
$tmptitle = $this->site_title;
}

return $tmptitle;
} //end get page title


public function twig($file=null, $data=null){
if($this->twigster == NULL):

require_once __DIR__."/../vendor/twig/lib/Twig/Autoloader.php";
\Twig_Autoloader::register();

$loader = New \Twig_Loader_Filesystem(__DIR__."/../".$this->viewPath);
$this->twigster =  New \Twig_Environment($loader);
endif;

global $site_url; 
$data['site_url'] = $site_url."/"; 
$data['page_title'] = $this->get_page_title();
$data['page_desc'] =$this->site_desc;

try{
echo $this->twigster->render("{$file}.twig", $data);
}
catch(Exception $e){
$this->error_page($e->getMessage());
}

} //end twig


}
