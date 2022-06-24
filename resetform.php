<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/SMTP.php';
    
    include("php/util.php");
    include("php/header.php");
    include("php/database.php");
    $header = logStatus();
    
    $secretKey = md5(uniqid(mt_rand(), true));
    // $url = 'http://localhost/google/reset.php?key=';
    $url = 'https://momo115.sakura.ne.jp/google/reset.php?key=';
    $url .= $secretKey;
    if (isset($_POST['email'])){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kenta.aratani5011@gmail.com';
        $mail->Password = 'wtditjbdmjrimdsk';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('kenta.aratani5011@gmail.com', 'Google2');
        $mail->addAddress($_POST['email']);
        $mail->Subject = 'Atto: Password Reset Link';
        $mail->Body = "Please reset your password from below. The link is only active for the next 10 minutes.\n".$url;
        try {
            $mail->send();
        } catch (Exception $e){
            exit('PHPMailer Error: '.$mail->ErrorInfo);
        }
    }

    $db = DbConn();
    $emails = array_merge(fldArray('email', 'loginCred', $db), fldArray('email', 'frozenAcct', $db));
    if (in_array($_POST['email'], $emails)){   
        mkTbIF('token', 'email VARCHAR(256),tokenid VARCHAR(256),expires INT(10)', $db);
        $_POST['tokenid'] = $secretKey;
        $_POST['expires'] = strtotime("+ 10 minutes");
        addData('token', 'email,tokenid,expires', $db, $_POST);
    }            
?>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <title>Reset Form</title>
</head>
<body id="reset">
    <header><?=$header?></header>
    <div>
        <form method="post" id="resetForm">
            <label for="email">Email:</label>
                <input type="text" name="email" id="checkEmail" required>
            <input id="resetSubmit" type="submit" value="Confirm email">
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const sessionEmail = '<?php echo $_SESSION['email'] ?>';
        if (sessionEmail !== ''){
            document.getElementById('checkEmail').value = sessionEmail;
        }
    </script>
</body>
</html>