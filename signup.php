<?php
    include("php/util.php");
    include("php/header.php");
    include("php/database.php");
    include("php/session.php");
    include("php/format.php");
    $header = logStatus();
    $error = [];
    if (count($_POST) > 0){
        $db = DbConn();
        mkTbIF('loginCred', 'email VARCHAR(256) UNIQUE,password VARCHAR(256),displayName VARCHAR(256) UNIQUE,qiitaName VARCHAR(256) UNIQUE, stackoName VARCHAR(256) UNIQUE, slackToken VARCHAR(256) UNIQUE, slackUID VARCHAR(256) UNIQUE,attempts INT(2) DEFAULT 0', $db);
        mkTbIF('frozenAcct', 'email VARCHAR(256),password VARCHAR(256),displayName VARCHAR(256),qiitaName VARCHAR(256), stackoName VARCHAR(256), slackToken VARCHAR(256), slackUID VARCHAR(256), attempts INT(2)', $db);
        foreach(['qiita', 'Dqiita', 'Aqiita'] as $name){
            mkFkTb($name, 'qiitaName', "loginCred(qiitaName)", 'qiitaName VARCHAR(256), domain VARCHAR(256) DEFAULT "qiita", title VARCHAR(256), url VARCHAR(256)', $db);
        }
        foreach(['stackoverflow', 'Dstackoverflow', 'Astackoverflow'] as $name){
            mkFkTb($name, 'stackoName', "loginCred(stackoName)", 'stackoName VARCHAR(256), domain VARCHAR(256) DEFAULT "stackoverflow", title VARCHAR(256), link VARCHAR(256)', $db);
        }
        foreach(['slack', 'Dslack'] as $name){
            mkFkTb($name, 'slackToken', "loginCred(slackToken)", 'slackToken VARCHAR(256), domain VARCHAR(256) DEFAULT "slack", text VARCHAR(256), permalink VARCHAR(256)', $db);
        }
        $exisEmail = fldArray('email', 'loginCred', $db);
        $exisDName = fldArray('displayName', 'loginCred', $db);
        $frozEmail = fldArray('email', 'frozenAcct', $db);
        $frozDname = fldArray('displayName', 'frozenAcct', $db);
        if (in_array($_POST['displayName'], $exisDName) || in_array($_POST['displayName'], $frozDname)){
            $error[] = 'Display name taken';
        }
        if (emailChk($_POST['email'])){
            $error[] = 'Please check your email address';
        } else if (in_array($_POST['email'], $exisEmail)){
            $error[] = 'Email already in use: <a href="login.php">LOGIN HERE</a>';
        } else if (in_array($_POST['email'], $frozEmail)){
            $error[] = 'Account locked <a href="resetform.php">REACTIVATE HERE</a>';
        }
        if (pwdChk($_POST['password'])){
            $error[] = 'Password requirements not met';
        } 
        if (count($error) == 0) {
            logIn();
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            addData('loginCred', 'email,password,displayName,qiitaName,stackoName', $db, $_POST);
            redirect('dashboard.php');
        }
    }
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Register</title>
</head>
<body id="signup_page">
    <header><?=$header?></header>
    <div id="signup_body">
        <div id="signup_message">
            <p>Install this app onto your slack channel</p>
            <ul id="signup_pointers">
                <li>
                    <i class="material-icons">new_releases</i>
                    creating an artificial sharing econoomy
                </li>
                <li>
                    <i class="material-icons">lock</i>
                    keep everything within your channel community
                </li>
                <li>
                    <i class="material-icons">unfold_less</i>
                    less is more
                </li>
                <li>
                    <i class="material-icons">attach_money</i>
                    get recognized and learn by sharing
                </li>
            </ul>
        </div>
        <div id="signup_form">
            <form id="signup_email" method="post" action="">
                <div>
                    <label for="qiita">Qiita username:</label>
                    <input id="user_qiita" type="text" name="qiitaName" required>
                </div>
                <div>
                    <label for="stackoverflow">Stackoverflow ID#:</label>
                    <input id="user_stackoverflow" type="text" name="stackoName" required>
                </div>
                <div>
                    <label for="email">Display name:</label>
                    <input id="user_displayName" type="text" name="displayName" required>
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input id="user_email" type="text" name="email" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input class="userpwd" id="userpwd_1" type="password" placeholder="1+ a~z/A~Z/#/special each, 4+ characters" required>
                </div>
                <i class="material-icons togglepwd" id="toggle1">remove_red_eye</i>
                <div>
                    <label for="password">Confirm password:</label>
                    <input class="userpwd" id="userpwd_2" name="password" type="password" placeholder="1+ a~z/A~Z/#/special each, 4+ characters" required>
                </div>
                <i class="material-icons togglepwd" id="toggle2">remove_red_eye</i>
                    <?php if ($error !== []): ?>
                        <?php for ($i=0; $i<count($error); $i++): ?>
                            <span style='color:red'><?=$error[$i]?></span>
                        <?php endfor; ?>
                    <?php endif; ?>
                <input id="signup_submit" type="button" value="Sign up">
            </form>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // toggle password
        for (let i = 0; i<2; i++){
            const $togglePassword = document.getElementsByClassName("togglepwd");
            const $pwd = document.getElementsByClassName("userpwd");
            $togglePassword[i].addEventListener("click", function (){
                const inputtype = $pwd[i].getAttribute("type") != "password" ? "password" : "text";
                $pwd[i].setAttribute("type", inputtype);
            })
        }

        // check password match
        const $signup_submit = document.getElementById("signup_submit");
        $signup_submit.addEventListener("click", function(){
            const $userpwd1 = document.getElementById("userpwd_1");
            const $userpwd2 = document.getElementById("userpwd_2");
            let password = "";
            if (($userpwd2 != "") && ($userpwd1.value === $userpwd2.value)){ // パスが合わないと、type submitに変更されずPHPにデータが送信されない
                $signup_submit.setAttribute("type", "submit");
            } else {
                $userpwd1.style.borderColor = "red";
                $userpwd2.style.borderColor = "red";
            }
        })
    </script>
</body>
</html>