<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8" />
        <title>mission5</title>
        <h1>けいじばん</h1>
    </head>
    <body>
        
        
          
       <?php
    //   Db接続
            $dsn= 'データベース名';
            $user= 'ユーザー名';
            $password= 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
           
            //テーブルがなければ作成する
            $sql = "CREATE TABLE IF NOT EXISTS mission_5"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name VARCHAR(32),"
            . "comment TEXT,"
            . "date DATETIME,"
            . "password VARCHAR(32)"
            .");";
            $stmt = $pdo->query($sql);  
        // 投稿
        if(isset($_POST["submit"])){
            if($_POST["name"] != ""){
                $name = $_POST["name"];
            }else{
                echo "名前を入力してください<br>";
            }
            if($_POST["comment"] != ""){
                $comment = $_POST["comment"];
            }else{
                echo "コメントを入力してください。<br>";
            }
            if($_POST["pass"] != ""){
                $pass = $_POST["pass"];
            }else{
                echo "パスワードを入力してください。<br>";
            }
            $date = date("Y-m-d H:i:s");
            // 新規投稿
            if (empty($_POST["num"])){
                $sql = $pdo -> prepare("INSERT INTO mission_5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
               /* $name = $_POST["name"];
                $comment = $_POST["comment"];
                $pass = $_POST["pass"];*/
                $sql -> execute();  
            }
            // 　編集投稿
            else{
                $id = $_POST["num"];
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                $pass = $_POST["pass"];
                $sql = 'UPDATE mission_5 SET name=:name,comment=:comment,password=:password WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':password', $pass, PDO::PARAM_STR);
                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                $stmt -> execute();
            } 
        }          
                   // 編集フォームに入力されたとき
                   
                    $edit="";
                    $editcomment="";
                    $editname="";
        //   編集フォーム
            if(isset($_POST["editsubmit"])){
                if(!empty($_POST["id_edit"])){
                    if(!empty($_POST["edit_pass"])){
                        
                
                    $edit_pass=$_POST["edit_pass"];
          
                    $id_edit=$_POST["id_edit"];
                    
                    $sql = 'SELECT * FROM mission_5 WHERE id =:id AND password=:password';
                    $stmt = $pdo ->prepare($sql);
                    $stmt->bindParam(':id', $id_edit, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $edit_pass, PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    foreach($results as $row){
                        $edit = $row['id'];
                        $editcomment = $row['comment'];
                        $editname = $row['name'];
                        }
                    }else{
                        echo "パスワードを入力してください。<br>";
                    }
                }else{
                    echo "編集したい投稿の投稿番号を入力してください。<br>";
                }
            }
                
        // 削除フォーム
            if(!empty($_POST["del_submit"])){
                if(empty($_POST["del_num"])){
                    echo "削除したい投稿番号を入力してください。<br>";
                }elseif(empty($_POST["del_pass"])){
                    echo "パスワードを入力してください。<br>";
                }else{
                    $del_id = $_POST["del_num"];
                    $del_pass = $_POST["del_pass"];
                    $sql = 'delete from mission_5 WHERE id=:id AND password=:password';
                    $stmt =$pdo -> prepare($sql);
                    $stmt -> bindParam(':id',$del_id, PDO::PARAM_INT);
                    $stmt -> bindParam(':password',$del_pass, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        
            ?>
            <!--入力フォーム-->
        <h2>入力フォーム</h2>
        <form action="" method="post">
            <input type="hidden" name="num" value="<?php echo $edit?>"> 
            <h3>名前</h3> 
            <!-- 名前とコメントを入力必須にする -->
            <input type="text" name="name" placeholder="名前" value="<?php echo $editname ; ?>" required>
            <br>
            <h3>コメント</h3>
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $editcomment  ;?>" required>
            <br>
            <h3>パスワード</h3>
            <input type="password" name="pass"><br>
            <input type="submit" name="submit"><br>
            
            </form>
        <!--削除フォーム-->
        <h2>削除</h2>
           <h3> 削除対象番号</h3>
            <form action="" method="post">
            <input type="number" name="del_num" placeholder="削除したい投稿番号を入力してください">
            <br> 
            <h3>パスワード</h3> 
            <input type="password" name="del_pass" ><br>
            <input type="submit" name="del_submit" value="削除">
            </form>
       <!--編集番号指定用フォーム-->  
       <h2>編集</h2>
            <h3>編集対象番号</h3>
            <form action="" method="post">
                <input type="number" name="id_edit" placeholder="編集したい投稿の投稿番号を入力してください"　 >
                <h3>パスワード</h3>
                <input type="password" name="edit_pass"><br>
                <input type="submit" name="editsubmit" value="編集">
            </form>  
         
         <?php
         //   データ表示
            $sql = 'SELECT * FROM mission_5';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $row){
                echo $row["id"].",";
                echo $row["name"].",";
                echo $row["comment"].",";
                echo $row["date"]."<br>";
            echo "<hr>";
            }
        ?>
        
        </body>
        </html>
            
            