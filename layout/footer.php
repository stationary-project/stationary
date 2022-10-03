<?php


require_once 'config.php';
require_once 'functions.php';
$categories = getAllData("categories");
?>

<footer class="bg3 p-t-75 p-b-32">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 p-l-80 col-md-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Categories
                </h4>

                <ul>
                    <?php foreach ($categories as $category) : ?>
                        <li class="p-b-10">
                            <a href="./product.php?categoryid=<?= $category['id'] ?>" class="stext-107 cl7 hov-cl1 trans-04">
                                <?= $category['name'] ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>



            <div class="col-sm-6 col-md-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    KEEP IN TOUCH
                </h4>

                <p class="stext-107 cl7 size-201">
                    Any questions? <br> Just call us at (0779627573)
                </p>

            </div>

            <div class="col-sm-6 col-md-4 p-b-50">
                <h4 class="stext-301 cl0 p-b-30">
                    Newsletter
                </h4>

                <form action="" method="post">
                    <div class="wrap-input1 w-full p-b-4">
                        <input class="input1 bg-none plh1 stext-107 cl7" type="text" name="email" placeholder="email@example.com">
                        <div class="focus-input1 trans-04"></div>
                    </div>

                    <div class="p-t-18">
                        <button name="submit" type="submit" class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</footer>


<?php
require_once './config.php';
require_once './functions.php';

if (isset($_POST["submit"])) {

    $subscriber = subscriber("newsletter", $_POST["email"]);

    if ($subscriber != null) {
        echo "<script type='text/javascript'>toastr.warning('You are already subscribed')</script>";
        echo "	<script>
        						if ( window.history.replaceState ) {
        							window.history.replaceState( null, null, window.location.href );
        						}
        					</script>";
    } else {
        $sql = "insert into newsletter (email) values (:email)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([":email" => $_POST["email"]]);
        echo "<script type='text/javascript'>toastr.info('Subscribed in to the newsletter')</script>";
        echo "<script>if ( window.history.replaceState ) {
        							window.history.replaceState( null, null, window.location.href );
        						}
        	  </script>";
    }
}

?>