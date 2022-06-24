<?php
    function Qstock($username, $token){
        $base_url = "https://qiita.com/api/v2/users";
        $url = "{$base_url}/".$username."/stocks";
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.$token],
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
?>