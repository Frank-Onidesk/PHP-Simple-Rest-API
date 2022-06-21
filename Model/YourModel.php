<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new DB_Operations('localhost', 'root', '', 'first-project');



require_once "DB.php";


class YourModel extends DB_Operations{

    public function getTargetUser( string $limit) : string{
       parent::DB_Operations(DB_SERVER, DB_USER, '', DB_PASS);
      return  parent::select('SELECT *  FROM products ORDER BY id ASC limit ?', array("i"),  array($limit));

    }
}
