<?php
session_start();
$pageName = "profile";
require_once './config.php';
require_once './functions.php';

?>
<!DOCTYPE html>
<html lang="en">

<?php require_once './layout/head.php'; ?>

<body class="animsition">

    <?php require_once './layout/header.php'; ?>




    <?php


    if ($_SESSION["email"]) {
        $activeUser = getOneByEmail('users', $_SESSION["email"]);
    } else {
        echo "<script>window.location =  './index.php'</script>";
    }

    $user_id = $activeUser['id'];
    // $userOrders = getDataByUserid('orders', $user_id);
    $userBills = getDataByUserid('bill', $user_id);
    // print_r($userBills);






    ?>
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92 bg2
">
        <div class="row container">
            <div class="col-6">
                <h2 class="pl-5 ml-5">Profile Page</h2>
            </div>

            <div class="col-3 text-center">
                <h2>Name</h2>
                <h4> <?php echo $activeUser["f_name"] . " " . $activeUser['l_name'] ?></h4>
                <hr>
                <h2>Email</h2>
                <h4><?php echo $activeUser["email"] ?></h4>
                <?php if ($activeUser['role'] == 'admin') : ?>
                    <hr>
                    <a href="./admin/index.php" style="color:#2a67b2; 
">
                        Go To Dashboard
                    </a>
                <?php endif ?>

            </div>

            <div class="col-2">
            </div>
        </div>
        <!-- <h2 class="mtext-105 cl0 txt-center p-b-20" style="color: black;">
         
        </h2> -->

    </section>
    <!-- <section class="sec-product bg0 p-t-10 p-b-50">
        <div class="container">
            <div class="p-b-32">
                <h3 class="ltext-105 cl5 txt-center respon1"></h3>
                <h5 class="mtext-105 cl5 txt-center respon1"></h5>
            </div>
        </div>
    </section> -->
    <section class="sec-product bg0 p-t-10 p-b-50">
        <div class="container">
            <div class="p-t-32">
                <h3 class="mtext-105 cl5 txt-center respon1">Order History</h3>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
                <div class="m-l-25 m-r--38 m-lr-0-xl">
                    <div class="wrap-table">
                        <table class="table">
                            <tr class="table_head">
                                <th class="column-1">Bill Number</th>

                                <th class="column-2">Date Ordered</th>
                                <th class="column-3">Total Price</th>
                                <th class="column-4">Order Details</th>
                            </tr>
                            <?php foreach ($userBills as $bill) : ?>
                                <tr class="table_row">

                                    <td class="column-1">
                                        <?= $bill['bill_number'] ?>
                                    </td>
                                    <td class="column-2"><?= $bill['date_ordered'] ?></td>
                                    <td class="column-3"><?= $bill['total_price'] ?> JOD </td>
                                    <td class="column-4 ">
                                        <a href="./order-details.php?bill_id=<?= $bill['id'] ?>&bill_num=<?= $bill['bill_number'] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
                                            View
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>




    <!-- Footer -->
    <?php require_once './layout/footer.php'; ?>


    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>

    <?php require_once './layout/scripts.php'; ?>



</body>

</html>