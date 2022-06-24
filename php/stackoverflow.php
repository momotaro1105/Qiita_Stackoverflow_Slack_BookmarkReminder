<?php
    // too slow, executed via javascript
    // function SOtop(){
    //     $key = 'atHW2PH8FqOLaBwxNb6dkw((';
    //     $date = '&fromdate='.strtotime(date('Ymd',strtotime("-1 days")));
    //     $base_url = "https://api.stackexchange.com/2.3/questions?page=1&";
    //     $pagesize = 10;
    //     $url = "{$base_url}pagesize=".$pagesize.$date."&order=desc&sort=votes&site=stackoverflow&key=".$key;
    //     $curl = curl_init();
    //     $option = [
    //         CURLOPT_URL => $url,
    //         CURLOPT_CUSTOMREQUEST => 'GET',
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ACCEPT_ENCODING => true,
    //     ];
    //     curl_setopt_array($curl, $option);
    //     $response = curl_exec($curl);
    //     curl_close($curl);
    //     return json_decode($response, true);
    // }

    function SOstock($userid){
        $key = 'atHW2PH8FqOLaBwxNb6dkw((';
        $base_url = "https://api.stackexchange.com/2.3/users/";
        $url = "{$base_url}".$userid."/favorites?site=stackoverflow&key=".$key;
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
?>