<?php

const token = "test";

try {
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        // Takes raw data from the request
        $json = file_get_contents('php://input');
        // Converts it into a PHP object
        $data = json_decode($json);

//        if empty credentials, return error
        if( empty($data->email) || empty($data->password) ){
            json_response(500, array(
                "error" => "No Credentials Specified!",
            ));
        }

        $email = trim($data->email);
        $password = trim($data->password);
        
        $stmt = mysqli_prepare($connection, "SELECT * From users WHERE email = ? ");
        mysqli_stmt_bind_param($stmt, "s", $email);

        // If query fails, show the reason
        if (!mysqli_stmt_execute($stmt)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection),
            ));
        }
        else {
            $result = mysqli_stmt_get_result($stmt);
            if ($result->num_rows <= 0 ){
                json_response(401, array(
                    "error" => "No user account with this email",
                ));
            }
            // found account
            // Fetch user data
            while ($row =  $result->fetch_assoc()) {
                $id = $row['id'];
                $firstname = $row['first_name'];
                $lastname = $row['last_name'];
                $db_password = $row['password'];
                $user_type = $row['user_type'];
            }
            mysqli_free_result($result);
            mysqli_close($connection);

            // Verify password
            if ( password_verify($password, $db_password) ){
                json_response(200, array(
                    "token" => token,
                    "id" => $id,
                    "first_name" => $firstname,
                    "last_name" => $lastname,
                    "email" => $email,
                    "user_type" => $user_type
                    )
                );
            }
            else{
                json_response(401, array(
                    "error" => "Password Invalid",
                ));
            }
        }

    }
    else throw new Exception('Invalid Request', 200);
}
catch (Exception $exception){
    json_response($exception -> getCode(), array(
        "error" => $exception -> getMessage()
    ));
}