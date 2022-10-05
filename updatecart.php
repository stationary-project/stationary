<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once './config.php';
require_once './functions.php';

if (isset($_SESSION["email"])) {
    $user_id = $_POST['user_id'];
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    if ($qty == 0) {
        $sql = "DELETE FROM cart WHERE product_id= $product_id AND user_id= $user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    } else {

        $sql = "UPDATE cart SET quantity = $qty WHERE product_id= $product_id AND user_id= $user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    echo "Cart updated successfully";
} else {

    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];
    // $product = getOneById("products", $product_id);
    updateCart($product_id, $qty);
}
