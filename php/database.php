<?php
    // function DbConn($dbName = 'userInfo', $host = 'localhost', $userName = 'root', $pwd = ''){
    //     try {
    //         $dsn = 'mysql:dbname='.$dbName.';host='.$host.';charset=utf8';
    //         return new PDO($dsn, $userName, $pwd);
    //     } catch (PDOException $e) {
    //         exit('DBConnection Error: '.$e->getMessage());
    //     }
    // }
    function DbConn($dbName = 'momo115_google', $host = 'mysql________', $userName = '_____', $pwd = '_____'){
        try {
            $dsn = 'mysql:dbname='.$dbName.';host='.$host.';charset=utf8';
            return new PDO($dsn, $userName, $pwd);
        } catch (PDOException $e) {
            exit('DBConnection Error: '.$e->getMessage());
        }
    }

    // テーブル作成（プライマリーidキーがデフォルトになっているため、さらにidという名の$structureは追加できない）
    function mkTbIF($tableName, $structure, $PDO){
        try {
            $sql = 'CREATE TABLE IF NOT EXISTS '.$tableName.' (id INT(12) NOT NULL auto_increment PRIMARY KEY,'.$structure.') DEFAULT CHARSET="utf8"';
            return $PDO -> query($sql);
        } catch (PDOException $e){
            exit('TableCreate Error: '.$e -> getMessage());
        }
    }
    // mkTbIF('loginCred', 'email VARCHAR(256),password VARCHAR(256),displayName VARCHAR(256),attempts INT(2) DEFAULT 0', $db);

    // relational DB作成用、primary keyナシ、参照先テーブルにおけるカラム名は'UNIQUE'でなければならない
    function mkFkTb($tableName, $foreign, $reference, $structure, $PDO){
        try {
            $sql = 'CREATE TABLE IF NOT EXISTS '.$tableName.' ('.$structure.', FOREIGN KEY ('.$foreign.') REFERENCES '.$reference.') DEFAULT CHARSET="utf8"';
            return $PDO -> query($sql);
        } catch (PDOException $e){
            exit('TableCreate Error: '.$e -> getMessage());
        }
    }
    // mkFkTb('qiita', 'qiitaName', "loginCred(qiitaName)", 'qiitaName VARCHAR(256), title VARCHAR(256), url VARCHAR(256)', $db);

    // テーブルの全データ削除
    function clearTb($tableName, $PDO){
        try {
            $sql = 'DELETE FROM '.$tableName;
            return $PDO -> query($sql);
        } catch (PDOException $e){
            exit('TableCreate Error: '.$e -> getMessage());
        }
    }


    // データ登録
    function cvtStruc($structure){
        $structureArray = explode(',', $structure);
        for ($i=0; $i<count($structureArray); $i++){
            $structureArray[$i] = ':'.$structureArray[$i];
        }
        return implode(',', $structureArray);
    }
    function addData($tableName, $structure, $PDO, $postedData){
        $structured = cvtStruc($structure);
        $add = 'INSERT INTO '.$tableName.' ('.$structure.') VALUES ('.$structured.')';
        $stmt = $PDO -> prepare($add);
        $structureArray = explode(',', $structure); // ループ用の配列作成
        for ($i=0; $i<count($structureArray); $i++){
            $stmt -> bindValue(explode(',',$structured)[$i], $postedData[$structureArray[$i]], PDO::PARAM_STR); // index名が同じなければならない
        }
        $status = $stmt -> execute();
        if ($status == false){
            $error = $stmt->errorInfo();
            exit("SQLData Error: ".$error[2]);
        } else {
            return $status;
        }
    }
    // addData('basicProfile', 'email,password', $dbh, $_POST);

    // 特定のテーブルのフィールド（縦）にある全ての値を配列として取得
    function fldArray($field, $tableName, $PDO, $condition = 1){
        $sql = 'SELECT '.$field.' FROM '.$tableName.' WHERE '.$condition;
        $result = $PDO -> query($sql);
        return $result -> fetchAll(PDO::FETCH_COLUMN);
    }
    // $existingEmail = fldArray('email', 'basicProfile', $db);

    // 条件からデータ取得（一つの値を想定）
    function CondSQL($category, $tableName, $condition = 1, $PDO){
        $sql = 'SELECT '.$category.' FROM '.$tableName.' WHERE '.$condition;
        $result = $PDO -> query($sql);
        $fetched = $result -> fetch(PDO::FETCH_ASSOC); // このままだとobject
        return $fetched[$category];
    }
    // $truePwd = CondSQL('password', 'basicProfile', 'email="'.$_SESSION['email'].'"', $db);

    // データ更新（複数可）
    function updateSQL($tableName, $newVal, $condition, $PDO){
        $sql = 'UPDATE '.$tableName.' SET '.$newVal.' WHERE '.$condition;
        $result = $PDO -> query($sql);
        return $result -> fetch(PDO::FETCH_ASSOC);
    }
    // updateSQL('basicProfile', 'attempts='.$LoginAttempts['attempts'], 'email="'.$_POST['email'].'"', $db);

    // 特定データの複製 注意：primary key idを設定している場合、下記例のようなカテゴリ指定必須
    function copyData($newTable, $values, $oldTable, $condition, $PDO){
        $sql = 'INSERT INTO '.$newTable.' SELECT '.$values.' FROM '.$oldTable.' WHERE '.$condition;
        $result = $PDO -> query($sql);
        return $result -> fetch(PDO::FETCH_ASSOC);
    }
    // copyData('frozenAccounts(email, password, displayName, attempts)', 'email, password, displayName, attempts', 'basicProfile', 'email="'.$_POST['email'].'"', $db);      
    
    // レコード削除
    function delData($tableName, $condition, $PDO){
        $sql = 'DELETE FROM '.$tableName.' WHERE '.$condition;
        $result = $PDO -> query($sql);
        return $result -> fetch(PDO::FETCH_ASSOC);
    }
    // delData('basicProfile', 'email="'.$_POST['email'].'"', $db);    

    // ワンタイムURL生成（キー保管用には向いていない）
    function uniqURL($default){ // https..から含む
        $url = $default.'?key=';
        $key = md5(uniqid(mt_rand(), true));
        $url .= $key;
        return $url;
    }
    // uriqURL('https://momo115.sakura.ne.jp/atto_php/reset.php');

    // リンク先に移動
    function redirect($path){
        header('Location: '.$path);
        exit();
    }
    // redirect('dashboard.php');

    // ログインの正常確認
    function checkUser(){
        if ($_SESSION["loggedIn"] !== true || $_SESSION["sessionID"] != session_id()) {
            redirect("login.php");
        } else {
            session_regenerate_id(true);
            $_SESSION["sessionID"] = session_id();
        }
    }
?>