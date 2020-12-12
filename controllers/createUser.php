<?php

try {
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);

//        if empty credentials, return error
        if( empty($data->email) || empty($data->password) || empty($data->last_name)
            || empty($data->first_name) || empty($data->user_type || empty($data->action)) ){
            json_response(500, array(
                "error" => "No Input Values Specified!"
            ));
        }

        // hash password
        if ($data->action === "create") {
            $stmt = mysqli_prepare($connection, "INSERT INTO users (first_name, last_name, email, password, user_type) VALUES (?,?,?,?,?) ");
            mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $password, $data->user_type);
            $message = "User Created Successfully";
        }
        elseif ($data->action === "update"){
            $stmt = mysqli_prepare($connection, "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? , user_type =? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "ssssss", $first_name, $last_name, $email, $password, $data->user_type, $data->id);
            $message = "User Updated Successfully";
        }

        $first_name = trim($data->first_name);
        $last_name = trim($data->last_name);
        $email = trim($data->email);
        $password = password_hash($data->password, PASSWORD_DEFAULT);


        // If query fails, show the reason
        if (!mysqli_stmt_execute($stmt)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection)
            ));
        }
        else {
            json_response(200, array(
                "message" => $message
            ));
            mysqli_stmt_close($stmt);
        }

    }
    else throw new Exception('Invalid Request', 200);

}
catch (Exception $exception){
    json_response($exception -> getCode(), array(
        "error" => $exception -> getMessage()
    ));
}