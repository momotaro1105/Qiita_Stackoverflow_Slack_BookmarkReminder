<?php
    include("php/util.php");
    include("php/session.php");
    include("php/header.php");
    include("php/database.php");
    include('php/qiita.php');
    include('php/stackoverflow.php');
    include('php/search.php');
    $header = logStatus();
    session_start();
    checkUser();
    $db = DbConn();
    mkFkTb('searches', 'slackUID', "loginCred(slackUID)", 'slackUID VARCHAR(256), query VARCHAR(256), timestamp INT(10)', $db);
    if (isset($_GET['search'])){
        $_SESSION['search'] = $_GET['search'];
        $newQ['slackUID'] = $_SESSION['slackUID'];
        $newQ['query'] = $_GET['search'];
        $newQ['timestamp'] = strtotime('now');
        addData('searches', 'slackUID,query,timestamp', $db, $newQ);
        $searchResults = CustomSearch($_GET['search'])['items'];
        for ($i=0; $i<count($searchResults); $i++){
            $searchResults[$i]['stocked'] = 0;
            if ($searchResults[$i]['displayLink'] === 'stackoverflow.com'){
                $SOstocks = array_merge(fldArray('link', 'stackoverflow', $db), fldArray('link', 'Astackoverflow', $db));
                if (in_array($searchResults[$i]['link'], $SOstocks)){
                    $searchResults[$i]['stocked'] = $searchResults[$i]['stocked'] + 1;
                }
            } else if ($searchResult['displayLink'] === 'qiita.com'){
                $Qstocks = array_merge(fldArray('url', 'qiita', $db), fldArray('url', 'Aqiita', $db));
                if (in_array($searchResults[$i]['link'], $Qstocks)){
                    $searchResults[$i]['stocked'] = $searchResults[$i]['stocked'] + 1;
                }
            }
        }
        usort($searchResults, function($a, $b){
            if ($a["stocked"] == $b["stocked"])
                return (0);
            return (($a["stocked"] > $b["stocked"]) ? -1 : 1);
        });
        $queries = fldArray('query', 'searches', $db, 'slackUID <> "'.$_SESSION['slackUID'].'"');
        $slackUIDs = fldArray('slackUID', 'searches', $db, 'slackUID <> "'.$_SESSION['slackUID'].'"');
        $timestamps = fldArray('timestamp', 'searches', $db, 'slackUID <> "'.$_SESSION['slackUID'].'"');
        $newQuery = explode(' ', $_GET['search']);
        for ($i=0; $i<count($queries); $i++){
            $queryScores[$i]['query'] = $queries[$i];
            $queryScores[$i]['slackUID'] = $slackUIDs[$i];
            if ($timestamps[$i] > strtotime('-7 days')){
                $queryScores[$i]['timestamp'] = 'within a week ago';
            } else if ($timestamps[$i] > strtotime('-30 days')){
                $queryScores[$i]['timestamp'] = 'within a month ago';
            } else {
                $queryScores[$i]['timestamp'] = '1+ month ago';
            }
            $queryScores[$i]['slackName'] = CondSQL('display_name', 'channelUsers', 'uid="'.$queryScores[$i]['slackUID'].'"', $db);
            $queryScores[$i]['score'] = 0;
            $queries[$i] = explode(' ', $queries[$i]);
            for ($j=0; $j<count($queries[$i]); $j++){
                if (in_array($queries[$i][$j], $newQuery)){
                    $queryScores[$i]['score'] = $queryScores[$i]['score'] + 1;
                }
            }
            $queryScores[$i]['score'] = round($queryScores[$i]['score'] / count($queries[$i]), 2);
        }
        if ($queryScores !== null){
            usort($queryScores, function($a, $b){
                if ($a["score"] == $b["score"])
                    return (0);
                return (($a["score"] > $b["score"]) ? -1 : 1);
            });
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
    <title>Ask</title>
</head>
<body id="askBody">
    <header><?=$header?></header>
    <form action="" method="get">
        <input id="askSearchBar" type="search" name="search" placeholder="Search a question here...">
        <input id="customSearchBtn" type="submit" value="Custom Search">
    </form>
    <div>
        <div id="searchResults">
            <?php if ($_GET['search'] !== null){ ?>
                <?php foreach($searchResults as $searchResult){ ?>
                    <?php if ($searchResult['displayLink'] ===  'stackoverflow.com'){ ?>
                        <span class="results"><a target="_blank" href=<?=$searchResult['link']?>><?=h($searchResult['title']);?></a> (<a href="addStock.php?domain=stackoverflow&stackoName=<?=$_SESSION['stackoName']?>&title=<?=str_replace(' ', '_', $searchResult['title'])?>&link=<?=$searchResult['link']?>">Add</a>) (<?=$searchResult['stocked']?>)</span>
                    <?php } ?>
                    <?php if ($searchResult['displayLink'] ===  'qiita.com'){ ?>
                        <span class="results"><a target="_blank" href=<?=$searchResult['link']?>><?=h($searchResult['title']);?></a> (<a href="addStock.php?domain=qiita&stackoName=<?=$_SESSION['qiitaName']?>&title=<?=str_replace(' ', '_', $searchResult['title'])?>&url=<?=$searchResult['link']?>">Add</a>) (<?=$searchResult['stocked']?>)</span>
            <?php }}} ?>
        </div>
        <div id="userResults">
            <?php if($_GET['search'] !== null && $queryScores !== null){ ?>
                <?php foreach($queryScores as $queryScore){ ?>
                    <?php if($queryScore['score'] > 0.5){ ?>
                        <span><?=h($queryScore['slackName'])?></span><br>
                        <span class="matchScore">"<?=$queryScore['query']?>" (<?=h($queryScore['score'])?>)</span><br>
                        <span class="matchTime">~ <?=$queryScore['timestamp']?></span><br><br><br>
            <?php }}} ?>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        const searched = '<?php echo $_GET['search'] ?>';
        if (searched !== null){
            document.getElementById("askSearchBar").value = searched;
        }
    </script>
</body>
</script>