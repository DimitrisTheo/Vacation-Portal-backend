<?php

//Database credentials
//stored in .env file

/* Attempt to connect to MySQL database */
$connection = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE'])
    or die("Database connection not established: " . mysqli_connect_error());
