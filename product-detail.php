<?php
if (!isset($_SESSION)) {
	session_start();
}
require_once './config.php';
require_once './functions.php';
$pageName = 'Product Detail';
if (isset($_SESSION["email"])) {
	$activeUser = getOneByEmail('users', $_SESSION["email"]);
	$user_id = $activeUser['id'];
}


?>
<!DOCTYPE html>
<html lang="en">
<?php require_once './layout/head.php' ?>

<body class="animsition">

	<!-- Header -->
	<?php require_once './layout/header.php' ?>

	<?php
	$id = $_GET["productid"];
	$product = getOneById("products", $id);
	$category_id = $product["category_id"];
	$category = getOneById("categories", $category_id);

	// related products
	$products = getDataByForeignKey("products", $category_id);
	?>
	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="stext-109 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<a href="product.php?categoryid=<?= $category["id"] ?>" class="stext-109 cl8 hov-cl1 trans-04">
				<?= $category["name"] ?>
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="stext-109 cl4">
				<?= $product["name"] ?>
			</span>
		</div>
	</div>




	<!-- Product Detail -->
	<section class="sec-product-detail bg0 p-t-65 p-b-60">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-7 p-b-30">
					<div class="p-l-25 p-r-30 p-lr-0-lg">
						<div class="wrap-slick3 flex-sb flex-w">
							<!-- <div class="wrap-slick3-dots"></div> -->
							<!-- <div class="wrap-slick3-arrows flex-sb-m flex-w"></div> -->


							<div class="wrap-pic-w pos-relative">
								<img src="./admin/img/<?php echo $product['image'] ?>" alt="IMG-PRODUCT">
							</div>

						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-5 p-b-30">
					<div class="p-r-50 p-t-5 p-lr-0-lg">
						<h4 class="mtext-105 cl2 js-name-detail p-b-14">
							<?= $product["name"] ?>
						</h4>


						<?php if ($product['discount'] != 1) : ?>
							<span style="text-decoration:line-through"><?php echo $product['price'] ?> JOD</span>
							<span class="mtext-106 cl2">
								<?php
								$discount = $product['price'] * $product['discount'];
								$priceAfterDiscount = $product['price'] - $discount;
								echo $priceAfterDiscount . " JOD"
								?>
							</span>
						<?php else : ?>
							<span><?php echo $product['price'] ?> JOD</span>
						<?php endif; ?>

						<p class="stext-102 cl3 p-t-23">
							<?= $product["description"] ?>
						</p>

						<?php if (isset($_SESSION["email"])) : ?>
							<div class="p-t-33">
								<form action="" method="post">
									<div class="flex-w flex-r-m p-b-10">
										<div class="size-204 flex-w flex-m respon6-next">
											<div class="wrap-num-product flex-w m-r-20 m-tb-10">
												<div class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
													<i class="fs-16 zmdi zmdi-minus"></i>
												</div>

												<input class="mtext-104 cl3 txt-center num-product" type="number" name="num-product" value="1">

												<div class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
													<i class="fs-16 zmdi zmdi-plus"></i>
												</div>
											</div>

											<button type="submit" name="submit" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-addcart-detail">
												Add to cart
											</button>
										</div>
									</div>
								</form>
							</div>

							<?php
							if (isset($_POST['submit'])) {
								$quantity = $_POST['num-product'];
								$sql = "SELECT * FROM cart WHERE product_id= $id AND user_id= $user_id";
								$stmt = $conn->prepare($sql);
								$stmt->execute();
								$productInCart = $stmt->fetchAll();

								// print_r($productInCart);
								// echo (count($productInCart));
								if (count($productInCart) > 0) {
									$quantityUpdated = (int)$productInCart[0]['quantity'] + (int)$quantity;
									// echo $quantityUpdated;
									$sql = "UPDATE cart SET quantity = $quantityUpdated WHERE product_id= $id AND user_id= $user_id";
									$stmt = $conn->prepare($sql);
									$stmt->execute();
									echo "<script> window.location = '#related'</script>";
									echo "<script type='text/javascript'>toastr.warning('Updated the quantity')</script>";
									echo "
								<script>
								if ( window.history.replaceState ) {
									window.history.replaceState( null, null, window.location.href );
								}
							</script>";
								} else {
									$sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id,$id,$quantity)";
									$stmt = $conn->prepare($sql);
									$stmt->execute();
									echo "<script> window.location = '#related'</script>";
									echo '<script type="text/javascript">toastr.info("Added to cart")</script>';
									echo "
								<script>
								if ( window.history.replaceState ) {
									window.history.replaceState( null, null, window.location.href );
								}
							</script>";
								}
							}
							?>

						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php
			$reviews = getReviews("reviews", "users", $product["id"]);
			if (isset($_SESSION["email"])) {
				$row = getRowsNumberOrders("orders", ["user_id" => $user_id, "product_id" => $product["id"]]);
			}
			$reviewsRowsNumber = getRowsNumber("reviews", ["product_id" => $product["id"]]);
			?>

			<div class="bor10 m-t-50 p-t-43 p-b-40">
				<!-- Tab01 -->
				<div class="tab01">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item p-b-10">
							<a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a>
						</li>


						<li class="nav-item p-b-10">
							<a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews (<?= $reviewsRowsNumber ?>)</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content p-t-43">
						<!-- - -->
						<div class="tab-pane fade show active" id="description" role="tabpanel">
							<div class="how-pos2 p-lr-15-md">
								<p class="stext-102 cl6">
									<?= $product["description"] ?>
								</p>
							</div>
						</div>



						<!-- - -->
						<div class="tab-pane fade" id="reviews" role="tabpanel">
							<div class="row">
								<div class="col-sm-10 col-md-8 col-lg-6 m-lr-auto">
									<div class="p-b-30 m-lr-15-sm">
										<!-- Review -->
										<?php foreach ($reviews as $review) : ?>
											<div class="flex-w flex-t p-b-68">

												<div class="size-207">
													<div class="flex-w flex-sb-m p-b-17">
														<span class="mtext-107 cl2 p-r-20">
															<?= $review["f_name"] . " " . $review["l_name"] ?>
														</span>

														<span class="fs-18 cl11">
															<?php for ($i = 0; $i < 5; $i++) : ?>
																<?php if ($i < $review["stars"]) : ?>
																	<i class="zmdi zmdi-star"></i>
																<?php else : ?>
																	<i class="zmdi zmdi-star-outline"></i>
																<?php endif; ?>
															<?php endfor; ?>
														</span>
													</div>

													<p class="stext-102 cl6">
														<?= $review["review"] ?>
													</p>
												</div>
											</div>
										<?php endforeach; ?>

										<?php if (isset($_SESSION["email"])) : ?>
											<?php if ($row > 0) : ?>
												<!-- Add review -->
												<form class="w-full" action="./reviews.php" method="post">
													<input type="hidden" value="<?= $id ?>" name="productid">
													<h5 class="mtext-108 cl2 p-b-7">
														Add a review
													</h5>
													<div class="flex-w flex-m p-t-50 p-b-23">
														<span class="stext-102 cl3 m-r-16">
															Your Rating
														</span>

														<span class="wrap-rating fs-18 cl11 pointer">
															<i class="item-rating pointer zmdi zmdi-star-outline"></i>
															<i class="item-rating pointer zmdi zmdi-star-outline"></i>
															<i class="item-rating pointer zmdi zmdi-star-outline"></i>
															<i class="item-rating pointer zmdi zmdi-star-outline"></i>
															<i class="item-rating pointer zmdi zmdi-star-outline"></i>
															<input class="dis-none" type="number" name="rating">
														</span>
													</div>

													<div class="row p-b-25">
														<div class="col-12 p-b-5">
															<label class="stext-102 cl3" for="review">Your review</label>
															<textarea class="size-110 bor8 stext-102 cl2 p-lr-20 p-tb-10" id="review" name="review"></textarea>
														</div>

													</div>

													<button class="flex-c-m stext-101 cl0 size-112 bg7 bor11 hov-btn3 p-lr-15 trans-04 m-b-10">
														Submit
													</button>
												</form>
											<?php endif; ?>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="bg6 flex-c-m flex-w size-302 m-t-73 p-tb-15">
			<span class="stext-107 cl6 p-lr-25">
				<?= $product["name"] ?>
			</span>

			<span id="related" class="stext-107 cl6 p-lr-25">
				<?= $category["name"] ?>
			</span>
		</div>
	</section>



	<!-- Related Products -->
	<section class="sec-relate-product bg0 p-t-45 p-b-105">
		<div class="container">
			<div class="p-b-45">
				<h3 class="ltext-106 cl5 txt-center">
					Related Products
				</h3>
			</div>

			<!-- Slide2 -->
			<div class="wrap-slick2">
				<div class="slick2">
					<?php foreach ($products as $product) : ?>
						<?php if ($product['id'] != $id) : ?>
							<div class="item-slick2 p-l-15 p-r-15 p-t-15 p-b-15">
								<!-- Block2 -->
								<div class="block2">
									<div class="block2-pic hov-img0">
										<img src="./admin/img/<?= $product["image"] ?>" alt="IMG-PRODUCT">

										<a href="product-detail.php?productid=<?php echo $product['id'] ?>" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04 ">
											View
										</a>
									</div>

									<div class="block2-txt flex-w flex-t p-t-14">
										<div class="block2-txt-child1 flex-col-l ">
											<a href="product-detail.php?productid=<?php echo $product['id'] ?>" class="stext-104 cl4 hov-cl1 trans-04 js-name-b2 p-b-6">
												<?= $product["name"] ?>
											</a>


											<?php if ($product['discount'] != 1) : ?>
												<span style="text-decoration:line-through"><?php echo $product['price'] ?> JOD</span>
												<span class="stext-105 cl3">
													<?php
													$discount = $product['price'] * $product['discount'];
													$priceAfterDiscount = $product['price'] - $discount;
													echo $priceAfterDiscount . " JOD"
													?>
												</span>
											<?php else : ?>
												<span><?php echo $product['price'] ?> JOD</span>
											<?php endif; ?>

										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>

				</div>
			</div>
		</div>
	</section>


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