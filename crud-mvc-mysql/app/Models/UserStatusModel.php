<?php

namespace App\Models;

use App\Config\ConnectDB;
use Exception;
use PDO;
class UserStatusModel
{

  private $conn;
  private $data;
  private $sql;
  private $pdo;
  private $modelData;
  private $primaryKey;
  
  public function __construct()
  {
    $this->data = [];
    $this->modelData = ['userStatus_name'];
    $this->primaryKey = 'userStatus_id';
  }

  
  public function findAll()
  {
    try {
      $this->conn = new ConnectDB();
      $this->pdo = $this->conn->connect();
      $this->sql = "SELECT * FROM userstatus";
      $result = $this->pdo->prepare($this->sql);
      $result->execute();
      $results = $result->fetchAll(PDO::FETCH_ASSOC);
      $this->data = $results;
    } catch (Exception $e) {
      $this->data = [];
      $this->data['status'] = 404;
      $this->data['message'] = $e->getMessage();
    }
    return $this->data;
  }

 
  public function findId(int $id):Array
  {
    try {
      $this->conn = new ConnectDB();
      $this->pdo = $this->conn->connect();
      $this->sql = "SELECT * FROM userstatus WHERE $this->primaryKey ={$id}";
      $result = $this->pdo->prepare($this->sql);
      $result->execute();
      $results = $result->fetchAll(PDO::FETCH_ASSOC);
      $this->data = $results;
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = $e->getMessage();
    }
    return $this->data;
  }

  
  public function create(array $role):Array
  {
    try {
      if ($this->validateModel($role)) {
        $this->conn = new ConnectDB();
        $this->pdo = $this->conn->connect();
        $this->sql = "INSERT INTO userstatus(userStatus_name) VALUES (?)";
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->bindParam(1, $role[$this->modelData[0]]);
        $stmt->execute();
        $last_id=$this->pdo->lastInsertId();
        $this->data['newId'] =  $last_id;
      } else {
        $this->data['data'] = [];
        $this->data['status'] = 404;
        $this->data['message'] = 'Error';
      }
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = $e->getMessage();
    }
    return $this->data;
  }


  public function update(array $role, int $id):Array
  {
    try {
      if ($this->validateModel($role)) {
        $this->conn = new ConnectDB();
        $this->pdo = $this->conn->connect();
        $this->sql = "UPDATE userstatus SET userStatus_name=? WHERE  $this->primaryKey=?";
        $stmt = $this->pdo->prepare($this->sql);
        $stmt->bindParam(1, $role[$this->modelData[0]]);
        $stmt->bindParam(2, $id);
        $stmt->execute();
        $this->data['updateId'] = $id;
      } else {
        $this->data['data'] = [];
        $this->data['status'] = 404;
        $this->data['message'] = 'Error';
      }
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = $e->getMessage();
    }
    return $this->data;
  }

  public function delete(int $id):Array
  {
    try {
      $this->conn = new ConnectDB();
      $this->pdo = $this->conn->connect();
      $this->sql = "DELETE FROM userstatus WHERE  $this->primaryKey=?";
      $stmt = $this->pdo->prepare($this->sql);
      $stmt->bindParam(1, $id);
      $stmt->execute();
      $this->data['deleteId'] = $id;
    } catch (Exception $e) {
      $this->data['data'] = [];
      $this->data['status'] = 404;
      $this->data['message'] = $e->getMessage();
    }
    return $this->data;
  }
 
  private function validateModel($array):Bool
  {
    $validate = true;
    for ($i = 0; $i < count($array); $i++) {
      if (!empty($role[$this->modelData[$i]])) {
        $validate = false;
        break;
      }
    }
    return $validate;
  }
}
