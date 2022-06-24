<?php
    include("php/util.php");
    include("php/database.php");
    $db = DbConn();
    if ($_GET['domain'] == 'qiita'){
        $Atitle = fldArray('title', 'Aqiita', $db, 'qiitaName="'.$_GET['qiitaName'].'"');
        if (!in_array($_GET['title'], $Atitle)){
            addData('Aqiita', 'qiitaName,title,url', $db, $_GET);
        }
    }
    if ($_GET['domain'] == 'stackoverflow'){
        $Atitle = fldArray('title', 'Astackoverflow', $db, 'stackoName="'.$_GET['stackoName'].'"');
        if (!in_array($_GET['title'], $Atitle)){
            $_GET['title'] = str_replace("_", " ", $_GET['title']);
            addData('Astackoverflow', 'stackoName,title,link', $db, $_GET);
        }
    }
    redirect("dashboard.php");
?>