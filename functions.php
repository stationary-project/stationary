<?php
// getAllData
function getAllData($table)
{
    global $conn;
    $sql = "SELECT * FROM $table";
    $stmt = $conn->prepare($sql);


    $stmt->execute();
    return $stmt->fetchAll();
}
// getOne
function getOneById($table, $id)
{
    global $conn;
    $sql = "SELECT * FROM $table WHERE id= $id";
    $stmt = $conn->prepare($sql);


    $stmt->execute();
    return $stmt->fetch();
}
// getOne
function getOneByEmail($table, $email)
{
    global $conn;
    $sql = "SELECT * FROM $table WHERE email= :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":email" => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function subscriber($table, $email)
{
    global $conn;
    $sql = "SELECT * FROM $table WHERE email= :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([":email" => $email]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// getDataByForeignKey
function getDataByForeignKey($table, $foreignKey)
{
    global $conn;
    $sql = "SELECT * FROM $table WHERE category_id= :foreignKey";
    $stmt = $conn->prepare($sql);

    $stmt->execute([":foreignKey" => $foreignKey]);
    return $stmt->fetchAll();
}
// getDataByForeignKey
function getDataByUserid($table, $foreignKey)
{
    global $conn;
    $sql = "SELECT * FROM $table WHERE user_id= :foreignKey";
    $stmt = $conn->prepare($sql);

    $stmt->execute([":foreignKey" => $foreignKey]);
    return $stmt->fetchAll();
}

// filterBySelectedCategoryInnerJoin
function filterBySelectedCategory($table1, $table2, $foreignKey)
{
    global $conn;
    $category_id = $foreignKey;
    $sql = "select * from $table1 
        inner join $table2
        on $table1.category_id = $table2.id";
    $query = $conn->prepare($sql);
    $query->execute([":category_id" => $category_id]);
    $filteredProducts = $query->fetchAll(PDO::FETCH_ASSOC);
    return $filteredProducts;
}
// filterBySelectedCategoryInnerJoin

function getBillDetails($id)
{
    global $conn;
    $stmt1 = $conn->prepare('SELECT *
        FROM
            products a
                INNER JOIN
            orders b
                ON a.id = b.product_id
                INNER JOIN 
            bill c
                ON b.bill_id = c.id WHERE c.id = ?;');

    $stmt1->execute(array($id));

    $productsForBill = $stmt1->fetchAll();

    return $productsForBill;
}

function getCartDetails($id)
{
    global $conn;
    $stmt1 = $conn->prepare('SELECT *
        FROM
            products a
                INNER JOIN
            cart b
                ON a.id = b.product_id
                INNER JOIN 
            users c
                ON b.user_id = c.id WHERE c.id = ?;');

    $stmt1->execute(array($id));

    $productsForBill = $stmt1->fetchAll();

    return $productsForBill;
}


function getRowsNumber($table, $condition = [])
{
    $key = array_keys($condition);
    if ($condition) {
        global $conn;
        $sql = "select count(*) from $table where " . $key[0] . " = :" . $key[0];
        $query = $conn->prepare($sql);
        $query->execute([":" . $key[0] => $condition["$key[0]"]]);
        $result = $query->fetch();
        return $result[0];
    } else {
        global $conn;
        $sql = "select count(*) from $table";
        $query = $conn->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        return $result[0];
    }
}


// getReviews
function getReviews($table, $table2, $foreignKey)
{
    global $conn;
    $sql = "SELECT * FROM $table 
    INNER JOIN $table2 
    ON $table.user_id = $table2.id 
    WHERE $table.product_id = $foreignKey";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}


// Check if user purchased the product 
function getRowsNumberOrders($table, $condition = [])
{
    $key = array_keys($condition);
    if ($condition) {
        global $conn;
        $sql = "select count(*) from $table where " . $key[0] . " = :" . $key[0] . " " . "and" . " " . $key[1] . " = :" . $key[1];
        $query = $conn->prepare($sql);
        $query->execute([":" . $key[0] => $condition["$key[0]"], ":" . $key[1] => $condition["$key[1]"]]);
        $result = $query->fetch();
        return $result[0];
    } else {
        global $conn;
        $sql = "select count(*) from $table";
        $query = $conn->prepare($sql);
        $query->execute();
        $result = $query->fetch();
        return $result[0];
    }
}


// Without loggedin the to cart senario


function addToCart($product, $qty)
{

    $product["quantity"] = $qty;
    if (!isset($_SESSION)) {
        session_start();
    }

    if (empty($_SESSION["cart"])) {
        $_SESSION["cart"][] = $product;
        echo '<script type="text/javascript">toastr.info("Added to cart")</script>';
    } else {
        foreach ($_SESSION["cart"] as $key => $stroedItems) {
            $ids[] = isset($stroedItems["id"]) ? $stroedItems["id"] : "";
        }

        if (!in_array($product["id"], $ids)) {

            $_SESSION["cart"][] = $product;
            echo '<script type="text/javascript">toastr.info("Added to cart")</script>';
        } else {
            foreach ($_SESSION["cart"] as $key => $stroedItems) {
                if ($product["id"] == $stroedItems["id"]) {
                    $_SESSION["cart"][$key]["quantity"]  += $qty;
                }
            }
            echo '<script type="text/javascript">toastr.warning("Updated cart quantity")</script>';
        }
    }
}


function updateCart($product_id, $qty)
{
    foreach ($_SESSION["cart"] as $key => $stroedItems) {
        if ($product_id == $stroedItems["id"]) {
            $_SESSION["cart"][$key]["quantity"] = $qty;
            if ($_SESSION["cart"][$key]["quantity"] == 0) {
                deleteFromCart($product_id);
            }
            echo '<script type="text/javascript">toastr.info("Updated quantity")</script>';
        }
    }
}



function deleteFromCart($product_id)
{
    // echo $product_id;
    foreach ($_SESSION["cart"] as $key => $stroedItems) {
        if ($product_id == $stroedItems["id"]) {
            unset($_SESSION["cart"][$key]);
            echo '<script type="text/javascript">toastr.error("remove from the cart")</script>';
        }
    }
}



function clearCartFromSession()
{
    unset($_SESSION["cart"]);
}



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
        $purshced = getDataByUserid("cart", $user_id);
        if ($purshced) {
            $sql = "delete from cart where user_id = :user_id";
            $query = $conn->prepare($sql);
            $query->execute([
                ":user_id" => $user_id,
            ]);
        }
    }
    foreach ($_SESSION["cart"] as  $value) {
        $sql = "insert into $table (user_id,product_id,quantity) values (:user_id,:product_id,:quantity)";
        $query = $conn->prepare($sql);
        $query->execute([
            ":user_id" => $user_id,
            ":product_id" => $value["id"],
            ":quantity" => $value["quantity"]
        ]);
    }

    clearCartFromSession();
}
