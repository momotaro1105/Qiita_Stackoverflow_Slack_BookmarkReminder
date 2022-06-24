<?php
    // PHP用 console.log
    function console_log($input){
        echo '<script>console.log('.json_encode($input).')</script>';
    }

    // HTML entityに変換
    function h($input){
        return htmlspecialchars($input, ENT_QUOTES);
    }
?>