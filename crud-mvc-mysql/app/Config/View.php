<?php
  namespace App\Config;

  use Exception;
  class View{
      protected $viewPath;
      protected $viewName;
      protected $data=[];
 
      public function __construct($viewName)
      { 
          $this->viewPath=str_replace("Config","",__DIR__.$this->viewPath);
          $this->viewPath=$this->viewPath.'\\Views\\';
          $this->viewPath=str_replace("\\",DIRECTORY_SEPARATOR,$this->viewPath);
          $this->viewName=str_replace("/","\\",$viewName);
      }
      public function set($key,$value){
        $this->data[$key]=$value;
        return $this;
      }

      public function render(){
        $fullPath=$this->viewPath.'\\'.$this->viewName.'.php';
        if(!file_exists($fullPath)){
          throw new Exception("View file not found : ".$fullPath);
        }
        extract($this->data);
        include($fullPath);

      }
  }
?>