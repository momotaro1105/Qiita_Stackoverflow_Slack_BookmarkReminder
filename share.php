<?php
    include("php/util.php");
    include("php/session.php");
    include("php/header.php");
    include("php/database.php");
    include('php/slack.php');
    $header = logStatus();
    session_start();
    checkUser();
    $db = DbConn();
    if (!isset($_SESSION['SlackToken'])){
        echo "<script> alert('Please install this app to your slack account/channel');</script>";
        redirect('dashboard.php');
    }
    if (isset($_POST['recipient'])){
        $channelID = CondSQL('uid', 'channelUsers', 'display_name="'.$_POST['recipient'].'"', $db);
        if (isset($_GET['url'])){
            sendDM($_SESSION['SlackToken'], $channelID, $_GET['url']);
        } else {
            redirect('dashboard.php');
        }
    }
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <title>Share</title>
</head>
<body>
    <header><?=$header?></header>
    <form action="" method="post">
        <label for="">Please select recipient:</label>
        <select name="recipient" id="">
            <?php foreach($channelUsers as $channelUser){ ?>
                <option value=""></option>
                <option value=""><?=h($channelUser['display_name']);?></option>
            <?php } ?>
        </select>
        <input type="submit">
    </form>
</body>
</html>