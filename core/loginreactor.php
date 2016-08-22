<? if (! defined('CALV')) exit('Hey! Who Are You?');
// LOG!NREACTOR
// The World Most Un-Safest Login System

use core\base\core;

Class LoginReactor extends Core {

private $table;
private $username;
private $email;
private $password;
private $crud;
private $redir_success;
private $redir_fail;

public function __construct(){
parent::__construct();
$this->crud = New \magicrud();
}

/**
* PREPARE PARAMETER
**/

private function slice_data($data){
$this->table = isset($data['table']) ? $data['table'] : $this->config("Table_Admin") ;
$this->username = isset($data['username']) ? strip_tags($data['username']) : null ;
$this->email = isset($data['email']) ? strip_tags($data['email']) : null ;
$this->password = isset($data['password']) ? $data['password'] : null ;
$this->redir_success = isset($data['redir_success']) ? $data['redir_success'] : null ;
$this->redir_fail = isset($data['redir_fail']) ? $data['redir_fail'] : null ;
}

/**
* SIGN-IN PROCCESS
**/

public function signin($data, $usernmail=null){
$this->slice_data($data);

//logical use only username, email, or both
if(($this->username == TRUE) AND ($this->email == FALSE)) :
	$condition = "username = ?";
	$wherekey = [$this->username];
elseif(($this->username == FALSE) AND ($this->email == TRUE)) :
	$condition = "email = ?";
	$wherekey = [$this->password];
elseif(($this->username == TRUE) AND ($this->email == TRUE)) :
	$logical = ($usernmail == TRUE) ? "AND" : "OR";
	$condition = "username = ? ".$logical."email = ?";
	$wherekey = array($this->username, $this->username);
else :
	$condition = "username = ? AND email = ? ";
	$wherekey = array(null, null);
endif;

//retrive data from db
$user = $this->crud->read("SELECT * FROM {$this->table} WHERE ".$condition." LIMIT 1", $wherekey);

if( ! isset($user)) :
	return FALSE;
else :
	$this->signin_passmatch($user=$user[0]);
endif;
} //end func in


private function signin_passmatch($user){
unset($_SESSION['userdata']);
$waktu = time();

	if(password_verify($this->password, $user['password'])) :

//insert login time
	$this->crud->update($this->table, ['logtime'=>$waktu, 'status' => true], "id = ?", ["id"=>$user['id']]);

//set login data to session
	unset($user['password']);
	$_SESSION['logindata'] = $user;
	$_SESSION['logindata']['key'] = sha1($waktu);

	if(isset($this->redir_success)) :
		\url::redir($this->redir_success);
	else :
		return $_SESSION['logindata'];
	endif;
else :
	if(isset($this->redir_fail)) :
		\url::redir($this->redir_fail);
	else :
		return FALSE;
	endif;
endif;

} //end func inmatch


/**
* AUTH PROCCESS
**/

public function auth($data=null){
$this->slice_data($data);

//disable browser cache for previous page
header("Cache-Control: none cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if( ! isset($_SESSION['logindata'])) :
	$result = FALSE;
else :
	$user = $this->crud->read("SELECT logtime FROM ".$this->table." WHERE id=?", array($_SESSION['logindata']['id']) );
	$timekey = isset($user[0]['logtime']) ? sha1($user[0]['logtime']) : null;

//tricky part about login time
	if($user !== FALSE && $_SESSION['logindata']['key'] === $timekey) :
		$result = TRUE;
	else :
		$result = FALSE;
	endif;
endif;


if($result === FALSE) :
	if(isset($this->redir_fail)) :
		\url::redir($this->redir_fail);
	else :
		return $result;
	endif;
else :
	if(isset($this->redir_success)) :
		\url::redir($this->redir_success);
	else :
		return $result;
	endif;
endif;
} //end func auth


/**
* LOG OUT
**/

public function logout($data){
$this->slice_data($data);
$waktu = time();

$result = $this->crud->update($this->table, ['logtime'=>$waktu, 'status' => false], "id = ?", ["id"=>$_SESSION['userdata']['id']]);

if($result === FALSE) :
	if(isset($this->redir_fail)) :
			\url::redir($this->redir_fail);
	else :
		return $result;
	endif;
else :
	unset($_SESSION['logindata']);
	if(isset($this->redir_success)) :
		\url::redir($this->redir_success);
	else :
		return $result;
	endif;
endif;
} //end func out


}


/**
taufiqhisyam@2016
LoginReactor / just a little part of calv mini framework
**/
