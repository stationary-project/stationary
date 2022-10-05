<?php
require_once "../../config.php";
require_once "../../functions.php";
$email = $_POST['emailLoginValue'];

$password = $_POST['passwordLoginValue'];


// instantiate LoginContr class
include "../classes/dbh.class.php";
include "../classes/login.class.php";
include "../classes/login-contr.class.php";
$login = new LoginContr($email, $password);


// run error handlers and user login
$login->loginUser();

if ($login->err) {
    echo $login->err;
} else {

    // Send the user to welcome page
    // use echo to send it as a response to js
    if (isset($_SESSION["cart"])) {
        insertCartAfterLogin("cart");
    }

    echo $login->location;
}
