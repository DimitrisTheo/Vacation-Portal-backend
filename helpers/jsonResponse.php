<?php
function json_response($code, $array){
//    ignore_user_abort(true);
//    set_time_limit(0);
//
//    header('Connection: close');
//    header('Content-Length: '.ob_get_length());
//    ob_start();

    http_response_code($code);
    // send the response
    echo json_encode($array);
//
//    ob_end_flush();
//    ob_flush();
//    flush();
}