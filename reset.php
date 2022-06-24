<?php
    include("php/util.php");
    include("php/header.php");
    include("php/database.php");
    $header = logStatus();
    $db = DbConn();

    $expireArray = fldArray('expires', 'token', $db);
    for ($i = 0; $i < count($expireArray); $i++){
        if (strtotime('now') > $expireArray[$i]){
            delData('token', 'expires='.$expireArray[$i], $db);
            console_log('expired');
        }
    }
    $error = '';
    $tokens = fldArray('tokenid', 'token', $db);
    if (in_array($_GET['key'], $tokens)){
        $email = CondSQL('email', 'token', 'tokenid="'.$_GET['key'].'"', $db);
        if (isset($_POST['password'])){
            $validEmail = fldArray('email', 'loginCred', $db);
            $frozEmail = fldArray('email', 'frozenAcct', $db);
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            if (in_array($email, $frozEmail)){
                copyData('loginCred(email, password, displayName, attempts)', 'email, password, displayName, attempts', 'frozenAcct', 'email="'.$email.'"', $db);
                delData('frozenAcct', 'email="'.$email.'"', $db);
            } 
            updateSQL('loginCred', 'attempts=0', 'email="'.$email.'"', $db);
            updateSQL('loginCred', 'password="'.$_POST['password'].'"', 'email="'.$email.'"', $db);
            delData('token', 'email="'.$email.'"', $db);
            redirect('login.php');
        }
    } else {
        $error = 'Link expired or invalid';
    }
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <title>Reset Password</title>
</head>
<body id="urlReset">
    <header><?=$header?></header>
    <div>
        <form method="post" id="urlResetForm">
            <label for="password">Password:</label>
                <input type="password" name="password" id="pwd1" required>
            <label for="password">Re-enter password:</label>
                <input type="password" name="password1" id="pwd2" required>
            <input id="resetPwd" type="button" value="Reset password">
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // check password match
        const $resetPwd = document.getElementById("resetPwd");
        $resetPwd.addEventListener("click", function(){
            const $pwd1 = document.getElementById("pwd1");
            const $pwd2 = document.getElementById("pwd2");
            if (($pwd2 != "") && ($pwd1.value === $pwd2.value)){ // 両パスが合わないと、type submitに変更されずPHPにデータが送信されない
                $resetPwd.setAttribute("type", "submit");
            } else {
                $pwd1.style.borderColor = "red";
                $pwd2.style.borderColor = "red";
            }
        })

        // エラー表示
        const error = '<?php echo $error ?>';
        if (error !== ''){
            $resetPwd.value = error;
            $resetPwd.style.backgroundColor = 'black';
        }
    </script>
</body>
</html>