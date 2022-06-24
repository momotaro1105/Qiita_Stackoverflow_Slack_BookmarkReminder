<?php
    include("php/database.php");
    $db = DbConn();
    delData($_GET['domain'], 'title="'.$_GET['title'].'"', $db);
    if ($_GET['domain'] == 'qiita'){
        addData('Dqiita', 'qiitaName,title,url', $db, $_GET);
    } 
    if ($_GET['domain'] == 'stackoverflow'){
        addData('Dstackoverflow', 'stackoName,title,link', $db, $_GET);
    }
    if ($_GET['domain'] == 'slack'){
        addData('Dslack', 'permalink', $db, $_GET);
    }
    redirect("dashboard.php");
?>