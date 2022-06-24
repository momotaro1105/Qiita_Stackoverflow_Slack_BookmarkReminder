<?php
    include("php/util.php");
    include("php/header.php");
    include("php/database.php");
    include("php/session.php");
    $header = logStatus();
    if (isset($_POST['email'])){
        $db = DbConn();
        $validEmail = fldArray('email', 'loginCred', $db);
        $frozEmail = fldArray('email', 'frozenAcct', $db);
        $error = '';
        $pwdErr = '';
        $emailErr = '';
        if (!in_array($_POST['email'], $frozEmail) && !in_array($_POST['email'], $validEmail)){
            $emailErr = 'Email not registered'; 
        } else if (in_array($_POST['email'], $frozEmail)){
            $emailErr = 'Account has been locked';
        } else if (in_array($_POST['email'], $validEmail)){
            $attempts = CondSQL('attempts', 'loginCred', 'email="'.$_POST['email'].'"', $db);
            $truePwd = CondSQL('password', 'loginCred', 'email="'.$_POST['email'].'"', $db);
            if ($attempts == 3){
                copyData('frozenAcct(email, password, displayName, attempts)', 'email, password, displayName, attempts', 'loginCred', 'email="'.$_POST['email'].'"', $db);
                delData('loginCred', 'email="'.$_POST['email'].'"', $db);
                $emailErr = 'Account has been locked';
            } else if ($attempts < 3){
                if (password_verify($_POST['password'], $truePwd)){
                    logIn();
                    $_SESSION['displayName'] = CondSQL('displayName', 'loginCred', 'email="'.$_SESSION['email'].'"', $db);
                    $_SESSION['qiitaName'] = CondSQL('qiitaName', 'loginCred', 'email="'.$_SESSION['email'].'"', $db);
                    $_SESSION['stackoName'] = CondSQL('stackoName', 'loginCred', 'email="'.$_SESSION['email'].'"', $db);
                    updateSQL('loginCred', 'attempts=0', 'email="'.$_POST['email'].'"', $db);
                    redirect('dashboard.php');
                } else {
                    updateSQL('loginCred', 'attempts='.($attempts+1), 'email="'.$_POST['email'].'"', $db);
                    $error = 'Incorrect password '.(3 - $attempts).' tries until account locked';
                }
            }
        }
    }
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Log in</title>
</head>
<body id="loginBody">
    <header><?=$header?></header>
    <div>
        <form action="" method="post" id="loginForm">
            <label for="email">Email:</label>
                <input type="text" name="email" id="loginEmail" value='' required>
            <label for="password">Password: <a id="forgot" href="resetform.php">Forgot password?</a></label>
                <input type="password" name="password" id="loginPwd" required>
            <input id="loginSubmit" type="submit" value="Log in">
        </form>
        <i class="material-icons" id="toggle">remove_red_eye</i>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const $toggle = document.getElementById("toggle");
        const $pwd = document.getElementById("loginPwd");
        $toggle.addEventListener("click", function (){
            const inputtype = $pwd.getAttribute("type") != "password" ? "password" : "text";
            $pwd.setAttribute("type", inputtype);
        })
        // エラー表記
        const pwdError = '<?php echo $error ?>';
        if (pwdError !== ''){
            document.getElementById('loginPwd').placeholder = pwdError;
            document.getElementById('forgot').style.color = 'red';
        }
        const pwdErr = '<?php echo $pwdErr ?>';
        if (pwdErr !== ''){
            document.getElementById('loginPwd').placeholder = pwdErr;
            document.getElementById('forgot').style.color = 'red';
        }
        const emailErr = '<?php echo $emailErr ?>';
        if (emailErr !== ''){
            document.getElementById('loginEmail').placeholder = emailErr;
        }
        if (emailErr == 'Account has been locked'){
            document.getElementById('loginSubmit').value = 'Reactivate account';
        }
        if (document.getElementById('loginSubmit').value == 'Reactivate account'){
            $reactivate = document.getElementById('loginSubmit');
            $reactivate.addEventListener('click', function(){
                window.location.href = "resetform.php";
            })
        }
    </script>
</body>
</html>