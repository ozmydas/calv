<? namespace apps\controller;

use core\base\controller;

class MyApp extends Controller{

private $table;
private $key;
private $view_dir;

public function __construct(){
parent::__construct();

$this->table = "product";
$this->key = "product_id";
$this->view_dir = "myview";

$this->app_title = "My App Is Me";
$this->app_subtitle = "";
}

public function index(){
$this->all();
} //end func


public function all(){
$data['result'] = $this->crud->read("SELECT * FROM $this->table");

$data['app_title'] = $this->app_title;
$data['app_subtitle'] = $this->app_subtitle;

return $this->template->load($this->view_dir."/all", $data, 1);
} //end func


public function detail($id=null){
$data['result'] = $this->crud->read("SELECT * FROM $this->mytable WHERE $this->key = $id");

return $this->template->load($this->view_dir."/view", $data, 1);
} //end func

public function update(){

}


public function delete($id=null){

} //end func

}
