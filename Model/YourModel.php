<?php


require_once "DB.php";


class ProductsModel extends DB_Operations{

      function __construct(){
      $this->conn  = new  DB_Operations(DB_SERVER, DB_USER, DB_PASS, DB_NAME);  
      }
    
      public function getProducts( ) : string|array{
    
     // return   $this->conn ->  select('SELECT *  FROM products ORDER BY id ASC limit ?', array("i"),  array($limit));
        return $this->conn -> select_result("Select name , price from products limit 5", array("name", "email"));
    }
}

require "../config/database_settings.php";


$model = new ProductsModel;
$products = $model->getProducts();  
echo $products;