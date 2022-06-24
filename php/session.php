<?php
    // ログイン状態のセッション
    function logIn(){
        $db = DbConn();
        session_start();
        $_SESSION["loggedIn"] = true;
        $_SESSION["sessionID"] = session_id();
        if (isset($_POST['email'])){
            $_SESSION['email'] = $_POST['email'];
        }
        if (isset($_POST['qiitaName'])){
            $_SESSION['qiitaName'] = $_POST['qiitaName'];
        } else {
            $_SESSION['qiitaName'] = CondSQL('qiitaName', 'loginCred', 'email="'.$_POST['email'].'"', $db);
        }
        if (isset($_POST['stackoName'])){
            $_SESSION['stackoName'] = $_POST['stackoName'];
        } else {
            $_SESSION['stackoName'] = CondSQL('stackoName', 'loginCred', 'email="'.$_POST['email'].'"', $db);
        }
    }
    // logIn();

    // ログアウト状態のセッション
    function logOut(){
        session_start();
        $_SESSION = [];
        session_destroy();
    }
    // logOut();
?>