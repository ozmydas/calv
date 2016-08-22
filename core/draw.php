<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

class Draw extends BaseCore {

private static $instance;
private static $extraTD;
private static $extraIdent;


private static function get_instance(){
if(!isset(self::$instance)) :
	self::$instance = New Draw();
endif;
}


public static function extraTableData($arraystring, $keyIdentifier=NULL){
$td = "";
foreach($arraystring as $key => $value) :
$td .= "<td>".$value."</td>";
endforeach;

self::$extraTD = $td;
self::$extraIdent = $keyIdentifier;
} // end method xtd


public static function table($thead, $tbody, $tother=NULL, $actionTrue=NULL){
$table = "<table class='row-border hover' {$tother} >";
$table .= "<thead><tr>";

foreach ($thead as $tdata) :
	$table .= "<td>{$tdata}</td>";
endforeach;

$table .= "</tr></thead><tbody>";

foreach($tbody as $key) :
	if( $actionTrue==TRUE ) :
		$whereID = $key[$actionTrue];
	endif;
	if( isset(self::$extraIdent) OR self::$extraIdent==TRUE ) :
		$whereIDx= $key[self::$extraIdent];
	endif;

	$table .= "<tr>";

	foreach($key as $key => $value) :
		$table .= "<td>{$value}</td>";
	endforeach;

	if(isset(self::$extraTD)) :
		$tmptable = self::$extraTD;
		$table .= str_replace('this-id',	$whereIDx,$tmptable);
	endif;

	if($actionTrue!=FALSE) :
		$actionuri = rtrim($_SERVER['REQUEST_URI'],"/");
		$table .= "<td style=\"min-width:140px;\"><a href=\"{$actionuri}/edit/{$whereID}\" ><img src=\"assets/images/edit.png\" width=\"16px\" /> edit</a> || <a href=\"{$actionuri}/delete/{$whereID}\" onclick=\"return confirm('Are You Sure?');\"><img src=\"assets/images/delete.png\" width=\"16px\" /> delete</a></td>";
	endif;
	$table .= "</tr>";
endforeach;

$table .= "</tbody></table>";
return $table;
} // end method table



public static function unserialize($string){
return unserialize($string);
}


public static function jsondecode($string){
return json_decode($string);
}


public static function video($video, $width=NULL,$height=NULL,$other=NULL){
$width = ($width == FALSE OR !is_numeric($width) )? '320' : $width;
$height =($height == FALSE OR !is_numeric($width) )? '240' : $height;

$player ="<div style='background:#ddd' $other >";

if(file_exists($video)) :
	$player .= "<video width='$width' height='$height' controls>
	<source src='$video' type='video/ogg' >
	Sorry.. Browser not Supported
	</video>";
else :
	$player .= "<div style='text-align:center;color:red' class='error' >ERROR : Video File not Found!</div>";
endif;

$player .= "</div>";
return $player;
} //end method


public static function audio($audio,$other=NULL){
$player ="<div style='background:#ddd' $other >";

if(file_exists($audio)) :
	$player .= "<audio controls>
	<source src='$audio type='audio/ogg' >
	Sorry.. Browser not Supported
	</audio>";
else :
	$player .= "<div style='text-align:center;color:red' class='error' >ERROR : Audio File not Found!</div>";
endif;

$player .= "</div>";
return $player;
} //end method



public static function pagination($total, $now=NULL, $limit=NULL, $more=NULL){
if($limit==NULL OR $limit=="") {$limit=10;}
$total = ceil($total/$limit);

if(!isset($now) OR !is_numeric($now) OR $now==0) {$now = 1;}

if($now>$total) {$now=$total;}

$url = rtrim($_SERVER['REQUEST_URI'], "/");
$url = explode("/",$url);
if(is_numeric(end($url))) {array_pop($url);}
$url =  implode("/",$url)."/";

$numpre = $now-5;
$numnxt = $now+5;
$numpre10= $now-20;
$numnxt10 = $now+20;
if($numpre<1) {$numpre=1;}
if($numnxt>$total) {$numnxt=$total;}
if($numpre10<1) {$numpre10=1;}
if($numnxt10>$total) {$numnxt10=$total;}

$result = "<ul class='pagination pagination-sm calv-page' >";

if($now>1) :
	$result .= "<li><a href='{$url}1'> First </a></li> <li><a href='{$url}{$numpre}'> &laquo; </a></li>";
else :
	$result .= "<li class='disabled' ><a> First </a></li> <li class='disabled' ><a> &lsaquo; </a></li>";
endif;

for($i=1,$n=$now;$i<=$total;$i++) :
	if( $i<($n-3) OR $i>($n+3) ) :
		if($i<$n) :
			$result .=  "<li><a href='{$url}{$numpre10}'> .. </a></li>";
			$i=$n-4;
		endif;
		if($i>$n) :
			$result .=  "<li><a href='{$url}{$numnxt10}'> .. </a></li>";
			$i=$total;
		endif;

	elseif( $i==$now) :
		$result .=  "<li class='active'><a href='{$url}{$i}'> $i </a></li>";

	else :
		$result .=  "<li><a href='{$url}{$i}'> $i </a></li>";
	endif;
/*
$i++;
}*/
endfor;

if($now<$total) :
	$result .= "<li><a href='{$url}{$numnxt}'> &raquo; </a></li> <li><a href='{$url}{$total}'> Last </a></li>";
else :
	$result .= "<li class='disabled' ><a> &rsaquo; </a></li> <li class='disabled' ><a> Last </a></li>";
endif;

$result .= "</ul>";
return $result;
} //end method pagination



public static function randomstr($length,$variation=null,$customstr=null){
$chardefault = "01234qwertyuiopasdfghjklzxcvbnm56789";

SWITCH ($variation) :
	CASE 'number':
	$char = "0123456789";
	break;
	CASE 'alphabet':
	$char = "qwertyuiopasdfghjklzxcvbnm";
	break;
	CASE 'mixed':
	$char = "0123456789qwertyuiopasdfghjklzxcvbnm@#$&-_+=!*()<>?\/|";
	break;
	CASE 'custom':
	$char = ($customstr==true)? $customstr : $chardefault;
	break;
	DEFAULT:
	$char = $chardefault;
endswitch;

$range=strlen($char)-1;
$generated = "";

for($i=0;$i<$length;$i++) :
$randomnum = rand(0,$range);
$case = rand(0,1);
$tmpchar = $char[$randomnum];
if($case==1) {$tmpchar=strtoupper($tmpchar);}
$generated .= $tmpchar;
endfor;

return $generated;
} // method random string generator


public static function money($number, $symbol=null){
$result = strrev(implode(".",str_split(strrev($number),3)));

if($symbol==true) :
	$result = ucfirst($symbol).". ".$result;
endif;

return $result;
} //end money currency format



}
