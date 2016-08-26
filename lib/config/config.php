<?  if (! defined('CALV')) exit('Hey! Who Are You?');

global $site_url;


/*******
THIS IS CONFIGURATION FILE
*******/

$config = [

'Site_URL' => $site_url, //Automatic, CALV is smart :)

'Site_Title' => "My Site",

'Site_Description' => "Just Another Website",

'Default_Language' => "id", //your default language (en,id,jp,de,etc)

'Default_Controller' => "myapp/index", //default loaded controller when not defined


/*******
database connection config
*******/

'DB_Server' => "localhost",
'DB_User' => "root",
'DB_Pass' => "",
'DB_Name' => "db_tes",
'DB_Connection' => "pdo", //pdo or mysqli; for now, use pdo


/*******
misc. config
*******/

'Controller_Path' => "apps/controller", //path for your controller files

'View_Path' => "view",

'Helper_Path' => "apps/helper/", 

'Library_Path' => "apps/library/", 

'Default_StoragePath' => "assets/files", //where default uploaded files store if not defined?

'Your_FavoriteWords' => "DreamDropDistance",

'Table_Admin' => "tesadmin", //table for admin data


/*******
Log and Protection
*******/

'Error_Log' => true, //create error logging or not (true/false)

'CSRF_Guard' => false, //Cross-Site Request Forgery Protection

'CSRF_Exclude' => array(), //Route that will bypasse CSRF protection (eg: for API)

'CSRF_Redir' => false, //redirect CSRF to another route (leave blank or false for default reaction)


/*******
Maintenance config
*******/

'NotFound_Controller' => "", //use blank string or default for set to default 404, controller MUST EXISTS!!

'Maintenance_Mode' => false, //block all access and show a maintenance page (still planed)

'Maintenance_Controller' => "default",  //use blank string or default for set to default 404 (still planed)

];
