<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-01</title>
    <style type="text/css">
    body{
        background-color:#FFC82C;
        color:navy;
        }
    </style>
</head>
    <?php
        //変数
        error_reporting(E_ALL & ~E_NOTICE);
        error_reporting(0);
        $ecomment="";
        $ename="";
        $editnumber="";
        $passe="";
        $edit="";
        $passe=($_POST["passe"]);
        $edit = $_POST["edit"];


        //データベースに繋ぐ
        $dsn = "テックベース";
        $user = "ユーザー名";
        $password = "パスワード";
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS tbm5" //テーブル作るよ
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"
        ."name char(32),"
        ."comment TEXT,"
        ."time TEXT,"
        ."pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        
        // 編集呼び出し
        if (!empty($_POST["edit"]) ){
            $sql = 'SELECT * FROM tbm5 WHERE id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(':id', $edit, PDO::PARAM_INT);
            $stmt->bindParam(':pass', $passe, PDO::PARAM_STR);
            $stmt->execute(); 
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                    $ename=$row['name'];
                    $ecomment=$row['comment'];
                    $editnumber=$row['id'];
            }
        }
        
    ?>
    <body>
    <h1>掲示板</h1>
    <center><img src="rogo.jpg" width="300px" alt="ロゴです"></center>
    <p>mission5-1でパスワード付き掲示板を作りました。</p>
    <!-- 投稿フォーム -->
    <h2>投稿フォーム</h2>
    <form action="" method="post">
        <input type="text" name="comment" value="<?php echo $ecomment ;?>" placeholder="コメントを入力">
        <input type="text" name="name" value="<?php echo $ename; ?>" placeholder="名前を入力">
        <input type="text" name="pass" placeholder="パスワードを入力">
        <input type="hidden" name="ed_num" value="<?php echo $editnumber;?>"><!--編集したい投稿番号-->
        <input type="submit" name="submit" value="投稿">
    </form>
    <!-- 削除フォーム -->
    <hr>
    <h2>削除フォーム</h2>
    <form action="" method="post">
    <input type="text" name="delete" placeholder="削除対象番号を入力">
    <input type="text" name="passd" placeholder="パスワードを入力">
    <input type="submit" name="submit_delete" value="削除">
    <!-- 編集フォーム -->
    <hr>
    <h2>編集フォーム</h2> 
    <p>編集したい投稿の番号とパスワードを入れたら投稿フォームに出てきます</p>
    <form action="" method="post">
    <input type="text" name="edit" placeholder="編集対象番号を入力">
    <input type="text" name="passe" placeholder="パスワードを入力">
    <input type="submit" name="submit_edit" value="編集呼び出し">
    </form>
    <hr>
    <h2>投稿一覧</h2>
    <?php 
    //変数
    $comment = $_POST["comment"];
    $name = $_POST["name"];
    $pass = $_POST["pass"];
    $passd = $_POST["passd"];
    $delete = $_POST["delete"];
    $ed_num = $_POST["ed_num"];
    $date = date('Y-m-d H:i:s');
    $comment = "";
    $name = "";
    $pass = "";
    $passd = "";
    $delete = "";
    $ed_num = "";

        //新規投稿
        if (!empty($_POST["comment"]) && empty($_POST["ed_num"])) {
            $sql = $pdo -> prepare("INSERT INTO tbm5 (name, comment, pass, time) VALUES(:name, :comment, :pass, :time)");
            $sql -> bindParam(":name", $name, PDO::PARAM_STR);        
            $sql -> bindParam(":comment", $comment, PDO::PARAM_STR);
            $sql -> bindParam(":time", $date, PDO::PARAM_STR);
            $sql -> bindParam(":pass", $pass, PDO::PARAM_STR);
            $sql -> execute(); 
        } 
        //編集
        if (!empty($_POST["ed_num"])) {
            $sql = "UPDATE tbm5 SET name=:name,comment=:comment,pass=:pass,time=:time where id=:id "; //呼び出す時にパスワード一致を確認したのでこっちではidのみ
            $stmt = $pdo->prepare($sql);
            $stmt -> bindParam(":name", $name, PDO::PARAM_STR);
            $stmt -> bindParam(":comment", $comment, PDO::PARAM_STR);
            $stmt -> bindParam(":pass", $pass, PDO::PARAM_STR);
            $stmt -> bindParam(":time", $time, PDO::PARAM_STR);
            $stmt -> bindParam(":id", $ed_num, PDO::PARAM_INT);
            $stmt -> execute();
        }
        
        //消去
        if (!empty($_POST["delete"]) && !empty($_POST["passd"])) {
            $sql = 'DELETE FROM tbm5 WHERE id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":id", $delete, PDO::PARAM_INT);
            $stmt->bindParam(":pass", $passd, PDO::PARAM_STR);
            $stmt->execute();
        }
        
        //表示する
        $sql = "SELECT * FROM tbm5";
        $stmt = $pdo -> query($sql);
        foreach ($stmt as $row) {
            echo $row["id"] . ",";
            echo $row["name"] . ",";
            echo $row["comment"] . ",";
            echo $row["time"] . "<br>";
        }
    ?>
</body>
</html>