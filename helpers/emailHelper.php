<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class emailHelper {

    // passing true in constructor enables exceptions in PHPMailer
    private PHPMailer $mail;

    function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->IsSMTP();
        // set mail settings and credentials
        $this->mail->SMTPDebug = 1;
        $this->mail->SMTPAuth = TRUE;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;;
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->isHTML(true);
   }

    function sendEmailToAdmin($connection, $submission_id, $data)
    {

        //            get admin's email
        $stmt = mysqli_prepare($connection, "SELECT email From users WHERE user_type = 'admin' ");
        if (!mysqli_stmt_execute($stmt) ) {
            json_response(500, array(
                "error" => "SQL query failed: " . mysqli_error($connection),
            ));
        }

        $result = mysqli_stmt_get_result($stmt);
        while ($row = $result->fetch_row()) {
            $admin_email = $row[0];
        }

        //            now get employee that made the request full name
        $stmt = mysqli_prepare($connection, "SELECT first_name, last_name From users WHERE id = ? ");
        mysqli_stmt_bind_param($stmt, "s", $data->user_id);

        if (!mysqli_stmt_execute($stmt)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection),
            ));
        }
        $result = mysqli_stmt_get_result($stmt);
        while ($row = $result->fetch_row()) {
            $employee_first_name = $row[0];
            $employee_last_name = $row[1];
        }

        try {
            $this->mail->setFrom($admin_email, "Admin");
            $this->mail->addAddress( $admin_email);
            $this->mail->Subject = "Vacation Request";
            $this->mail->Body = "Dear supervisor,<br>employee <b>" . $employee_first_name . " " . $employee_last_name . "</b> requested<br>for 
                some time off, starting on: " . $data->date_from . " " . " and ending on: " . $data->date_to . ",<br>
                stating the reason: " . $data->reason . "\nClick on one of the below links to approve or reject
                the application:<br> <a href='http://localhost:8000/updateSubmission?st=approved&id=" . $submission_id . "'>Approve</a> <br>
                <a href='http://localhost:8000/updateSubmission?st=rejected&id=" . $submission_id . "'>Reject</a>";

            $this->mail->send();
        } catch (Exception $e) {
            json_response(500,array(
                "error" => "Mail could not be sent. Mailer Error: {$this->mail->ErrorInfo}"
            ));
        }
    }

    function sendEmailToEmployee($connection, $submission_id)
    {
        //    get submission date and status for email
        $stmt = mysqli_prepare($connection, "select u.email, date_submitted, status from submissions 
                            inner join users u on submissions.user_id = u.id where submissions.id = ?");
        mysqli_stmt_bind_param($stmt, "s", $submission_id);
        if (!mysqli_stmt_execute($stmt)){
            json_response(500, array(
                "error" => "SQL query failed: " .mysqli_error($connection),
            ));
        }
        $result = mysqli_stmt_get_result($stmt);
        while ($row = $result->fetch_row()) {
            $employee_email = $row[0];
            $date_submitted = $row[1];
            $status = $row[2];
        }

        try {
            $this->mail->setFrom($employee_email);
            $this->mail->addAddress( $employee_email);
            $this->mail->Subject = "Vacation Request Feedback";
            $this->mail->Body = "Dear employee,<br>your supervisor has <b>" . $status . "</b> your application submitted on " . $date_submitted;

            $this->mail->send();
        } catch (Exception $e) {
            json_response(500,array(
                "error" => "Mail could not be sent. Mailer Error: {$this->mail->ErrorInfo}"
            ));
        }
    }


}