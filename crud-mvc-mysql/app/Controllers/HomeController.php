<?php


namespace App\Controllers;


use App\Config\View;
use App\Models\RoleModuleModel;

use Exception;

class HomeController
{
  private $data;
  private $model;
  private $roleModuleModel;
  private $userApp;
  private $roleModules;
  private $result;


  public function __construct()
  {
    $this->data = [];
    $this->result = "";
    $this->userApp=[];
    $this->roleModules=[];
    $this->getModulesRoles();
  }
  public function getModulesRoles(){
    $this->roleModuleModel=new RoleModuleModel();
    $this->userApp= $_SESSION[SESSION_APP];
    $userRole= $this->userApp[0]['role_fk'];
    $this->roleModules=$this->roleModuleModel->roleModules($userRole);
  }

  public function dashboard()
  {
    try {
    
      $view = new View('home/index');
      $view->set('title', 'Home Dashboard');
      $view->set('getUser',  $this->userApp);
      $view->set('roleModules',  $this->roleModules);
      $view->set('title', 'Home Dashboard');
      $view->render();
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
     //echo json_encode($this->data);
  }

}
