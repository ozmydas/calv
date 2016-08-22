<? if (! defined('CALV')) exit('Hey! Who Are You?');

use core\base\core as BaseCore;

/*******
* WHEN CRUD BECOME MAGIC, IS CALLED MAGICRUD
*******/

class Magicrud extends BaseCore {

public $conn;
private $stmtx;

function __construct($dbname=null) {
parent::__construct();
$this->contype=strtolower($this->config('DB_Connection'));

$this->connect($dbname);
}

private function connect($dbname=null){
if( ! isset($this->conn)) :
	require_once "database.php";
	$conn = New \ConnectionDB();
	$this->conn = $conn->connect($dbname);
endif;
}

/*******
* QUERY READ
*******/

public  function read($sql,$prekey=NULL){ 
$stmt = $this->conn->prepare($sql);

SWITCH($this->contype) :
CASE 'mysqli':
if($stmt === false) :
 	 $this->write_log('database', 'MySQLi Read ERROR: ' . $sql . ' - ' . $this->conn->error, E_USER_ERROR);
	$result =  false;
	break;
endif;

if(isset($prekey)):
$paramtyp = "";

	foreach($prekey as $key=>$value):
	$paramtyp .= (is_int($value))? "i":"s";
	$refkey[$key] = &$prekey[$key];
	endforeach;

array_unshift($refkey, $paramtyp);
call_user_func_array( [$stmt, 'bind_param'], $refkey); //mysqli bind param
endif;//end if isset prekey

	$stmt->execute();
	$result = $this->mysqli_read($stmt);
break;

CASE 'pdo':
try{
	$stmt->execute($prekey);
	$result =  $this->pdo_read($stmt);
} // end try
catch(Exception $e){
$errmsg = $e->getMessage();
$this->write_log('database','PDO Read ERROR : '.$errmsg);
$result =  false;
} //end catch

break;

DEFAULT:
	$result =  false;
endswitch;

return $result;
}


/*******
* QUERY CREATE
*******/

public  function insert($tabel,$arr){
$col=implode(", " , array_keys($arr));
$newValue=array_values($arr);
$nVal=array();
$iV=0;

// convert all values from array to string that match for query
if ($this->contype=='mysqli') :
	foreach($newValue as $newValue) :
	$newValue = mysqli_real_escape_string($this->conn,$newValue);
		$nVal[$iV++]="'".$newValue."'";
	 endforeach;
elseif ($this->contype=='pdo') :
	$preArr=array_keys($arr);
	foreach($preArr as $preValue) :
		$nVal[$iV++]=":{$preValue}";
	endforeach;
else :
	exit('unknown connection type');
endif;

$col=implode(", " , array_keys($arr));
$val=implode(", " , $nVal);

// sql statement begin here
	$sql = "insert into $tabel ($col) VALUES ($val)";
$stmt = $this->conn->prepare($sql);


if($stmt === false) :
	 $this->write_log('database', 'MySQLi Insert ERROR: ' .$sql. ' - ' . $this->conn->error, E_USER_ERROR);
break;
endif;

// if statement format syntax correct, execute sql and insert data into database
$result = $this->exe_query_create($stmt,$arr);

return $result; 
} // end function


/*******
* QUERY UPDATE
*******/

public  function update($table,$arr,$where,$prewhere=NULL){
$statedata=null;
$stateEnd=sizeof($arr)-1;
$statePos=0;

foreach($arr as $key => $value) :
	if($statePos++==$stateEnd) :
		$comma=" ";
	else :
		$comma=", ";
	endif;
	
	$value="?";
	$statedata.="{$key}={$value}".$comma;
endforeach;

$sql="UPDATE $table SET $statedata WHERE $where";
$stmt = $this->conn->prepare($sql);

if($stmt === false) :
	 $this->write_log('database', 'MySQLi Update ERROR: ' .$sql. ' - ' . $this->conn->error, E_USER_ERROR);
	$result= false;
else :
	$result = $this->exe_query_update($stmt,$arr,$prewhere);
endif;

return $result;
}


/*******
* QUERY DELETE
*******/

public  function delete($tabel,$where,$whereval=NULL){
$sql="delete from $tabel where $where";

$stmt = $this->conn->prepare($sql);

if($stmt === false) :
	 $this->write_log('database', 'MySQLi Delete ERROR: ' .$sql. ' - ' . $this->conn->error, E_USER_ERROR);
	$result= false;
else :
	$result = $this->exe_query_delete($stmt,$whereval);
endif;

return $result;
}


/*******
/DOUBLE CONNECTION TYPE
*******/

private  function pdo_read($stmt){
return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}


private  function mysqli_read($stmt){
$tempResult=$stmt->get_result();
return $tempResult->fetch_all(MYSQLI_ASSOC);
}


/*******
* execute query when insert data
*******/

private  function exe_query_create($stmt,$arr){
$exeQuery = FALSE;

if ($this->contype=='mysqli') :
if($stmt === false) :
 	  $this->write_log('database', 'MySQLi Insert ERROR: ' . $sql . ' - ' . $this->conn->error, E_USER_ERROR);
endif;
$exeQuery = $stmt->execute();
endif;

if ($this->contype=='pdo') :
$preParam = array();
	foreach($arr as $preKey=>$preValue) :
	//$preValue=$this->conn->quote($preValue);
	$preParam[':'.$preKey]=$preValue;
	endforeach;

	try {
	$exeQuery = $stmt->execute($preParam);
	}
	catch(PDOException $e){
	$errmsg = $e->getMessage();
	$this->write_log('database', 'PDO Insert ERROR : '.$errmsg);
	}
endif;

if($exeQuery) :
	return TRUE;
else :
	return FALSE;
endif;

} //end fuction


/*******
* execute query when update
*******/

private  function exe_query_update($stmt,$arr,$prewhere){
$exeQuery = FALSE;

if ($this->contype=='mysqli') :

	foreach($arr as $key=>$value) :
		$paramtyp[] = (is_int($value))? "i":"s";
	endforeach;

	if(isset($prewhere)) :
		foreach($prewhere as $key=>$value) :
		$paramtyp[] = (is_int($value))? "i":"s";
		endforeach;
	endif;

$paramtyp=implode("",$paramtyp);
$params[] =& $paramtyp;

	for($i=0, $arr=array_values($arr), $n=count($arr); $i<$n; $i++) :
	$params[] =& $arr[$i];
	endfor;
	for($i=0,$n=count($prewhere);$i<$n;$i++):
	$params[] =& $prewhere[$i];
	endfor;

call_user_func_array(array($stmt,'bind_param'),$params);
$exeQuery = $stmt->execute();
endif;

if ($this->contype=='pdo') :
	foreach($arr as $preKey=>$preValue) :
		$params[]=$preValue;
	endforeach;
	foreach($prewhere as $prewhere) :
		$params[]=$prewhere;
	endforeach;
try {
$exeQuery = $stmt->execute($params);
}
catch(PDOException $e){
$errmsg = $e->getMessage();
$this->write_log('database','PDO Update ERROR : '.$errmsg);
}

endif;

if($exeQuery) :
	return TRUE;
else :
	return FALSE;
endif;
} //end function update



/*******
* execute query when delete
*******/

private  function exe_query_delete($stmt,$whereval){
$exeQuery = FALSE;

if ($this->contype=='mysqli') :

	if(isset($whereval)) :
	foreach($whereval as $key=>$value) :
	$paramtyp[] = (is_int($value))? "i":"s";
	endforeach;
	endif;

$paramtyp=implode("",$paramtyp);
$params[] =& $paramtyp;

	
	for($i=0,$n=count($whereval);$i<$n;$i++) :
	$params[] =& $whereval[$i];
	endfor;

call_user_func_array(array($stmt,'bind_param'),$params);
$exeQuery = $stmt->execute();

endif;

if ($this->contype=='pdo') :
	foreach($whereval as $whereval) :
	$params[]=$whereval;
	endforeach;
try {
$exeQuery = $stmt->execute($params);
}
catch(PDOException $e){
$errmsg = $e->getMessage();
$this->write_log('database','PDO Delete ERROR : '.$errmsg);
}

endif;

if($exeQuery) :
	return TRUE;
else :
	return FALSE;
endif;
} // end delete


/*******
/ Play with Transaction
*******/

public function transaction($data=null){
if($data == false):
	return FALSE;
endif;

$victim = 1;

try{

$this->conn->beginTransaction();

foreach($data as $key => $value):
	$smt = $this->conn->prepare($value[0]);
	$result = $smt->execute($value[1]);

	if($result == true):
		$victim++;
	endif;
endforeach;

$this->conn->commit();
}
catch(Exception $e){
$this->conn->rollBack();
$this->write_log('database','PDO Transaction ('.$victim.'/'.count($data).') ERROR : '.$e->getMessage() );
return FALSE;
}

return TRUE;
} //end transactions


/*******
/ Manual SQL
*******/

public  function  sql_query($sql){
try{
$this->stmtx =  $this->conn->query($sql);
}
catch(Exception $e){
$errmsg = $e->getMessage();
$this->write_log('database','Manual CRUD query ERROR : '.$errmsg);
}
}

public  function sql_prepare($sql){
try {
$this->stmtx =  $this->conn->prepare($sql);
}
catch(Exception $e){
$errmsg = $e->getMessage();
$this->write_log('database','Manual CRUD prepare ERROR : '.$errmsg);
}
}

public  function sql_bindparam($type,$value){
try {
$this->stmtx->bind_param($type,$value);
}
catch(Exception $e){
$errmsg = $e->getMessage();
$this->write_log('database','Manual CRUD bind param ERROR : '.$errmsg);
}
}

public  function sql_execute($param=NULL){
$this->sqlResult = $this->stmtx->execute($param);
}

public  function sql_mysqliaffected(){
return $this->stmtx->affected_rows;
}

public  function sql_result(){
if(!isset($this->stmtx) OR !isset($this->sqlResult)) :
	$result = null;
else :
	SWITCH($this->contype) :
	CASE 'mysqli':
	$result = $this->mysqli_read($this->stmtx);
	break;
	CASE 'pdo':
	$result = $this->pdo_read($this->stmtx);
	break;
	default:
	$result = null;
	endswitch;
endif;

return $result;

}


/***** end sql manual ***/


/*******
/ Close Connection
*******/

public function close(){
	SWITCH($this->contype) :
		CASE 'mysqli':
		$this->conn->close();
		break;
		CASE 'pdo':
		$this->conn=null;
		break;
	endswitch;
} // end close conn


}


/**
taufiqhisyam@2016
MagiCRUD / just a little part of calv mini framework
**/
