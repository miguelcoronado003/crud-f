<?php



namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\RoleModuleModel;
use App\Config\View;

use Exception;

class RoleController
{
  private $data;
  private $model;
  private $idKey;
  private $result;
  private $userApp;
  private $roleModuleModel;
  private $roleModules;

  public function __construct()
  {
    $this->data = [];
    $this->model = new RoleModel();
    $this->data = [];
    $this->userApp=[];
    $this->result = "";
    $this->getModulesRoles();
   
  }
  public function getModulesRoles(){
    $this->roleModuleModel=new RoleModuleModel();
    $this->userApp= $_SESSION[SESSION_APP];
    $userRole= $this->userApp[0]['role_fk'];
    $this->roleModules=$this->roleModuleModel->roleModules($userRole);
  }

  public function index() {
    try {
      $this->result = $this->model->findAll();
      $view = new View('role/index');
      $view->set('title', 'Role Index');
      $view->set('roles', $this->result);
      $view->set('roleModules',  $this->roleModules);
      $view->set('getUser',  $this->userApp);
      $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
     //echo json_encode($this->data);
  }
  public function viewCreate()
  {
    try {

      $view = new View('role/create');
      $view->set('title', 'Role Create');
      $view->set('roleModules',  $this->roleModules);
      $view->set('getUser',  $this->userApp);
      $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    //echo json_encode($this->data);
  }

  public function show()
  {
    try {
      $results = $this->model->findAll();
      $this->data['data'] = $results;
      $this->data['status'] = 200;
      $this->data['message'] = "ok";
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    echo json_encode($this->data);
  }

 
  public function showId(int $id = null)
  {
    try {
      $this->result = $this->model->findId($id);
      $view = new View('role/show');
      $view->set('title', 'Role Show');
      $view->set('role', $this->result);
      $view->set('roleModules',  $this->roleModules);
      $view->set('getUser',  $this->userApp);
      $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
     //echo json_encode($this->data);
  }

  public function edit(int $id = null)
  {
    try {
      $this->result = $this->model->findId($id);
      $view = new View('role/edit');
      $view->set('title', 'Role Edit');
      $view->set('role', $this->result);
      $view->set('roleModules',  $this->roleModules);
      $view->set('getUser',  $this->userApp);
      $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
     //echo json_encode($this->data);
  }
  public function create()
  {
    try {
      $this->result  = $this->model->create($this->getDataModel());
      $this->data['data'] = $this->result;
      $this->data['status'] = 200;
      $this->data['message'] = "ok";
      header("Location: index/");
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    //echo json_encode($this->data);
  }
 

  
  public function update(int $id = null)
  {
    try {
      if (count($this->model->findId($id)) > 0) {
        $this->result  = $this->model->update($this->getDataModel(), $id);
        $this->data['data'] = $this->result;
        $this->data['status'] = 200;
        $this->data['message'] = "ok";

        header("Location: ".URL_CONTROLLER_ROLE);
      } else {
        $this->data['data'] =  [];
        $this->data['status'] = 404;
        $this->data['message'] = "Error: Validate - User record does not exist";
      }
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    //echo json_encode($this->data);
  }
  public function viewDelete(int $id = null)
  {
    try {
        $this->result = $this->model->findId($id);
        $view = new View('role/delete');
        $view->set('title', 'Role Delete');
        $view->set('role', $this->result);
        $view->set('roleModules',  $this->roleModules);
        $view->set('getUser',  $this->userApp);
        $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    // echo json_encode($this->data);
  }

  public function delete(int $id = null)
  {
    try {
        if (count($this->model->findId($id)) > 0) {
          $this->result  = $this->model->delete($id);
          $this->data['data'] = $this->result;
          $this->data['status'] = 200;
          $this->data['message'] = "ok";
          header("Location: ".URL_CONTROLLER_ROLE);
        } else {
          $this->data['data'] =  [];
          $this->data['status'] = 404;
          $this->data['message'] = "Error: Validate - User record does not exist";
        }
    
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    // echo json_encode($this->data);
  }

  private function getDataModel()
  {
    $data_request = json_decode(file_get_contents('php://input'), true);
  
    if ($data_request != NULL) {
      $getModel['role_name'] = empty($data_request['name']) ? '' : $data_request['name'];
    } else {
      $getModel['role_name'] = empty($_REQUEST['name']) ? '' : $_REQUEST['name'];

    }

    return $getModel;
  }
}
