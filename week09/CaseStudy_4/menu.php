<?php
$servername = "localhost";
$username = "root";
$dbname = "javajam";

// Create connection
$conn = new mysqli($servername, $username, '', $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";


$var1 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=1");
$var2 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=2");
$var3 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=3");
$var4 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=4");
$var5 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=5");

while($row = mysqli_fetch_assoc($var1)) {
	$price1 = $row['Price'];
};
while($row = mysqli_fetch_assoc($var2)) {
	$price2 = $row['Price'];
};
while($row = mysqli_fetch_assoc($var3)) {
	$price3 = $row['Price'];
};
while($row = mysqli_fetch_assoc($var4)) {
	$price4 = $row['Price'];
};
while($row = mysqli_fetch_assoc($var5)) {
	$price5 = $row['Price'];
};



mysqli_close($conn);

?>
<!DOCTYPE html>

<html lang="en">

	<head>
		<title>JavaJam Coffee House</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="index.css" />
		<link rel="stylesheet" href="menu.css" />
		<script type="text/javascript" src="menu.js"></script>


	</head>

	<body>
		<div class="container">
			<header>
				<img src="assets/javalogo.png">
			</header>
			<div class="main">
				<nav>
					<a href="index.html">Home</a>
					<a href="menu.html">Menu</a>
					<a href="music.html">Music</a>
					<a href="jobs.html">Jobs</a>
				</nav>
				<div class="menu-content">
					<h1>Coffee at JavaJam</h1>
					<form id="order-form">
						<table>
							<tr>
								<td>
									<b>Just Java</b>
								</td>
								<td>
									Regular house blend, decaffeinated coffee, or flavor of the day<br />
									<b>Endless Cup $<?= $price1 ?></b>
								</td>
								<td class="qty">
									<span>Quantity</span>
									<input type="number" min="0" value="0" name="qtyjustJava"
										id="qty-just-java"></input>
								</td>
								<td class="subtotal">
									<span>Subtotal</span>

									<input id="subtotal-just-java" value=0 disabled></input>
								</td>
							</tr>
							<tr>
								<td>
									<b>Cafe au lait</b>
								</td>
								<td>
									House blended coffee infused to a smooth, steamed milk.<br />
									<b><input type="radio" name="cafeAuLait" id="single" value="2.00" checked>
										Single  $<?= $price2 ?>
										<input type="radio" name="cafeAuLait" id="double" value="3.00">
										Double  $<?= $price3 ?></b>
								</td>
								<td class="qty">
									<span>Quantity</span>
									<input type="number" min="0" value="0" name="qtyCafeAuLait"
										id="qty-cafe-au-lait"></input>
								</td>
								<td class="subtotal">
									<span>Subtotal</span>
									<input id="subtotal-cafe-au-lait" value=0 disabled></input>
								</td>
							</tr>
							<tr>
								<td>
									<b>Iced Cappuccino</b>
								</td>
								<td>
									Sweetened espresso blended with icy-cold milk and served in a chilled glass.<br />
									<b><input type="radio" name="icedCappuccino" id="single" value="4.75" checked>
										Single $<?= $price4 ?>
										<input type="radio" name="icedCappuccino" id="double" value="5.75">
										Double $ <?= $price5 ?></b>
								</td>
								<td class="qty">
									<span>Quantity</span>
									<input type="number" min="0" value="0" name="qtyIcedCap" id="qty-iced-cap"></input>
								</td>
								<td class="subtotal">
									<span>Subtotal</span>

									<input id="subtotal-iced-cap" value=0 disabled></input>
								</td>
							</tr>
							<tfoot>
								<td class="totalContainer" colspan="4">
									<span>Total</span>
									<input id="total-order" value="$0.00" disabled>

								</td>
							</tfoot>

						</table>
						<div class="checkoutBtn">
							<input id="checkout-btn" type="submit" value="Check out">
						</div>
					</form>

				</div>
			</div>

			<footer>
				<small><i>Copyright &copy2014 JavaJam Coffee House</i></small>
				<br />
				<small>
					<a href="mailto:vanessa.chandra2002@gmail.com"><i>vanessachristy@chandra.com</i>
					</a>
				</small>
			</footer>
		</div>

	</body>

</html>