<?php
    function CustomSearch($query){
        $query = str_replace(' ', '+', $query);
        $url = "https://www.googleapis.com/customsearch/v1?key=AIzaSyBs20wcpET19m1Cj-Btq0BOz9eyrSv4oEk&cx=57804be1c5c8a9f26&q=".$query;
        $curl = curl_init();
        $option = [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($curl, $option);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
?>