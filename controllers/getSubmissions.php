<?php
try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
//        first check if url contains the id
//        of a user in order to find his past submissions
        $user_id = $_GET["id"];
        if (empty($user_id)) {
            json_response(500, array(
                "error" => "No User specified to search for!"
            ));
        }

        $stmt = mysqli_prepare($connection, "SELECT * From submissions WHERE user_id = ? ORDER BY date_submitted DESC ");
        mysqli_stmt_bind_param($stmt, "s", $user_id);

        // If query fails, show the reason
        if (!mysqli_stmt_execute($stmt)) {
            json_response(500, array(
                "error" => "SQL query failed: " . mysqli_error($connection)
            ));
        } else {
            $result = mysqli_stmt_get_result($stmt);
//            if no results
            if ($result->num_rows <= 0) {
                json_response(200, array(
                    "submissions" => ""
                ));
            }
            // Fetch submissions
            $submissions = array();
            while ($row = $result->fetch_assoc()) {
                $submissions[] = $row;
            }
//            return data
            json_response(200, array(
                "submissions" => $submissions
            ));
        }
    }
}
catch (Exception $exception){
    json_response($exception -> getCode(), array(
        "error" => $exception -> getMessage()
    ));
}