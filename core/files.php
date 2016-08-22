<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

class Files extends BaseCore{

private static $instance;
private $filepath;

function __construct(){
$this->filepath = $this->config('Default_StoragePath');
}

private static function get_instance(){
if(!isset(self::$instance)) :
	self::$instance = New Files;
endif;
}


/**
* Local Upload Handling
**/

public static function upload($file,$extallowed=NULL,$filedir=NULL){
self::get_instance();

if(isset($filedir)) :
$filedir="{$filedir}/";
else :
$filedir=null;
endif;

$fileqty = count($_FILES[$file]['name']);
$extallowed = explode(",",$extallowed);
$filedetail = null;

for($i=0;$i<$fileqty;$i++) : // start for
$file_name = trim($_FILES[$file]['name'][$i]);
$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

if($extallowed==true) :
	if(!in_array($file_ext, $extallowed)){ continue; }
endif;

$file_fname = strtolower(Draw::randomstr(16));
$file_size = $_FILES[$file]['size'][$i];
$file_type = $_FILES[$file]['type'][$i];
$file_tmp = $_FILES[$file]['tmp_name'][$i];
$upload_dir =  self::$instance->filepath."/".$filedir.$file_fname.".".$file_ext;

if(move_uploaded_file($file_tmp,$upload_dir)) :
$filedetail[]=array("name"=>pathinfo($file_name,PATHINFO_FILENAME),"filename"=>$filedir.$file_fname.".".$file_ext, "size"=>$file_size,"type"=>$file_type,"path"=>$upload_dir);
else :
$filedetail[]="upload $file_name error";
endif;

endfor; // end for

$filedetail= ($fileqty>1 )? $filedetail : $filedetail[0];
return $filedetail;
}


/**
* Remote Upload Handling
**/

public static function urlupload($url, $filedir=NULL){
self::get_instance();

if(isset($filedir)) :
$filedir=rtrim($filedir,"/")."/";
else :
$filedir=null;
endif;

$urlprefix = substr($url,0,7);
if(($urlprefix !="http://") || $urlprefix !="https://") :
$url = "http://".$url;
endif;

$filename = basename($url);
$path = self::$instance->filepath."/".$filedir;
$fullpath = $path.$filename;

$tryupload = file_put_contents($fullpath, file_get_contents($url));
if($tryupload){
$filedetail=array("name"=>$filename, "path"=>$fullpath);
}
else{
$filedetail = false;
}

return $filedetail;
} //end func remote up


/**
* Download File Handling
**/

public static function delete($filepath){
self::get_instance();

if(unlink($filepath)){
return true;
}
else{
self::$instance->write_log("files","error deleting file $filepath");
return false;
}
}


/**
* Download File Handling
**/

public static function download($filepath){
//if http
//else local
// cek if file exists
//get file header
//download start
//else return false
}



}