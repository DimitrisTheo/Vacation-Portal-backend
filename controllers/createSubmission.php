<?php
try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);

//        if empty credentials, return error
        if( empty($data->date_from) || empty($data->date_to) || empty($data->reason) ){
            json_response(500, array(
                "error" => "No Input Values Specified!",
            ));
        }

        $stmt = mysqli_prepare($connection, "INSERT INTO submissions (date_submitted, date_from, date_to, reason, user_id) VALUES (?,?,?,?,?) ");
        $date_submitted = date("Y-m-d"); // today
        mysqli_stmt_bind_param($stmt, "sssss", $date_submitted, $data->date_from, $data->date_to, $data->reason, $data->user_id);

        // If query fails, show the reason
        if (!mysqli_stmt_execute($stmt)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection)
            ));
        }
        else {

//            get id of last inserted element
            $submission_id = mysqli_insert_id($connection);
//          Call function to send email to admin
            $emailHelper = new emailHelper();
            $emailHelper->sendEmailToAdmin($connection, $submission_id, $data);

            json_response(200, array(
                "message" => "Request Submitted to Admin"
            ));
        }
    }
    else throw new Exception('Invalid Request', 200);

}
catch (Exception $exception){
    json_response($exception -> getCode(), array(
        "error" => $exception -> getMessage()
    ));
}
