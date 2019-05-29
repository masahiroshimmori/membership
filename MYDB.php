<?php
function db_connect(){
$db_user = 'root';
$db_pass = '';
$db_host = 'localhost';
$db_name = 'sampledb';
$db_type = 'mysql';

$dsn = "$db_type:host=$db_host;dbname=$db_name;charset=utf8mb4";
try {
  $dbh = new PDO($dsn,$db_user,$db_pass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  //echo '接続しました';
} catch (Exception $e) {
  echo $e->getMessage();
}
return $dbh;
}
 ?>
