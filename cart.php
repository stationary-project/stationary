<?php
session_start();
$pageName = "Cart";
require_once './config.php';
require_once './functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<?php require_once './layout/head.php'; ?>

<body class="animsition">

	<?php require_once './layout/header.php'; ?>


	<!-- breadcrumb -->
	<div class="container">
		<div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
			<a href="index.php" class="mtext-80 cl8 hov-cl1 trans-04">
				Home
				<i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
			</a>

			<span class="mtext-80 cl4">
				Shopping Cart
			</span>
		</div>
	</div>

	<?php

	$cartRows = 0;
	if (isset($_SESSION["email"])) {
		$activeUser = getOneByEmail('users', $_SESSION["email"]);
		$user_id = $activeUser['id'];
		// $userOrders = getDataByUserid('orders', $user_id);
		$userCart = getCartDetails($user_id);
		// print_r($userCart);
		$cartRows = getRowsNumber('cart', ["user_id" => $activeUser["id"]]);
	}

	?>

	<!-- Shoping Cart -->
	<form class="bg0 p-t-75 p-b-85">
		<div class="container">
			<div class="row">
				<?php if ($cartRows > 0) : ?>
					<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
						<div class="m-l-25 m-r--38 m-lr-0-xl">
							<div class="wrap-table-shopping-cart">
								<table class="table-shopping-cart">
									<tr class="table_head">
										<th class="column-1">Product</th>
										<th class="column-2"></th>
										<th class="column-3">Price</th>
										<th class="column-4">Quantity</th>
										<th class="column-5">Total</th>

									</tr>
									<?php foreach ($userCart as $item) : ?>

										<tr class="table_row">
											<td class="column-1">
												<div class="how-itemcart1">
													<img src="./admin/img/<?= $item['image']; ?>" alt="IMG">
												</div>
											</td>
											<td class="column-2"><?= $item['name']; ?></td>
											<td class="column-3">

												<?php if ($item['discount'] != 1) : ?>
													<span style="text-decoration:line-through"><?= $item['price']; ?> </span>
													<span>
														<?php
														$discount = $item['price'] * $item['discount'];
														$priceAfterDiscount = $item['price'] - $discount;
														echo $priceAfterDiscount . " JOD";
														?>

													</span>
												<?php else : ?>
													<?= $item['price']; ?>
												<?php endif; ?>

											</td>
											<td class="column-4">
												<div class="wrap-num-product flex-w m-l-auto m-r-0">
													<button id="submit-minus<?= $item['product_id'] ?>" class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
														<i class="fs-16 zmdi zmdi-minus"></i>
													</button>

													<input class="mtext-104 cl3 txt-center num-product" type="number" id="num-product<?= $item['product_id'] ?>" value="<?= $item['quantity']; ?>">

													<button id="submit-plus<?= $item['product_id'] ?>" class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
														<i class="fs-16 zmdi zmdi-plus"></i>
													</button>
													<input class="mtext-104 cl3 txt-center num-product" type="hidden" id="user_id" value="<?= $user_id; ?>">
													<input class="mtext-104 cl3 txt-center num-product" type="hidden" id="product_id<?= $item['product_id'] ?>" value="<?= $item['product_id'] ?>">
													<?php

													echo "<script>


														$(document).ready(function(){
															$('#submit-minus" . $item['product_id'] . "').click(function(){
																var qty=$('#num-product" . $item['product_id'] . "').val();
																var user_id=$('#user_id').val();
																var product_id=$('#product_id" . $item['product_id'] . " ').val();

																
																$.ajax({
																	url:'updatecart.php',
																	method:'POST',
																	data:{
																		qty:qty,
																		user_id:user_id,
																		product_id:product_id
																	},
																	
																});
															});
															
														});
														</script>";

													echo "<script>
														
														$(document).ready(function(){
															$('#submit-plus" . $item['product_id'] . "').click(function(){
																var qty=$('#num-product" . $item['product_id'] . "').val();
																var user_id=$('#user_id').val();
																var product_id=$('#product_id" . $item['product_id'] . " ').val();

																
																$.ajax({
																	url:'updatecart.php',
																	method:'POST',
																	data:{
																		qty:qty,
																		user_id:user_id,
																		product_id:product_id
																	},
																	
																});
															});
														
														});
														</script>";




													?>
												</div>
											</td>
											<td class="column-5">

												<?php if ($item['discount'] != 1) : ?>
													<?= $priceAfterDiscount *  $item['quantity']; ?> JOD
												<?php else : ?>
													<?= $item['price'] *  $item['quantity']; ?>
												<?php endif; ?>


											</td>
											<!-- <td>
												<a href="cart.php?productid=<?php //$item['id'] 
																			?>" class="btn btn-danger">Delete</a>
											</td> -->
										</tr>
									<?php endforeach; ?>

								</table>
							</div>
						</div>
					</div>


					<?php
					$total = 0;
					foreach ($userCart as $item) {
						if ($item['discount'] != 1) {
							$discount = $item['price'] * $item['discount'];
							$priceAfterDiscount = $item['price'] - $discount;
							$total += ($priceAfterDiscount * $item['quantity']);
						} else {

							$total += ($item['price'] * $item['quantity']);
						}
					}

					?>
					<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
						<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
							<h4 class="mtext-109 cl2 p-b-30">
								Cart Totals
							</h4>

							<div class="flex-w flex-t p-t-27 p-b-33">
								<div class="size-208">
									<span class="mtext-101 cl2">
										Total:
									</span>
								</div>

								<div class="size-209 p-t-1">
									<span class="mtext-110 cl2">
										<?= $total ?> JOD
									</span>
								</div>
							</div>
							<a href="./checkout.php" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
								Proceed to Checkout

							</a>
						</div>
					</div>
				<?php elseif (isset($_SESSION["cart"])) : ?>
					<div class="col-lg-10 col-xl-7 m-lr-auto m-b-50">
						<div class="m-l-25 m-r--38 m-lr-0-xl">
							<div class="wrap-table-shopping-cart">
								<table class="table-shopping-cart">
									<tr class="table_head">
										<th class="column-1">Product</th>
										<th class="column-2"></th>
										<th class="column-3">Price</th>
										<th class="column-4">Quantity</th>
										<th class="column-5">Total</th>

									</tr>

									<?php foreach ($_SESSION["cart"] as $item) : ?>

										<tr class="table_row">
											<td class="column-1">
												<div class="how-itemcart1">
													<img src="./admin/img/<?= $item['image']; ?>" alt="IMG">
												</div>
											</td>
											<td class="column-2"><?= $item['name']; ?></td>
											<td class="column-3">

												<?php if ($item['discount'] != 1) : ?>
													<span style="text-decoration:line-through"><?= $item['price']; ?> </span>
													<span>
														<?php
														$discount = $item['price'] * $item['discount'];
														$priceAfterDiscount = $item['price'] - $discount;
														echo $priceAfterDiscount . " JOD";
														?>

													</span>
												<?php else : ?>
													<?= $item['price']; ?>
												<?php endif; ?>

											</td>
											<td class="column-4">
												<div class="wrap-num-product flex-w m-l-auto m-r-0">
													<button id="submit-minus<?= $item['id'] ?>" class="btn-num-product-down cl8 hov-btn3 trans-04 flex-c-m">
														<i class="fs-16 zmdi zmdi-minus"></i>
													</button>

													<input class="mtext-104 cl3 txt-center num-product" type="number" id="num-product<?= $item['id'] ?>" value="<?= $item['quantity']; ?>">

													<button id="submit-plus<?= $item['id'] ?>" class="btn-num-product-up cl8 hov-btn3 trans-04 flex-c-m">
														<i class="fs-16 zmdi zmdi-plus"></i>
													</button>
													<input class="mtext-104 cl3 txt-center num-product" type="hidden" id="product_id<?= $item['id'] ?>" value="<?= $item['id'] ?>">
													<?php

													echo "<script>


														$(document).ready(function(){
															$('#submit-minus" . $item['id'] . "').click(function(){
																var qty=$('#num-product" . $item['id'] . "').val();
																var product_id=$('#product_id" . $item['id'] . " ').val();

																
																$.ajax({
																	url:'updatecart.php',
																	method:'POST',
																	data:{
																		qty:qty,
																		product_id:product_id
																	},
																	
																});
															});
															
														});
														</script>";

													echo "<script>
														
														$(document).ready(function(){
															$('#submit-plus" . $item['id'] . "').click(function(){
																var qty=$('#num-product" . $item['id'] . "').val();
																var product_id=$('#product_id" . $item['id'] . " ').val();

																
																$.ajax({
																	url:'updatecart.php',
																	method:'POST',
																	data:{
																		qty:qty,
																		product_id:product_id
																	},
																	
																});
															});
														
														});
														</script>";




													?>
												</div>
											</td>
											<td class="column-5">

												<?php if ($item['discount'] != 1) : ?>
													<?= $priceAfterDiscount *  $item['quantity']; ?> JOD
												<?php else : ?>
													<?= $item['price'] *  $item['quantity']; ?>
												<?php endif; ?>


											</td>
											<!-- <td>
												<a href="cart.php?productid=<?php //$item['id'] 
																			?>" class="btn btn-danger">Delete</a>
											</td> -->
										</tr>
									<?php endforeach; ?>

								</table>
							</div>
						</div>
					</div>


					<?php
					$total = 0;
					foreach ($_SESSION["cart"] as $item) {
						if ($item['discount'] != 1) {
							$discount = $item['price'] * $item['discount'];
							$priceAfterDiscount = $item['price'] - $discount;
							$total += ($priceAfterDiscount * $item['quantity']);
						} else {

							$total += ($item['price'] * $item['quantity']);
						}
					}

					?>
					<div class="col-sm-10 col-lg-7 col-xl-5 m-lr-auto m-b-50">
						<div class="bor10 p-lr-40 p-t-30 p-b-40 m-l-63 m-r-40 m-lr-0-xl p-lr-15-sm">
							<h4 class="mtext-109 cl2 p-b-30">
								Cart Totals
							</h4>

							<div class="flex-w flex-t p-t-27 p-b-33">
								<div class="size-208">
									<span class="mtext-101 cl2">
										Total:
									</span>
								</div>

								<div class="size-209 p-t-1">
									<span class="mtext-110 cl2">
										<?= $total ?> JOD
									</span>
								</div>
							</div>
							<a href="./checkout.php" class="flex-c-m stext-101 cl0 size-116 bg3 bor14 hov-btn3 p-lr-15 trans-04 pointer">
								Proceed to Checkout

							</a>
						</div>
					</div>
				<?php else : ?>
					<h1 class="cl4">Your cart is empty !</h1>
				<?php endif; ?>
			</div>
		</div>
	</form>


	<?php
	// if (isset($_GET["productid"])) {

	// 	// echo "<script>alert(" . $_GET["productid"] . ")</script>";
	// 	deleteFromCart($_GET["productid"]);
	// 	echo "<script>
	// 	    window.onload = function() {
	// 	      if (!window.location.hash) {
	// 	        window.location = window.location;
	// 	        window.location.reload();
	// 	      }
	// 	    }
	// 	  </script>
	// 	";
	// }


	?>


	<!-- Footer -->
	<?php require_once './layout/footer.php' ?>


	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

	<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
	<script>
		$(".js-select2").each(function() {
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
	<!--===============================================================================================-->
	<script src="vendor/MagnificPopup/jquery.magnific-popup.min.js"></script>
	<!--===============================================================================================-->
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script>
		$('.js-pscroll').each(function() {
			$(this).css('position', 'relative');
			$(this).css('overflow', 'hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function() {
				ps.update();
			})
		});
	</script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>

</html>