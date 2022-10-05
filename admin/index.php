<?php


if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["email"])) {
    require_once '../config.php';
    require_once '../functions.php';
    $activeUser = getOneByEmail("users", $_SESSION["email"]);
    $user_role = $activeUser["role"];
    if ($user_role != "admin") {
        header('Location: ' . $_SESSION['current_page']);
    }
} else {
    header('Location: ' . $_SESSION['current_page']);
}


include "layout\head.php";
include "layout\header.php"



?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <?php
    require_once "backend/connection.php";
    require_once "backend/functions.php";

    ?>

    <?php
    require_once "backend/admin_content.php";

    ?>


    <!-- Page Heading -->

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php

include "layout/footer.php"



?>