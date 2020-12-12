<?php

try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        $submission_id = $_GET["id"];
        $new_status = $_GET["st"];

        $stmt = mysqli_prepare($connection, "UPDATE submissions SET status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ss", $new_status, $submission_id);
        // If query fails, show the reason
        if (!mysqli_stmt_execute($stmt)){
            die("SQL query failed: " .mysqli_error($connection));
        }

        $emailHelper = new emailHelper();
        $emailHelper->sendEmailToEmployee($connection, $submission_id);

        json_response(200, array(
            "message" => "Request ".$new_status ."!"
        ));


    }

}
catch (Exception $exception){
    json_response($exception -> getCode(), array(
        "error" =>$exception -> getMessage()
    ));
}
