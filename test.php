<?php

function insertCartAfterLogin($table)
{
    // // print_r($_SESSION["cart"]);
    // echo $_SESSION["cart"][0]["id"];
    if (!isset($_SESSION)) {
        session_start();
    }
    global $conn;

    if (isset($_SESSION["email"])) {
        $activeUser = getOneByEmail("users", $_SESSION["email"]);
        $user_id = $activeUser["id"];
        $cartUser = getDataByUserid("cart", $user_id);
    }
    foreach ($_SESSION["cart"] as $key => $value) {
        for ($i = 0; $i < count($cartUser); $i++) {
            if ($cartUser[$i]["product_id"] == $value["id"]) {
                $sql = "update $table set quantity = :quantity";
                $query = $conn->prepare($sql);
                $query->execute([
                    ":quantity" => $value["quantity"]
                ]);
            } else {
                $sql = "insert into $table (user_id,product_id,quantity) values (:user_id,:product_id,:quantity)";
                $query = $conn->prepare($sql);
                $query->execute([
                    ":user_id" => $user_id,
                    ":product_id" => $value["id"],
                    ":quantity" => $value["quantity"]
                ]);
            }
        }
    }

    clearCartFromSession();
}
