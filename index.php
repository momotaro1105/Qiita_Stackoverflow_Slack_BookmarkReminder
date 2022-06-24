<?php
    include("php/header.php");
    $header = logStatus();
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <title>Intra</title>
</head>
<body id="homepage">
    <header><?=$header?></header>
    <div id="hp_body">
        <h1>Bloombergもどき</h1>
        <ol>
            <li>A platform meant for a <u>community</u></li>
            <li>Get the most out of <u>each other</u></li>
            <li><u>Sharing simplified</u></li>
        </ol>
    </div>
</body>
</html>