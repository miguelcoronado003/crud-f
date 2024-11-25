<?php


namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModuleModel;
use App\Models\UserStatusModel;
use App\Models\RoleModel;
use App\Config\View;

use Exception;

class UserController
{
  private $data;
  private $model;
  private $roleModel;
  private $roleModuleModel;
  private $roleModules;
  private $statusModel;
  private $result;
  private $userApp;

 
  public function __construct()
  {
    $this->data = [];
    $this->userApp=[];
    $this->model = new UserModel();
    $this->statusModel = new UserStatusModel();
    $this->roleModel = new RoleModel();
    $this->result = "";
    $this->getModulesRoles();
  }

  public function index()
  {
    try {
      $this->result = $this->model->findAll();
      $view = new View('user/index');
      $view->set('title', 'User Index');
      $view->set('users', $this->result);
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
  public function getModulesRoles(){
    $this->roleModuleModel=new RoleModuleModel();
    $this->userApp= $_SESSION[SESSION_APP];
    $userRole= $this->userApp[0]['role_fk'];
    $this->roleModules=$this->roleModuleModel->roleModules($userRole);
  }

  public function showId(int $id = null)
  {
    try {
      $this->result = $this->model->findId($id);
      $view = new View('user/show');
      $view->set('title', 'User Show');
      $view->set('user', $this->result);
      $view->set('roles', $this->roleModel->findAll());
      $view->set('status', $this->statusModel->findAll());
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
      $view = new View('user/edit');
      $view->set('title', 'User Edit');
      $view->set('user', $this->result);
      $view->set('roles', $this->roleModel->findAll());
      $view->set('status', $this->statusModel->findAll());
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
      $this->result = $this->model->findAll();
      $this->data['data'] = $this->result;
      $this->data['status'] = 200;
      $this->data['message'] = "ok";
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

  public function viewCreate()
  {
    try {

      $view = new View('user/create');
      $view->set('title', 'User Create');
      $view->set('roles', $this->roleModel->findAll());
      $view->set('status', $this->statusModel->findAll());
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

 

  public function update(int $id = null)
  {
    try {
      if (count($this->model->findId($id)) > 0) {
        $this->result  = $this->model->update($this->getDataModel(), $id);
        $this->data['data'] = $this->result;
        $this->data['status'] = 200;
        $this->data['message'] = "ok";

        header("Location: ".URL_CONTROLLER_USER);
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


  public function delete(int $id = null)
  {
    try {
        if (count($this->model->findId($id)) > 0) {
          $this->result  = $this->model->delete($id);
          $this->data['data'] = $this->result;
          $this->data['status'] = 200;
          $this->data['message'] = "ok";
          header("Location: ".URL_CONTROLLER_USER);
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


  public function viewDelete(int $id = null)
  {
    try {
        $this->result = $this->model->findId($id);
        $view = new View('user/delete');
        $view->set('title', 'User Delete');
        $view->set('user', $this->result);
        $view->set('roles', $this->roleModel->findAll());
        $view->set('status', $this->statusModel->findAll());
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

  private function getDataModel()
  {
    $data_request = json_decode(file_get_contents('php://input'), true);
  
    if ($data_request != NULL) {
      $getModel['user_user'] = empty($data_request['user']) ? '' : $data_request['user'];
      $getModel['user_password'] = empty($data_request['password']) ? '' : $data_request['password'];
      $getModel['userStatus_fk'] = $data_request['status'];
      $getModel['role_fk'] = $data_request['role'];
    } else {
      $getModel['user_user'] = empty($_REQUEST['user']) ? '' : $_REQUEST['user'];
      $getModel['user_password'] = empty($_REQUEST['password']) ? '' : $_REQUEST['password'];
      $getModel['userStatus_fk'] = $_REQUEST['status'];
      $getModel['role_fk'] = $_REQUEST['role'];
    }

    return $getModel;
  }
}
