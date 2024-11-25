<?php



namespace App\Controllers;

use App\Models\RoleModuleModel;

use Exception;

class RoleModuleController
{
  private $data;
  private $model;
  private $idKey;

  public function __construct()
  {
    $this->data = [];
    $this->model = new RoleModuleModel();
   
  }

  public function index() {}
 
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
      if (!empty($id)) {
        $results = $this->model->findId($id);
        $this->data['data'] = $results;
        $this->data['status'] = 200;
        $this->data['message'] = "ok";
      } else {
        $this->data['data'] = [];
        $this->data['status'] = 404;
        $this->data['message'] = "Error Id";
      }
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = "Error: " . $e->getMessage();
    }
    echo json_encode($this->data);
  }

  public function create()
  {
    try {
      $results = $this->model->create($this->getDataModel());
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

  public function update(int $id = null)
  {
    try {
      if (count($this->model->findId($id)) > 0) {
        $results = $this->model->update($this->getDataModel(), $id);
        $this->data['data'] = $results;
        $this->data['status'] = 200;
        $this->data['message'] = "ok";
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
    echo json_encode($this->data);
  }


  public function delete(int $id = null)
  {
    try {

      if (count($this->model->findId($id)) > 0) {
        $results = $this->model->delete($id);
        $this->data['data'] = $results;
        $this->data['status'] = 200;
        $this->data['message'] = "ok";
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
    echo json_encode($this->data);
  }

  private function getDataModel()
  {
    $data_request = json_decode(file_get_contents('php://input'), true);
    $getModel['role_fk'] = empty($data_request['role']) ? '' : $data_request['role'];
    $getModel['module_fk'] = empty($data_request['module']) ? '' : $data_request['module'];
    return $getModel;
  }
}