<? namespace apps\controller;

use core\base\controller;

class MyApp extends Controller{

private $table;
private $key;
private $view_dir;

public function __construct(){
parent::__construct();

$this->app_title = "My App Is Me";
$this->app_subtitle = "";
$this->table = "product";
$this->key = "product_id";
$this->view_dir = "myview";
}

public function index(){
$data['app_title'] = $this->app_title;
$data['app_subtitle'] = $this->app_subtitle;
$data['result'] = $this->all();

return $this->template->load($this->view_dir."/all", $data, 1);
} //end func


public function all(){
$data['result'] = $this->crud->read("SELECT * FROM $this->table ORDER BY product_name");

//echo json_encode($data['result']);
return $data['result'];
} //end func


public function detail($id=null){
$data['result'] = $this->crud->read("SELECT * FROM $this->table WHERE $this->key = ? LIMIT 1", [$id]);

echo json_encode($data['result'][0]);
//return $this->template->load($this->view_dir."/view", $data, 1);
} //end func


public function save(){
unset($_POST[$this->key]);
unset($_POST['submit']);
$this->crud->insert($this->table, $_POST);

\url::redir('myapp');
//echo json_encode($_POST);
}


public function update($id){
unset($_POST[$this->key]);
unset($_POST['submit']);
$this->crud->update($this->table, $_POST, "$this->key = ?", [$id]);

\url::redir('myapp');
//echo json_encode($_POST);
}


public function delete($id=null){

} //end func

}
