<?
session_start();
ob_start();

define('DS',   DIRECTORY_SEPARATOR);
define('ROOT', __DIR__.DS);
define('CALV', "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

require "autoload.php";

/* 
Real URL Should Like This => yoursitename.com/index.php?route=classcontroller/actionmethod/param1/param2
*/

$dataRoute = Request::get('route');
$site_url = "http://".$_SERVER['HTTP_HOST'].pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);

DEFINE('ROUTE', ltrim($dataRoute, "/"));

// Touch in the Heart
$heyCALV = New Heart($dataRoute);

// And Make it Alive
$heyCALV->live();

// Well Done CALV
