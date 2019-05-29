<?php
session_start();
require_once("MYDB.php");
$dbh = db_connect();

function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
}

if(isset($_GET['id']) && $_GET['id'] > 0){
  $id = $_GET['id'];
  $_SESSION['id'] = $id;
}else{
  exit('パラメータが不正です。');
}


try {
  $sql = 'select * from member where id = :id';
  $pre = $dbh->prepare($sql);
  $pre->bindValue(':id',$id, PDO::PARAM_INT);
  $pre->execute();
  $count = $pre->rowCount();

} catch (Exception $e) {
  echo "エラー".$e->getMessage();
}


 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>会員情報更新・修正</title>
  </head>
  <body>
    <hr>
      更新画面
    <hr>
      [<a href="list.php">戻る</a>]<br>
    <?php if($count < 1) :?>
      <?php echo "更新データがありません。<br>" ;?>
    <?php else :?>
      <?php $row = $pre->fetch(PDO::FETCH_ASSOC);?>

      <form name="form1" action="list.php" method="post">
      番号：<?php echo h($row['id']);?><br>
      氏：<input type="text" name="last_name" value="<?php echo h($row['last_name']);?>"><br>
      名：<input type="text" name="first_name" value="<?php echo h($row['first_name']);?>"><br>
      年齢：<input type="text" name="age" value="<?php echo h($row['age']);?>"><br>
      <input type="hidden" name="action" value="update">
      <input type="submit" value="更　新">
      </form>
    <?php endif ;?>
  </body>
</html>
