<?php
    function CustomSearch($query, $key){
        $query = str_replace(' ', '+', $query);
        $url = "https://www.googleapis.com/customsearch/v1?key=".$key."&q=".$query;
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