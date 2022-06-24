<?php
    function getAuth($token, $clientID, $clientSecret){
        $url = "https://slack.com/api/oauth.v2.access?client_id=".$clientID."&client_secret=".$clientSecret."=".$token;
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ACCEPT_ENCODING => true,
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    function Sstock($token){
        $url = "https://slack.com/api/stars.list?pretty=1";
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ACCEPT_ENCODING => true,
            CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.$token],
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($response, true)['items'];
        $result = array();
        for ($i=0; $i<count($res); $i++){
            $result[$i]['text'] = $res[$i]['message']['text'];
            $result[$i]['permalink'] = $res[$i]['message']['permalink'];
        }
        return $result;
    }

    function getChannelUsers($token){
        $url = "https://slack.com/api/users.list?team_id=T03BKPV9GFJ&pretty=1";
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ACCEPT_ENCODING => true,
            CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.$token],
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    function sendDM($token, $channel, $url){
        $url = "https://slack.com/api/chat.postMessage?channel=".$channel."&text=".$url."&pretty=1";
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ACCEPT_ENCODING => true,
            CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.$token],
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
?>