<?php

try {
    if($_SERVER["REQUEST_METHOD"] === "GET"){
        $sql = "SELECT * From users WHERE user_type = 'employee'";

        // If query fails, show the reason
        if (!$query = mysqli_query($connection, $sql)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection)
            ));
        }

        // Check if users exist
        if( mysqli_num_rows($query) <= 0) {
            json_response(200, array(
                "users" => ""
            ));
        }
        else {
            // Fetch users
            $users = array();
            while ($row = $query->fetch_assoc()) {
                $users[] = $row;
            }

//            return data
            json_response(200, array(
                "users" => $users
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