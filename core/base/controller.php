<? namespace core\base;

if (! defined('CALV')) exit('Hey! Who Are You?');

Class Controller{

public function __construct(){
$this->crud = New \Magicrud();
$this->template = New \Template();
$this->login = New \Loginreactor();

}

}