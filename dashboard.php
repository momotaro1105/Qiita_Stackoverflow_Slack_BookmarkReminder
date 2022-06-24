<?php
    include("php/util.php");
    include("php/session.php");
    include("php/header.php");
    include("php/database.php");
    include('php/qiita.php');
    include('php/stackoverflow.php');
    include('php/slack.php');
    $header = logStatus();
    session_start();
    checkUser();
    $db = DbConn();
    if ($_SESSION['qiitaName'] !== ''){
        $Qstocks = Qstock($_SESSION['qiitaName']);
        $deleted = fldArray('title', 'Dqiita', $db, 'qiitaName="'.$_SESSION['qiitaName'].'"');
        delData('qiita', 'qiitaName="'.$_SESSION['qiitaName'].'"', $db);
        for ($j=0; $j<count($deleted);$j++){
            for ($i=0; $i<count($Qstocks); $i++){
                if ($deleted[$j] === $Qstocks[$i]['title']){
                    unset($Qstocks[$i]);
                }
            }
        }
        $Qstocks = array_values($Qstocks);
        for ($i=0; $i<count($Qstocks); $i++){
            addData('qiita', 'title,url', $db, $Qstocks[$i]);
            updateSQL('qiita', 'qiitaName="'.$_SESSION['qiitaName'].'"', 'title="'.$Qstocks[$i]['title'].'"', $db);
        }
        $Qadded = [];
        $QAtitle = fldArray('title', 'Aqiita', $db, 'qiitaName="'.$_SESSION['qiitaName'].'"');
        $QAurl = fldArray('url', 'Aqiita', $db, 'qiitaName="'.$_SESSION['qiitaName'].'"');
        for ($i=0; $i<count($QAtitle); $i++){
            $Qadded[$i]['title'] = $QAtitle[$i];
            $Qadded[$i]['url'] = $QAurl[$i];
            array_push($Qstocks, $Qadded[$i]);
        }
    }
    if ($_SESSION['stackoName'] !== ''){
        $SOstocks = SOstock($_SESSION['stackoName'])['items'];
        $deleted = fldArray('title', 'Dstackoverflow', $db, 'stackoName="'.$_SESSION['stackoName'].'"');
        delData('stackoverflow', 'stackoName="'.$_SESSION['stackoName'].'"', $db);
        for ($j=0; $j<count($deleted);$j++){
            for ($i=0; $i<count($SOstocks); $i++){
                if ($deleted[$j] === $SOstocks[$i]['title']){
                    unset($SOstocks[$i]);
                }
            }
        }
        $SOstocks = array_values($SOstocks);
        for ($i=0; $i<count($SOstocks); $i++){
            addData('stackoverflow', 'title,link', $db, $SOstocks[$i]);
            updateSQL('stackoverflow', 'stackoName="'.$_SESSION['stackoName'].'"', 'title="'.$SOstocks[$i]['title'].'"', $db);
        }
        $Sadded = [];
        $SAtitle = fldArray('title', 'Astackoverflow', $db, 'stackoName="'.$_SESSION['stackoName'].'"');
        $SAlink = fldArray('link', 'Astackoverflow', $db, 'stackoName="'.$_SESSION['stackoName'].'"');
        for ($i=0; $i<count($SAtitle); $i++){
            $Sadded[$i]['title'] = $SAtitle[$i];
            $Sadded[$i]['link'] = $SAlink[$i];
            array_push($SOstocks, $Sadded[$i]);
        }
    }
    if (isset($_POST['qiitaName'])){
        $_SESSION['qiitaName'] = $_POST['qiitaName'];
        updateSQL('loginCred', 'qiitaName="'.$_POST['qiitaName'].'"', 'email="'.$_SESSION['email'].'"', $db);
        $Qstocks = Qstock($_POST['qiitaName']);
        $deleted = fldArray('title', 'Dqiita', $db, 'qiitaName="'.$_POST['qiitaName'].'"');
        delData('qiita', 'qiitaName="'.$_POST['qiitaName'].'"', $db);
        for ($j=0; $j<count($deleted);$j++){
            for ($i=0; $i<count($Qstocks); $i++){
                if ($deleted[$j] === $Qstocks[$i]['title']){
                    unset($Qstocks[$i]);
                }
            }
        }
        $Qstocks = array_values($Qstocks);
        for ($i=0; $i<count($Qstocks); $i++){
            addData('qiita', 'title,url', $db, $Qstocks[$i]);
            updateSQL('qiita', 'qiitaName="'.$_POST['qiitaName'].'"', 'title="'.$Qstocks[$i]['title'].'"', $db);
        }
        $PQadded = [];
        $PQAtitle = fldArray('title', 'Aqiita', $db, 'qiitaName="'.$_POST['qiitaName'].'"');
        $PQAurl = fldArray('url', 'Aqiita', $db, 'qiitaName="'.$_POST['qiitaName'].'"');
        for ($i=0; $i<count($PQAtitle); $i++){
            $PQadded[$i]['title'] = $PQAtitle[$i];
            $PQadded[$i]['url'] = $PQAurl[$i];
            array_push($Qstocks, $PQadded[$i]);
        }
    }
    if (isset($_POST['stackoName'])){
        $_SESSION['stackoName'] = $_POST['stackoName'];
        updateSQL('loginCred', 'stackoName="'.$_POST['stackoName'].'"', 'email="'.$_SESSION['email'].'"', $db);
        $SOstocks = SOstock($_POST['stackoName'])['items'];
        $deleted = fldArray('title', 'Dstackoverflow', $db, 'stackoName="'.$_POST['stackoName'].'"');
        delData('stackoverflow', 'stackoName="'.$_POST['stackoName'].'"', $db);
        for ($j=0; $j<count($deleted);$j++){
            for ($i=0; $i<count($SOstocks); $i++){
                if ($deleted[$j] === $SOstocks[$i]['title']){
                    unset($SOstocks[$i]);
                }
            }
        }
        $SOstocks = array_values($SOstocks);
        for ($i=0; $i<count($SOstocks); $i++){
            addData('stackoverflow', 'title,link', $db, $SOstocks[$i]);
            updateSQL('stackoverflow', 'stackoName="'.$_POST['stackoName'].'"', 'title="'.$SOstocks[$i]['title'].'"', $db);
        }
        $PSadded = [];
        $PSAtitle = fldArray('title', 'Astackoverflow', $db, 'stackoName="'.$_POST['stackoName'].'"');
        $PSAlink = fldArray('link', 'Astackoverflow', $db, 'stackoName="'.$_POST['stackoName'].'"');
        for ($i=0; $i<count($PSAtitle); $i++){
            $PSadded[$i]['title'] = $PSAtitle[$i];
            $PSadded[$i]['link'] = $PSAlink[$i];
            array_push($SOstocks, $PSadded[$i]);
        }
    }
    if (isset($_GET['code'])){
        $slackUser = getAuth($_GET['code']);
        $xoxpToken = $slackUser['authed_user']['access_token'];
        $UID = $slackUser['authed_user']['id'];
        updateSQL('loginCred', 'slackToken="'.$xoxpToken.'"', 'email="'.$_SESSION['email'].'"', $db);
        updateSQL('loginCred', 'slackUID="'.$UID.'"', 'email="'.$_SESSION['email'].'"', $db);
        $_SESSION['SlackToken'] = $xoxpToken;
        $_SESSION['slackUID'] = $UID;
        console_log($_SESSION);
        $Sstocks = Sstock($xoxpToken);
        console_log($Sstocks);
        delData('slack', 'slackToken="'.$_SESSION['SlackToken'].'"', $db);
        for ($i=0; $i<count($Sstocks); $i++){
            addData('slack', 'text,permalink', $db, $Sstocks[$i]);
            updateSQL('slack', 'slackToken="'.$_SESSION['SlackToken'].'"', 'permalink="'.$Sstocks[$i]['permalink'].'"', $db);
        }
    }
    $registeredToken = CondSQL('slackToken', 'loginCred', 'email="'.$_SESSION['email'].'"', $db);
    if ($registeredToken !== null && !isset($_GET['code'])){
        $_SESSION['SlackToken'] = $registeredToken;
        $Sstocks = Sstock($registeredToken);
        $deleted = fldArray('permalink', 'Dslack', $db, 'slackToken="'.$registeredToken.'"');
        delData('slack', 'slackToken="'.$_SESSION['SlackToken'].'"', $db);
        for ($j=0; $j<count($deleted);$j++){
            for ($i=0; $i<count($Sstocks); $i++){
                if ($deleted[$j] === $Sstocks[$i]['permalink']){
                    unset($Sstocks[$i]);
                }
            }
        }
        $Sstocks = array_values($Sstocks);
        for ($i=0; $i<count($Sstocks); $i++){
            addData('slack', 'text,permalink', $db, $Sstocks[$i]);
            updateSQL('slack', 'slackToken="'.$_SESSION['SlackToken'].'"', 'permalink="'.$Sstocks[$i]['permalink'].'"', $db);
        }
    }
    if (isset($_SESSION['SlackToken'])){
        mkTbIF('channelUsers', 'channel VARCHAR(256) DEFAULT "C03BNE864CD", uid VARCHAR(256), display_name VARCHAR(256)', $db);
        $channelUsers = getChannelUsers($_SESSION['SlackToken'])['members'];
        for ($i=0; $i<count($channelUsers); $i++){
            $filteredUsers[$i]['uid'] = $channelUsers[$i]['id'];
            $filteredUsers[$i]['display_name'] = $channelUsers[$i]['profile']['display_name'];
        }
        delData('channelUsers', 'channel="C03BNE864CD"', $db);
        for ($i=0; $i<count($filteredUsers); $i++){
            if ($filteredUsers[$i]['display_name'] == null){
                unset($filteredUsers[$i]);
            } else {
                addData('channelUsers', 'uid,display_name', $db, $filteredUsers[$i]);
            }
        }
        $filteredUsers = array_values($filteredUsers);
    }
    console_log($_SESSION);
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css?<?php echo date('YmdHis')?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <title>Dashboard</title>
</head>
<body id="dashboard">
    <header><?=$header?></header>
    <div>
        <div id="trending">
            <h2>New & Popular ~72hrs</h2>
            <div id="topsAppend"></div>
        </div>
        <div id="stocked">
            <h2>Stocked Articles <a href="https://slack.com/oauth/v2/authorize?client_id=3393811322528.3679145686581&scope=&user_scope=channels:read,groups:read,im:read,im:write,mpim:read,mpim:write,stars:read,users:read,chat:write"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcSet="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x" /></a></h2>
            <form action="" id="searchstocked" method="post">
                Qiita: <input type="text" class="idfilter" name="qiitaName" id="qiitaName" placeholder="Username">
                StackOverflow: <input type="text" class="idfilter" name="stackoName" id="stackoName" placeholder="Userid">
                <input id="button" type="submit" value="REFRESH">
            </form>
            <div>
                <?php if (count($Qstocks) > 0){ ?>
                    <?php foreach($Qstocks as $Qstock){ ?>
                        <span class="qiitastocks"><a target="_blank" href=<?=$Qstock['url']?>><?=h($Qstock['title']);?></a> (<a href="deleteStock.php?domain=qiita&qiitaName=<?=h($_SESSION['qiitaName'])?>&title=<?=h($Qstock['title'])?>&url=<?=h($Qstock['url'])?>">Delete</a> | <a href="share.php?url=<?=h($Qstock['url'])?>">Share</a>)</span>
                <?php }} ?>
                <?php if (isset($_SESSION['SlackToken'])){ ?>
                    <?php foreach($Sstocks as $Sstock){ ?>
                        <span class="slackstocks"><a target="_blank" href=<?=$Sstock['permalink']?>><?=h($Sstock['text']);?></a> (<a href="deleteStock.php?domain=slack&permalink=<?=h($Sstock['permalink'])?>">Delete</a> | <a href="share.php?url=<?=h($Sstock['permalink'])?>">Share</a>)</span>
                <?php }} ?>
                <?php if (count($SOstocks) > 0){ ?>
                    <?php foreach($SOstocks as $SOstock){ ?>
                        <span class="stackoverflowstocks"><a target="_blank" href=<?=$SOstock['link']?>><?=h($SOstock['title']);?></a> (<a href="deleteStock.php?domain=stackoverflow&stackoName=<?=h($_SESSION['stackoName'])?>&title=<?=h($SOstock['title'])?>&link=<?=h($SOstock['link'])?>">Delete</a> | <a href="share.php?url=<?=h($SOstock['link'])?>">Share</a>)</span>
                <?php }} ?>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script>
    <script>
        const qiitaNameP = '<?php echo $_POST['qiitaName'] ?>';
        const qiitaNameS = '<?php echo $_SESSION['qiitaName'] ?>';
        var qiitaName = '';
        if (qiitaNameP !== '' || qiitaNameS !== ''){
            const qiitaName = (qiitaNameP.length > qiitaNameS.length) ? qiitaNameP : qiitaNameS;
            document.getElementById("qiitaName").placeholder = qiitaName;
        }
        const stackoNameP = '<?php echo $_POST['stackoName'] ?>';
        const stackoNameS = '<?php echo $_SESSION['stackoName'] ?>';
        if (stackoNameP !== '' || stackoNameS !== ''){
            const stackoName = (stackoNameP.length > stackoNameS.length) ? stackoNameP : stackoNameS;
            document.getElementById("stackoName").placeholder = stackoName;
        }

        const key = {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ca6a386884e9b3b638c33f3c43860e9345673d58'
            },
        }
        const now = new Date();
        fetch('https://qiita.com/api/v2/items?per_page=10&query=created%3A%3E'+now.getFullYear()+'-'+(now.getMonth()+1)+'-'+(now.getDate()-3)+'+stocks%3A%3E10', key)
            .then(response => response.json()).then(data => {
                for (let i=0; i<data.length; i++){
                    const qiitaName = (qiitaNameP.length > qiitaNameS.length)?qiitaNameP:qiitaNameS;
                    addQ = 'addStock.php?&domain=qiita&qiitaName='+qiitaName+'&title='+data[i]['title']+'&url='+data[i]['url'];
                    $("#topsAppend").append('<span><a href="'+data[i]['url']+'" target="_blank">'+data[i]['title']+'</a> (<a href="'+addQ.replace(/\s/g, '')+'">Add</a>)</span>');
                }
            });
        const timestamp = Math.floor((Date.now()-3*24*60*60*1000)/1000);
        fetch('https://api.stackexchange.com/2.3/questions/featured?page=1&pagesize=10&fromdate='+timestamp+'&order=desc&sort=votes&site=stackoverflow&key=atHW2PH8FqOLaBwxNb6dkw((')
            .then(response => response.json()).then(data => {
                for (let i=0; i<data['items'].length; i++){
                    const stackoName = (stackoNameP.length > stackoNameS.length)?stackoNameP:stackoNameS;
                    addSO = 'addStock.php?domain=stackoverflow&stackoName='+stackoName+'&title='+data['items'][i]['title']+'&link='+data['items'][i]['link'];
                    $("#topsAppend").append('<span><a href="'+data['items'][i]['link']+'" target="_blank">'+data['items'][i]['title']+'</a> (<a href="'+addSO.replace(/\s/g, '_')+'">Add</a>)</span>');
                }
            });
    </script>
</body>
</html>