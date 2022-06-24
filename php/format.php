<?php
    // メールアドレスとパスワードを正規表現で確認
    function emailChk($input){
        return !preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/', $input);
    }
    // emailChk($_POST['email']);

    function pwdChk($input){
        return !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[_\-!#*@&])[A-Za-z\d_\-!#*@&]{8,30}$/', $input);
    }
    // pwdChk($_POST['password']);
?>