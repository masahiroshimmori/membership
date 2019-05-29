<?php
session_start();
require_once('MYDB.php');
$dbh = db_connect();

function h($s){
  return htmlspecialchars($s,ENT_QUOTES,'UTF-8');
}
//削除の処理
if(isset($_GET['action']) && $_GET['action'] == 'delete' && $_GET['id'] > 0){
  try {
    $dbh->beginTransaction();
    $id = $_GET['id'];
    $sql = 'DELETE FROM member WHERE id = :id';
    $pre = $dbh->prepare($sql);
    $pre->bindValue(':id',$id,PDO::PARAM_INT);
    $pre->execute();
    $dbh->commit();
    $delete_count = $pre->rowCount();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "データ削除エラー:".$e->getMessage();
  }
}

//挿入の処理
if(isset($_POST['action']) && $_POST['action'] == 'insert'){
  try {
    $dbh->beginTransaction();
    $sql = 'INSERT INTO member(last_name,first_name,age) VALUES(:last_name,:first_name,:age)';
    $pre = $dbh->prepare($sql);
    $pre->bindValue(':last_name',$_POST['last_name'],PDO::PARAM_STR);
    $pre->bindValue(':first_name',$_POST['first_name'],PDO::PARAM_STR);
    $pre->bindValue(':age',$_POST['age'],PDO::PARAM_INT);
    $pre->execute();
    $dbh->commit();
    $insert_count = $pre->rowCount();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "データ挿入エラー:".$e->getMessage();
  }
}

//更新の処理
if(isset($_POST['action']) && $_POST['action'] == 'update'){
  $id = $_SESSION['id'];
  try {
    $dbh->beginTransaction();
    $sql = 'UPDATE member SET last_name = :last_name,first_name = :first_name,age = :age where id = :id';
    $pre = $dbh->prepare($sql);
    $pre->bindValue(':last_name',$_POST['last_name'],PDO::PARAM_STR);
    $pre->bindValue(':first_name',$_POST['first_name'],PDO::PARAM_STR);
    $pre->bindValue(':age',$_POST['age'],PDO::PARAM_INT);
    $pre->bindValue(':id',$id,PDO::PARAM_INT);
    $pre->execute();
    $dbh->commit();
    $update_count = $pre->rowCount();
  } catch (Exception $e) {
    $dbh->rollBack();
    echo "データ挿入エラー:".$e->getMessage();
  }
  unset($_POST['id']);
}

//検索と現在のデータ表示
  try {
      if(isset($_POST['search_key']) && $_POST['search_key'] != ''){
      $search_key = '%'.$_POST['search_key'].'%';
      $sql = 'SELECT * FROM member where last_name like :last_name or first_name like :first_name';
      $pre = $dbh->prepare($sql);
      $pre->bindValue(':last_name',$search_key,PDO::PARAM_STR);
      $pre->bindValue(':first_name',$search_key,PDO::PARAM_STR);
      $pre->execute();

    } else {
      $sql = 'SELECT * FROM member';
      $pre = $dbh->query($sql);
    }
      $total_count = $pre->rowCount();
  } catch (Exception $e) {
      echo "データ取得エラー:".$e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>会員名簿一覧</title>
  </head>
  <body>
    <hr>
    <h1>会員名簿一覧</h1>
    <hr>
    [<a href="form.html">新規登録</a>]<br>
    <form name="form1" method="post" action="list.php">
      名前：<input type="text" name="search_key"><input type="submit" value="検索する">
    </form>
    <?php if($total_count < 1) :?>
      <?php echo "検索結果がありません。<br>"; ?>
    <?php else :?>
    <table border="1">
      <?php echo "検索結果は{$total_count}件です。<br>"; ?>
      <tr><th>番号</th><th>氏</th><th>名</th><th>年齢</th><th></th><th></th></tr>
      <?php while($row = $pre->fetch(PDO::FETCH_ASSOC)){
      ?>
      <tr>
        <td><?php echo h($row['id']);?></td>
        <td><?php echo h($row['last_name']);?></td>
        <td><?php echo h($row['first_name']);?></td>
        <td><?php echo h($row['age']);?></td>
        <td><a href="updateform.php?id=<?php echo h($row['id']);?>">更新</a></td>
        <td><a href="list.php?action=delete&id=<?php echo h($row['id']);?>">削除</a></td>
      </tr>
      <?php
      }
       ?>
    </table>
    <?php endif ;?>
  </body>
</html>
