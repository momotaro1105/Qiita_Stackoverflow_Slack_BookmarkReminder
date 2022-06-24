<?php
    // function Qtop(){
    //     $date = date('Y-m-d',strtotime("-3 days"));
    //     $cond = urlencode('created:>'.$date.' stocks:>10');
    //     $base_url = "https://qiita.com/api/v2/items";
    //     $per_page = 10;
    //     $url = "{$base_url}?per_page=".$per_page."&query={$cond}";
    //     $curl = curl_init();
    //     $option = [
    //         CURLOPT_URL => $url,
    //         CURLOPT_CUSTOMREQUEST => 'GET',
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.'ca6a386884e9b3b638c33f3c43860e9345673d58'],
    //     ];
    //     curl_setopt_array($curl, $option);
    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     return json_decode($response, true);
    // }

    function Qstock($username){
        $base_url = "https://qiita.com/api/v2/users";
        $url = "{$base_url}/".$username."/stocks";
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER =>  ['Authorization: Bearer '.'ca6a386884e9b3b638c33f3c43860e9345673d58'],
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
?>