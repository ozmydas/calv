<?
Class ConnectionDB {

public function connect($dbname=null){
	global $calv_CFG;
	
	$DB_Server = $calv_CFG['DB_Server'];
	$DB_User = $calv_CFG['DB_User'];
	$DB_Pass = $calv_CFG['DB_Pass'];
	$DB_Name = $calv_CFG['DB_Name'];
	$DB_Connection = $calv_CFG['DB_Connection'];

$DB_Name = $dbname!=false ? $dbname : $DB_Name;


SWITCH (strtolower($DB_Connection)){

CASE 'mysqli':
$conn = new mysqli($DB_Server, $DB_User, $DB_Pass, $DB_Name);
if ($conn->connect_error){
	trigger_error("<br/> CALV SAYS : Error while creating  new MYSQLi connection <br/> <br/>".$conn->connect_error, E_USER_ERROR);
}
break;

CASE 'pdo':
try{
$conn = new PDO("mysql:host=$DB_Server;dbname=$DB_Name", $DB_User, $DB_Pass);
//$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOexception $e){
print "CALV SAYS : ERROR while creating new PDO Connection <br/> <br/>  (".$e->getMessage().")";
die();
}
break;

DEFAULT:
exit ("CALV is Confused : 'Unknown Database Connection Type';<br/> Please use rather MySQLi or PDO");
}

return $conn;
} //end func

}
