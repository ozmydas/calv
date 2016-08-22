<? namespace apps\controller;

if (! defined('CALV')) exit('Hey! Who Are You?') ;

class Home{

public function __construct(){
$this->template = New \Template;
}


public function index($lan=null){
if(isset($lan)) :
	\lang::set($lan);
endif;

$data['say'] = \lang::say('hello');

$this->template->load('main/home',$data,true);
}


/*********/

public function newspaper(){
$data['hay'] = "hay";
$data['saya'] = "saya";
$data['lipsums'] = 
"Demikian pula, tidak adakah orang yang mencintai atau mengejar atau ingin mengalami penderitaan, bukan semata-mata karena penderitaan itu sendiri, tetapi karena sesekali terjadi keadaan di mana susah-payah dan penderitaan dapat memberikan kepadanya kesenangan yang besar.";

$data['lipsumm'] = 
"Demikian pula, tidak adakah orang yang mencintai atau mengejar atau ingin mengalami penderitaan, bukan semata-mata karena penderitaan itu sendiri, tetapi karena sesekali terjadi keadaan di mana susah-payah dan penderitaan dapat memberikan kepadanya kesenangan yang besar. Sebagai contoh sederhana, siapakah di antara kita yang pernah melakukan pekerjaan fisik yang berat, selain untuk memperoleh manfaat daripadanya?";

$data['lipsuml'] = 
"Demikian pula, tidak adakah orang yang mencintai atau mengejar atau ingin mengalami penderitaan, bukan semata-mata karena penderitaan itu sendiri, tetapi karena sesekali terjadi keadaan di mana susah-payah dan penderitaan dapat memberikan kepadanya kesenangan yang besar. Sebagai contoh sederhana, siapakah di antara kita yang pernah melakukan pekerjaan fisik yang berat, selain untuk memperoleh manfaat daripadanya? Tetapi siapakah yang berhak untuk mencari kesalahan pada diri orang yang memilih untuk menikmati kesenangan yang tidak menimbulkan akibat-akibat yang mengganggu, atau orang yang menghindari penderitaan yang tidak menghasilkan kesenangan?";

$data['titlenews'] = "Judulku Adalah";
$data['moren'] = "Another News on the other page of fury";

$this->template->twig("twig_newspaper", $data);
}


public function signin(){

$data = array(
'table' => 'tesadmin',
'username' => 'master',
'password' => 'master',
'email' => 'tesadmin',
'redir_success' => 'home/admin',
'redir_fail' => ''
);

$this->login->signin($data);

var_dump($_SESSION);

//$data['say'] = "ini adalah halaman about";
//$this->template->load('main/home',$data,true);
}


public function admin(){

$data = array(
'table' => 'tesadmin',
'redir_fail' => 'home/index'
);

$this->login->auth($data);

echo "welcome admin";
}

}
