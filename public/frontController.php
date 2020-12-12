<?php

require_once 'vendor/autoload.php';
// create dotenv object
$dotenv = Dotenv\Dotenv::createImmutable("./");
$dotenv->load();

// Include files
require "./config/db.php";
require "./config/header.php";
require "./helpers/jsonResponse.php";
require "./helpers/emailHelper.php";

switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
    case '/':
        require 'controllers/login.php';
        break;
    case '/users':
        require 'controllers/getUsers.php';
        break;
    case '/createUser':
        require 'controllers/createUser.php';
        break;
    case '/submissions':
        require 'controllers/getSubmissions.php';
        break;
    case '/submitRequest':
        require 'controllers/createSubmission.php';
        break;
    case '/updateSubmission':
        require 'controllers/updateSubmission.php';
        break;
    default:
        http_response_code(404);
        exit('Not Found');
}
